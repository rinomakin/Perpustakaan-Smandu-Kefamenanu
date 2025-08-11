# Panduan Integrasi Tabel Pengembalian

## Overview

Dokumen ini menjelaskan bagaimana tabel pengembalian yang baru dibuat telah terintegrasi dengan menu pengembalian yang sudah ada di sistem perpustakaan.

## Yang Telah Diintegrasikan

### 1. Controller Updates

#### PengembalianController.php
- ✅ **Import Models**: Menambahkan import untuk `Pengembalian` dan `DetailPengembalian`
- ✅ **Method Index**: Mengubah query untuk menggunakan tabel `pengembalian` 
- ✅ **Method History**: Mengubah query untuk menggunakan tabel `pengembalian`
- ✅ **Method Store**: Mengubah logika untuk menyimpan ke tabel `pengembalian` dan `detail_pengembalian`
- ✅ **Method Show**: Menambahkan method baru untuk menampilkan detail pengembalian

#### Perubahan Utama di Method Store:
```php
// Sebelum: Hanya update tabel peminjaman
$peminjaman->update(['status' => 'dikembalikan']);

// Sesudah: Buat record di tabel pengembalian
$pengembalian = Pengembalian::create([
    'nomor_pengembalian' => Pengembalian::generateNomorPengembalian(),
    'peminjaman_id' => $peminjaman->id,
    'anggota_id' => $peminjaman->anggota_id,
    'user_id' => auth()->id(),
    'tanggal_pengembalian' => $tanggalKembali,
    'jam_pengembalian' => $request->jam_kembali ?? now()->format('H:i'),
    'jumlah_hari_terlambat' => $daysLate,
    'total_denda' => $totalDenda,
    'status_denda' => $totalDenda > 0 ? 'belum_dibayar' : 'tidak_ada',
    'catatan' => $request->catatan_pengembalian,
    'status' => 'selesai'
]);

// Buat detail pengembalian untuk setiap buku
DetailPengembalian::create([
    'pengembalian_id' => $pengembalian->id,
    'buku_id' => $detail->buku_id,
    'detail_peminjaman_id' => $detail->id,
    'kondisi_kembali' => $kondisi,
    'jumlah_dikembalikan' => $detail->jumlah ?? 1,
    'denda_buku' => $dendaBuku,
    'catatan_buku' => $this->getCatatanBuku($kondisi)
]);
```

### 2. View Updates

#### resources/views/admin/pengembalian/index.blade.php
- ✅ **Stats Cards**: Mengubah perhitungan statistik untuk menggunakan field dari tabel pengembalian
- ✅ **Table Headers**: Mengubah header tabel untuk menampilkan informasi pengembalian
- ✅ **Table Data**: Mengubah data yang ditampilkan dari tabel peminjaman ke tabel pengembalian
- ✅ **Status Display**: Mengubah logika status untuk menggunakan field `status` dan `jumlah_hari_terlambat`

#### resources/views/admin/pengembalian/history.blade.php
- ✅ **Stats Cards**: Mengubah perhitungan statistik untuk menggunakan field dari tabel pengembalian
- ✅ **Table Structure**: Mengubah struktur tabel untuk menampilkan data pengembalian
- ✅ **Data Display**: Mengubah data yang ditampilkan dari tabel peminjaman ke tabel pengembalian

#### resources/views/admin/pengembalian/show.blade.php (Baru)
- ✅ **Detail View**: Membuat view baru untuk menampilkan detail pengembalian
- ✅ **Informasi Lengkap**: Menampilkan informasi pengembalian, anggota, buku, dan denda
- ✅ **Responsive Design**: Menggunakan grid layout yang responsif
- ✅ **Print Functionality**: Menambahkan tombol cetak

### 3. Data Flow

#### Sebelum Integrasi:
```
Peminjaman (status: dipinjam) → Update status menjadi 'dikembalikan'
```

#### Sesudah Integrasi:
```
Peminjaman (status: dipinjam) → 
├── Buat record di tabel pengembalian
├── Buat record di tabel detail_pengembalian
├── Update status peminjaman menjadi 'dikembalikan'
└── Buat record denda (jika terlambat)
```

### 4. Field Mapping

#### Tabel Peminjaman → Tabel Pengembalian:
| Peminjaman | Pengembalian | Keterangan |
|------------|--------------|------------|
| `nomor_peminjaman` | `nomor_pengembalian` | Generate otomatis dengan format KMB-YYYYMMDD-XXXX |
| `tanggal_kembali` | `tanggal_pengembalian` | Tanggal pengembalian |
| `jam_kembali` | `jam_pengembalian` | Jam pengembalian |
| `status` | `status` | Status pengembalian (diproses/selesai/dibatalkan) |
| - | `jumlah_hari_terlambat` | Jumlah hari keterlambatan |
| - | `total_denda` | Total denda yang harus dibayar |
| - | `status_denda` | Status pembayaran denda |

