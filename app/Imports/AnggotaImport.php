<?php

namespace App\Imports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\DB;

class AnggotaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    private $errors = [];
    private $imported = 0;
    private $processedNiks = [];
    private $errorTracker = []; // Track unique errors to prevent duplicates

    public function model(array $row)
    {
        try {
            // Debug: log the row data
            \Log::info('Import row data:', $row);
            
            // Check if this is a header row or empty row
            if (empty($row['nama_lengkap']) && empty($row['nik'])) {
                \Log::info('Skipping empty or header row');
                return null;
            }
            
            // Convert data types and clean up
            $namaLengkap = trim((string)($row['nama_lengkap'] ?? ''));
            $jenisKelamin = trim((string)($row['jenis_kelamin'] ?? ''));
            $nik = trim((string)($row['nik'] ?? ''));
            $alamat = trim((string)($row['alamat'] ?? ''));
            $nomorTelepon = trim((string)($row['nomor_telepon'] ?? ''));
            $email = trim((string)($row['email'] ?? ''));
            $kelasId = !empty($row['kelas_id']) ? (int)$row['kelas_id'] : null;
            $jabatan = trim((string)($row['jabatan'] ?? ''));
            $jenisAnggota = trim((string)($row['jenis_anggota'] ?? ''));
            $status = trim((string)($row['status'] ?? ''));
            $tanggalBergabung = !empty($row['tanggal_bergabung']) ? $row['tanggal_bergabung'] : now();
            
            // Handle NIK format issues (scientific notation from Excel)
            if (!empty($nik)) {
                // Convert scientific notation to regular number
                if (strpos($nik, 'E') !== false || strpos($nik, 'e') !== false) {
                    $nik = number_format((float)$nik, 0, '', '');
                }
                
                // Remove any non-numeric characters except dots and commas
                $nik = preg_replace('/[^0-9.,]/', '', $nik);
                
                // Convert to string and remove leading zeros if it's a valid number
                if (is_numeric($nik)) {
                    $nik = (string)(int)$nik; // Convert to integer then string to remove leading zeros
                }
            }
            
            // Manual validation
            if (empty($namaLengkap)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Nama lengkap wajib diisi");
                return null;
            }

            if (empty($nik)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": NIK wajib diisi");
                return null;
            }

            // Validate NIK format (should be numeric and reasonable length)
            if (!is_numeric($nik) || strlen($nik) < 10 || strlen($nik) > 20) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Format NIK tidak valid (harus angka, 10-20 digit)");
                return null;
            }

            // Check if NIK already exists in database
            if (Anggota::where('nik', $nik)->exists()) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": NIK {$nik} sudah terdaftar di database");
                return null;
            }

            // Check if NIK already processed in this import session
            if (in_array($nik, $this->processedNiks)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": NIK {$nik} duplikat dalam file import");
                return null;
            }

            // Validate email if provided
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Format email tidak valid");
                return null;
            }

            // Validate jenis kelamin
            if (!empty($jenisKelamin) && !in_array($jenisKelamin, ['Laki-laki', 'Perempuan'])) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Jenis kelamin harus 'Laki-laki' atau 'Perempuan'");
                return null;
            }

            // Validate kelas_id if provided
            if (!empty($kelasId) && !\App\Models\Kelas::find($kelasId)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": ID Kelas tidak valid");
                return null;
            }

            // Validate jenis_anggota if provided
            if (!empty($jenisAnggota) && !in_array($jenisAnggota, ['siswa', 'guru', 'staff'])) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Jenis anggota harus salah satu dari: siswa, guru, staff");
                return null;
            }

            // Validate status if provided
            if (!empty($status) && !in_array($status, ['aktif', 'nonaktif', 'ditangguhkan'])) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Status harus salah satu dari: aktif, nonaktif, ditangguhkan");
                return null;
            }

            // Add NIK to processed list
            $this->processedNiks[] = $nik;

            // Generate unique nomor anggota dan barcode using the robust method
            $maxRetries = 5;
            $retryCount = 0;
            $generatedData = null;
            
            while ($retryCount < $maxRetries) {
                try {
                    $generatedData = Anggota::generateUniqueCodes();
                    break;
                } catch (\Exception $e) {
                    $retryCount++;
                    if ($retryCount >= $maxRetries) {
                        $this->addUniqueError("Baris " . ($this->imported + 1) . ": Gagal generate kode unik setelah {$maxRetries} percobaan");
                        return null;
                    }
                    // Wait a bit before retrying
                    usleep(100000); // 0.1 second
                }
            }

            if (!$generatedData) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Gagal generate kode unik");
                return null;
            }

            $this->imported = $this->imported + 1;

            return new Anggota([
                'nomor_anggota' => $generatedData['nomor_anggota'],
                'barcode_anggota' => $generatedData['barcode_anggota'],
                'nama_lengkap' => $namaLengkap,
                'jenis_kelamin' => !empty($jenisKelamin) ? $jenisKelamin : null,
                'nik' => $nik,
                'alamat' => $alamat,
                'nomor_telepon' => $nomorTelepon,
                'email' => !empty($email) ? $email : null,
                'kelas_id' => $kelasId,
                'jabatan' => !empty($jabatan) ? $jabatan : null,
                'jenis_anggota' => !empty($jenisAnggota) ? $jenisAnggota : 'siswa',
                'status' => !empty($status) ? $status : 'aktif',
                'tanggal_bergabung' => $tanggalBergabung,
            ]);
        } catch (\Exception $e) {
            $this->addUniqueError("Error pada baris " . ($this->imported + 1) . ": " . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'nik' => 'nullable',
            'alamat' => 'nullable',
            'nomor_telepon' => 'nullable',
            'email' => 'nullable',
            'kelas_id' => 'nullable',
            'jabatan' => 'nullable',
            'jenis_anggota' => 'nullable',
            'status' => 'nullable',
            'tanggal_bergabung' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'kelas_id.exists' => 'ID Kelas tidak valid',
            'jenis_anggota.in' => 'Jenis anggota harus salah satu dari: siswa, guru, staff',
            'status.in' => 'Status harus salah satu dari: aktif, nonaktif, ditangguhkan',
            'tanggal_bergabung.date' => 'Format tanggal bergabung tidak valid',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function batchSize(): int
    {
        return 50; // Reduced batch size for better performance and less locking conflicts
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImportedCount()
    {
        return $this->imported;
    }

    // Method untuk menambahkan error yang unik (tidak duplikat)
    private function addUniqueError($errorMessage)
    {
        if (!in_array($errorMessage, $this->errorTracker)) {
            $this->errors[] = $errorMessage;
            $this->errorTracker[] = $errorMessage;
        }
    }
} 