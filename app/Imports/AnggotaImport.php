<?php

namespace App\Imports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AnggotaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    private $errors = [];
    private $imported = 0;

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
            $nik = trim((string)($row['nik'] ?? ''));
            $alamat = trim((string)($row['alamat'] ?? ''));
            $nomorTelepon = trim((string)($row['nomor_telepon'] ?? ''));
            $email = trim((string)($row['email'] ?? ''));
            $kelasId = !empty($row['kelas_id']) ? (int)$row['kelas_id'] : null;
            $jabatan = trim((string)($row['jabatan'] ?? ''));
            $jenisAnggota = trim((string)($row['jenis_anggota'] ?? ''));
            $status = trim((string)($row['status'] ?? ''));
            $tanggalBergabung = !empty($row['tanggal_bergabung']) ? $row['tanggal_bergabung'] : now();
            
            // Manual validation
            if (empty($namaLengkap)) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": Nama lengkap wajib diisi";
                return null;
            }

            if (empty($nik)) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": NIK wajib diisi";
                return null;
            }

            // Check if NIK already exists
            if (Anggota::where('nik', $nik)->exists()) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": NIK {$nik} sudah terdaftar";
                return null;
            }

            // Validate email if provided
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": Format email tidak valid";
                return null;
            }

            // Validate kelas_id if provided
            if (!empty($kelasId) && !\App\Models\Kelas::find($kelasId)) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": ID Kelas tidak valid";
                return null;
            }

            // Validate jenis_anggota if provided
            if (!empty($jenisAnggota) && !in_array($jenisAnggota, ['siswa', 'guru', 'staff'])) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": Jenis anggota harus salah satu dari: siswa, guru, staff";
                return null;
            }

            // Validate status if provided
            if (!empty($status) && !in_array($status, ['aktif', 'nonaktif', 'ditangguhkan'])) {
                $this->errors[] = "Baris " . ($this->imported + 1) . ": Status harus salah satu dari: aktif, nonaktif, ditangguhkan";
                return null;
            }

            // Generate unique nomor anggota dan barcode for each record
            $nomorAnggota = Anggota::generateNomorAnggota();
            $barcodeAnggota = Anggota::generateBarcodeAnggota();

            // Ensure uniqueness
            while (Anggota::where('nomor_anggota', $nomorAnggota)->exists()) {
                $nomorAnggota = Anggota::generateNomorAnggota();
            }
            
            while (Anggota::where('barcode_anggota', $barcodeAnggota)->exists()) {
                $barcodeAnggota = Anggota::generateBarcodeAnggota();
            }

            $this->imported = $this->imported + 1;

            return new Anggota([
                'nomor_anggota' => $nomorAnggota,
                'barcode_anggota' => $barcodeAnggota,
                'nama_lengkap' => $namaLengkap,
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
            $this->errors[] = "Error pada baris " . ($this->imported + 1) . ": " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'nullable',
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
        return 100;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImportedCount()
    {
        return $this->imported;
    }
} 