#### Detail Peminjaman → Detail Pengembalian:
| Detail Peminjaman | Detail Pengembalian | Keterangan |
|-------------------|---------------------|------------|
| `kondisi_kembali` | `kondisi_kembali` | Kondisi buku saat dikembalikan |
| - | `denda_buku` | Denda khusus untuk buku ini |
| - | `catatan_buku` | Catatan khusus untuk buku ini |

### 5. Fitur Baru yang Ditambahkan

#### 1. Nomor Pengembalian Otomatis
- Format: `KMB-YYYYMMDD-XXXX`
- Generate otomatis saat proses pengembalian

#### 2. Perhitungan Denda Otomatis
- Denda keterlambatan: Rp 1.000/hari
- Denda buku rusak: Rp 5.000
- Denda buku rusak parah: Rp 25.000
- Denda buku hilang: Rp 100.000

#### 3. Tracking Kondisi Buku
- Baik
- Sedikit Rusak
- Rusak
- Hilang

#### 4. Status Pengembalian
- Diproses
- Selesai
- Dibatalkan

#### 5. Status Denda
- Tidak Ada
- Belum Dibayar
- Sudah Dibayar

### 6. Routes yang Digunakan

```php
// Routes yang sudah ada dan masih berfungsi
Route::resource('pengembalian', \App\Http\Controllers\PengembalianController::class);
Route::get('/pengembalian/search-anggota', [PengembalianController::class, 'searchAnggota']);
Route::get('/pengembalian/get-peminjaman-aktif', [PengembalianController::class, 'getPeminjamanAktif']);
Route::post('/pengembalian/scan-barcode', [PengembalianController::class, 'scanBarcode']);
Route::post('/pengembalian/scan-barcode-anggota', [PengembalianController::class, 'scanBarcodeAnggota']);
Route::get('/pengembalian/history', [PengembalianController::class, 'history']);

// Route baru yang ditambahkan
Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');
```

### 7. Menu Integration

Menu pengembalian di sidebar sudah terintegrasi dan akan menampilkan:
- **Data Pengembalian Hari Ini**: Menampilkan pengembalian hari ini
- **Riwayat Pengembalian**: Menampilkan semua riwayat pengembalian
- **Proses Pengembalian**: Form untuk memproses pengembalian baru

### 8. Dashboard Integration

Dashboard akan menampilkan statistik pengembalian yang diambil dari tabel pengembalian yang baru.

## Cara Testing

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Jalankan Seeder (Opsional)
```bash
php artisan db:seed --class=PengembalianSeeder
```

### 3. Test Menu Pengembalian
1. Buka menu "Pengembalian" di sidebar
2. Klik "Proses Pengembalian" untuk membuat pengembalian baru
3. Cari anggota yang memiliki peminjaman aktif
4. Proses pengembalian buku
5. Lihat data di halaman "Data Pengembalian Hari Ini"
6. Lihat riwayat di halaman "Riwayat Pengembalian"
7. Klik detail untuk melihat informasi lengkap

### 4. Verifikasi Data
- Cek tabel `pengembalian` untuk data pengembalian
- Cek tabel `detail_pengembalian` untuk detail buku yang dikembalikan
- Cek tabel `peminjaman` untuk status yang diupdate
- Cek tabel `denda` untuk denda yang dibuat (jika ada)

## Keuntungan Integrasi

1. **Data Terpisah**: Data pengembalian terpisah dari data peminjaman
2. **Tracking Lebih Detail**: Bisa melacak kondisi buku per item
3. **Denda Otomatis**: Perhitungan denda otomatis berdasarkan kondisi
4. **Nomor Unik**: Setiap pengembalian memiliki nomor unik
5. **Riwayat Lengkap**: Riwayat pengembalian tersimpan dengan baik
6. **Backward Compatibility**: Menu yang sudah ada tetap berfungsi

## Catatan Penting

- Tabel pengembalian terhubung dengan tabel peminjaman yang sudah ada
- Data lama tetap bisa diakses melalui menu yang sudah ada
- Sistem denda otomatis berdasarkan hari keterlambatan dan kondisi buku
- Nomor pengembalian generate otomatis dengan format yang konsisten
- Relasi antar tabel sudah diatur dengan foreign key constraints
