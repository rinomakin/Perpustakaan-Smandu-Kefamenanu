# Update Riwayat Pengembalian

## Overview
Telah dilakukan pemisahan dan restrukturisasi untuk fitur riwayat pengembalian. Sebelumnya, riwayat pengembalian ditangani oleh method `history()` di `PengembalianController` dan view `history.blade.php`. Sekarang telah dibuat controller dan view terpisah yang mirip dengan struktur riwayat peminjaman.

## Perubahan yang Dilakukan

### 1. Penghapusan Method History dari PengembalianController
- Method `history()` dihapus dari `app/Http/Controllers/PengembalianController.php`
- File view `resources/views/admin/pengembalian/history.blade.php` dihapus

### 2. Pembuatan Controller Baru
**File:** `app/Http/Controllers/RiwayatPengembalianController.php`

Controller ini memiliki struktur yang mirip dengan `RiwayatPeminjamanController` dengan fitur:
- Method `index()` untuk menampilkan daftar riwayat pengembalian dengan filter
- Method `export()` untuk export data ke CSV
- Filter berdasarkan:
  - Pencarian (nomor pengembalian, nama anggota)
  - Status terlambat (terlambat/tepat waktu)
  - Anggota
  - Buku
  - Tanggal mulai dan akhir
  - Jam mulai dan akhir

### 3. Pembuatan View Baru
**File:** `resources/views/admin/riwayat-pengembalian/index.blade.php`

View ini memiliki fitur:
- Header dengan tombol export dan kembali
- Filter section dengan berbagai opsi filter
- Stats cards menampilkan:
  - Total dikembalikan
  - Total terlambat
  - Total denda
  - Pengembalian hari ini
- Tabel riwayat pengembalian dengan kolom:
  - Nomor pengembalian
  - Anggota (nama, nomor, kelas)
  - Jumlah buku
  - Tanggal dan jam pengembalian
  - Status (terlambat/tepat waktu)
  - Denda
  - Petugas
  - Aksi (detail)

### 4. Update Routes
**File:** `routes/web.php`

#### Routes Admin:
```php
// Riwayat Pengembalian
Route::get('/riwayat-pengembalian', [RiwayatPengembalianController::class, 'index'])->name('riwayat-pengembalian.index');
Route::get('/riwayat-pengembalian/export', [RiwayatPengembalianController::class, 'export'])->name('riwayat-pengembalian.export');
```

#### Routes Kepala Sekolah:
```php
Route::get('/riwayat-pengembalian', [RiwayatPengembalianController::class, 'index'])->name('kepsek.riwayat-pengembalian');
Route::get('/export/riwayat-pengembalian', [RiwayatPengembalianController::class, 'export'])->name('kepsek.export.riwayat-pengembalian');
```

### 5. Update Navigation
**File:** `resources/views/layouts/admin.blade.php`

Menambahkan menu "Riwayat Pengembalian" di sidebar dan mobile menu dengan:
- Icon: `fas fa-undo-alt`
- Route: `riwayat-pengembalian.index`
- Permission: `pengembalian.manage` atau `isAdmin()`

**File:** `resources/views/layouts/kepsek.blade.php`

Menambahkan menu "Riwayat Pengembalian" di navbar dan mobile menu untuk kepala sekolah.

### 6. Update Link di Halaman Lain
- **File:** `resources/views/admin/pengembalian/index.blade.php`
  - Mengubah link "Semua Riwayat" menjadi "Riwayat Pengembalian"
  - Route diubah dari `pengembalian.history` ke `riwayat-pengembalian.index`

- **File:** `resources/views/admin/peminjaman/index.blade.php`
  - Menambahkan link "Riwayat Pengembalian" di samping "Riwayat Peminjaman"

## Fitur yang Tersedia

### 1. Filter dan Pencarian
- Pencarian berdasarkan nomor pengembalian atau nama anggota
- Filter status terlambat (terlambat/tepat waktu)
- Filter berdasarkan anggota
- Filter berdasarkan buku
- Filter tanggal mulai dan akhir
- Filter jam mulai dan akhir

### 2. Export Data
- Export ke format CSV
- Data yang diexport sesuai dengan filter yang diterapkan
- Format file: `riwayat_pengembalian_YYYY-MM-DD_HH-MM-SS.csv`

### 3. Statistik
- Total pengembalian
- Total terlambat
- Total denda
- Pengembalian hari ini

### 4. Tampilan Data
- Tabel responsive dengan pagination
- Status terlambat dengan indikator visual
- Informasi denda dengan format rupiah
- Link ke detail pengembalian

## Permission dan Access Control
- Admin dan Kepala Sekolah dapat mengakses riwayat pengembalian
- Menggunakan middleware `role:ADMIN,KEPALA_SEKOLAH`
- Permission `pengembalian.manage` diperlukan untuk akses

## Struktur Database
Controller menggunakan model:
- `Pengembalian` (model utama)
- `Anggota` (untuk data anggota)
- `Buku` (untuk data buku)
- Relasi: `anggota.kelas`, `user`, `detailPengembalian.buku.kategoriBuku`, `peminjaman.detailPeminjaman.buku`

## Catatan Teknis
- View menggunakan layout `admin.blade.php`
- Styling menggunakan Tailwind CSS
- Icon menggunakan Font Awesome
- Pagination menggunakan Laravel pagination
- Export menggunakan Laravel response stream
- Filter menggunakan query builder dengan eager loading
