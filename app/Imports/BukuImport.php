<?php

namespace App\Imports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\DB;

class BukuImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    private $errors = [];
    private $imported = 0;
    private $processedBarcodes = [];
    private $errorTracker = [];

    public function model(array $row)
    {
        try {
            // Debug: log the row data
            \Log::info('Import buku row data:', $row);
            
            // Check if this is a header row or empty row
            if (empty($row['judul_buku'])) {
                \Log::info('Skipping empty or header row');
                return null;
            }
            
            // Convert data types and clean up
            $judulBuku = trim((string)($row['judul_buku'] ?? ''));
            $isbn = trim((string)($row['isbn'] ?? ''));
            $barcode = trim((string)($row['barcode'] ?? ''));
            $penulis = trim((string)($row['penulis'] ?? ''));
            $penerbit = trim((string)($row['penerbit'] ?? ''));
            $kategoriId = !empty($row['kategori_id']) ? (int)$row['kategori_id'] : null;
            $jenisId = !empty($row['jenis_id']) ? (int)$row['jenis_id'] : null;
            $sumberId = !empty($row['sumber_id']) ? (int)$row['sumber_id'] : null;
            $tahunTerbit = !empty($row['tahun_terbit']) ? (int)$row['tahun_terbit'] : null;
            $jumlahHalaman = !empty($row['jumlah_halaman']) ? (int)$row['jumlah_halaman'] : null;
            $bahasa = trim((string)($row['bahasa'] ?? 'Indonesia'));
            $jumlahStok = !empty($row['jumlah_stok']) ? (int)$row['jumlah_stok'] : 1;
            $lokasiRak = trim((string)($row['lokasi_rak'] ?? ''));
            $status = trim((string)($row['status'] ?? 'tersedia'));
            $deskripsi = trim((string)($row['deskripsi'] ?? ''));
            
            // Manual validation
            if (empty($judulBuku)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Judul buku wajib diisi");
                return null;
            }

            // Validate penulis
            if (empty($penulis)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Nama penulis wajib diisi");
                return null;
            }

            // Validate penerbit
            if (empty($penerbit)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Nama penerbit wajib diisi");
                return null;
            }

            // Validate kategori_id
            if (empty($kategoriId) || !\App\Models\KategoriBuku::find($kategoriId)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": ID Kategori tidak valid");
                return null;
            }

            // Validate jenis_id
            if (empty($jenisId) || !\App\Models\JenisBuku::find($jenisId)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": ID Jenis tidak valid");
                return null;
            }

            // Validate sumber_id if provided
            if (!empty($sumberId) && !\App\Models\SumberBuku::find($sumberId)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": ID Sumber tidak valid");
                return null;
            }

            // Validate tahun_terbit if provided
            if (!empty($tahunTerbit) && ($tahunTerbit < 1900 || $tahunTerbit > date('Y') + 1)) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Tahun terbit tidak valid (1900-" . (date('Y') + 1) . ")");
                return null;
            }

            // Validate jumlah_halaman if provided
            if (!empty($jumlahHalaman) && $jumlahHalaman < 1) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Jumlah halaman harus lebih dari 0");
                return null;
            }

            // Validate jumlah_stok
            if ($jumlahStok < 1) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Jumlah stok harus lebih dari 0");
                return null;
            }

            // Validate status
            if (!empty($status) && !in_array($status, ['tersedia', 'tidak_tersedia'])) {
                $this->addUniqueError("Baris " . ($this->imported + 1) . ": Status harus salah satu dari: tersedia, tidak_tersedia");
                return null;
            }

            // Handle barcode
            if (!empty($barcode)) {
                // Check if barcode already exists in database
                if (Buku::where('barcode', $barcode)->exists()) {
                    $this->addUniqueError("Baris " . ($this->imported + 1) . ": Barcode {$barcode} sudah terdaftar di database");
                    return null;
                }

                // Check if barcode already processed in this import session
                if (in_array($barcode, $this->processedBarcodes)) {
                    $this->addUniqueError("Baris " . ($this->imported + 1) . ": Barcode {$barcode} duplikat dalam file import");
                    return null;
                }

                $this->processedBarcodes[] = $barcode;
            } else {
                // Generate barcode if not provided
                try {
                    $barcode = Buku::generateBarcode();
                } catch (\Exception $e) {
                    $this->addUniqueError("Baris " . ($this->imported + 1) . ": Gagal generate barcode: " . $e->getMessage());
                    return null;
                }
            }

            $this->imported = $this->imported + 1;

            return new Buku([
                'judul_buku' => $judulBuku,
                'isbn' => !empty($isbn) ? $isbn : null,
                'barcode' => $barcode,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'kategori_id' => $kategoriId,
                'jenis_id' => $jenisId,
                'sumber_id' => !empty($sumberId) ? $sumberId : null,
                'tahun_terbit' => $tahunTerbit,
                'jumlah_halaman' => $jumlahHalaman,
                'bahasa' => $bahasa,
                'jumlah_stok' => $jumlahStok,
                'stok_tersedia' => $jumlahStok,
                'lokasi_rak' => !empty($lokasiRak) ? $lokasiRak : null,
                'status' => $status,
                'deskripsi' => !empty($deskripsi) ? $deskripsi : null,
            ]);
        } catch (\Exception $e) {
            $this->addUniqueError("Error pada baris " . ($this->imported + 1) . ": " . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'judul_buku' => 'nullable',
            'isbn' => 'nullable',
            'barcode' => 'nullable',
            'penulis' => 'nullable',
            'penerbit' => 'nullable',
            'kategori_id' => 'nullable',
            'jenis_id' => 'nullable',
            'sumber_id' => 'nullable',
            'tahun_terbit' => 'nullable',
            'jumlah_halaman' => 'nullable',
            'bahasa' => 'nullable',
            'jumlah_stok' => 'nullable',
            'lokasi_rak' => 'nullable',
            'status' => 'nullable',
            'deskripsi' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'judul_buku.required' => 'Judul buku wajib diisi',
            'penulis.required' => 'Nama penulis wajib diisi',
            'penerbit.required' => 'Nama penerbit wajib diisi',
            'kategori_id.exists' => 'ID Kategori tidak valid',
            'jenis_id.exists' => 'ID Jenis tidak valid',
            'sumber_id.exists' => 'ID Sumber tidak valid',
            'status.in' => 'Status harus salah satu dari: tersedia, tidak_tersedia',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImportedCount()
    {
        return $this->imported;
    }

    private function addUniqueError($errorMessage)
    {
        if (!in_array($errorMessage, $this->errorTracker)) {
            $this->errors[] = $errorMessage;
            $this->errorTracker[] = $errorMessage;
        }
    }
}
