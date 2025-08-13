# Sistem Denda Perpustakaan

## Overview

Sistem denda perpustakaan adalah fitur untuk mengelola denda keterlambatan pengembalian buku. Sistem ini memungkinkan admin untuk mencatat, mengelola, dan melacak denda yang dikenakan kepada anggota yang terlambat mengembalikan buku.

## Fitur Utama

### 1. Manajemen Denda

-   **Tambah Denda**: Menambahkan denda baru untuk peminjaman yang terlambat
-   **Edit Denda**: Mengubah informasi denda yang sudah ada
-   **Hapus Denda**: Menghapus data denda
-   **Lihat Detail**: Melihat detail lengkap denda

### 2. Status Pembayaran

-   **Belum Dibayar**: Denda yang belum dibayar oleh anggota
-   **Sudah Dibayar**: Denda yang sudah dibayar dengan tanggal pembayaran

### 3. Statistik Denda

-   Total denda keseluruhan
-   Denda yang belum dibayar
-   Denda yang sudah dibayar
-   Denda hari ini

## Struktur Database

### Tabel `denda`

```sql
CREATE TABLE denda (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    peminjaman_id BIGINT NOT NULL,
    anggota_id BIGINT NOT NULL,
    jumlah_hari_terlambat INT NOT NULL,
    jumlah_denda DECIMAL(10,2) NOT NULL,
    status_pembayaran ENUM('belum_dibayar', 'sudah_dibayar') DEFAULT 'belum_dibayar',
    tanggal_pembayaran DATE NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE
);
```

## File yang Dibuat/Dimodifikasi

### 1. Controller

-   **`app/Http/Controllers/DendaController.php`**: Controller utama untuk mengelola denda

### 2. Views

-   **`resources/views/admin/denda/index.blade.php`**: Halaman daftar denda
-   **`resources/views/admin/denda/create.blade.php`**: Form tambah denda
-   **`resources/views/admin/denda/show.blade.php`**: Detail denda
-   **`resources/views/admin/denda/edit.blade.php`**: Form edit denda

### 3. Routes

-   **`routes/web.php`**: Route untuk CRUD denda

### 4. Permissions

-   **`database/seeders/PermissionSeeder.php`**: Permission untuk akses denda

## Cara Penggunaan

### 1. Akses Menu Denda

1. Login sebagai admin
2. Pilih menu "Transaksi" di sidebar
3. Klik "Denda" dari dropdown menu

### 2. Menambah Denda Baru

1. Klik tombol "Tambah Denda"
2. Pilih peminjaman yang terlambat dari dropdown
3. Masukkan jumlah hari terlambat
4. Masukkan jumlah denda (dalam Rupiah)
5. Pilih status pembayaran
6. Jika sudah dibayar, masukkan tanggal pembayaran
7. Tambahkan catatan (opsional)
8. Klik "Simpan Denda"

### 3. Mengedit Denda

1. Dari daftar denda, klik ikon edit (✏️)
2. Ubah informasi yang diperlukan
3. Klik "Update Denda"

### 4. Melihat Detail Denda

1. Dari daftar denda, klik ikon detail (👁️)
2. Informasi lengkap akan ditampilkan termasuk:
    - Data denda
    - Informasi anggota
    - Informasi peminjaman
    - Detail buku yang dipinjam

### 5. Menghapus Denda

1. Dari daftar denda, klik ikon hapus (🗑️)
2. Konfirmasi penghapusan

## Permission yang Dibutuhkan

### Permission Denda

-   `denda.manage`: Kelola denda (full access)
-   `denda.view`: Lihat daftar denda
-   `denda.create`: Tambah denda baru
-   `denda.update`: Edit denda
-   `denda.delete`: Hapus denda

## Integrasi dengan Sistem Lain

### 1. Peminjaman

-   Denda terkait dengan peminjaman yang terlambat
-   Sistem hanya menampilkan peminjaman dengan status 'dipinjam' dan tanggal harus kembali sudah lewat

### 2. Anggota

-   Denda terkait dengan anggota yang melakukan peminjaman
-   Informasi anggota ditampilkan di detail denda

### 3. Laporan

-   Data denda dapat dilihat di laporan kas
-   Laporan denda tersedia di menu laporan

## Validasi

### 1. Form Validation

-   `peminjaman_id`: Harus ada dan valid
-   `jumlah_hari_terlambat`: Minimal 1 hari
-   `jumlah_denda`: Minimal 0, step 100
-   `status_pembayaran`: Harus 'belum_dibayar' atau 'sudah_dibayar'
-   `tanggal_pembayaran`: Wajib jika status 'sudah_dibayar'

### 2. Business Logic

-   Tidak boleh ada denda ganda untuk peminjaman yang sama
-   Hanya peminjaman terlambat yang bisa ditambahkan denda

## Statistik dan Dashboard

### 1. Card Statistik

-   **Total Denda**: Jumlah total denda keseluruhan
-   **Belum Dibayar**: Jumlah denda yang belum dibayar
-   **Sudah Dibayar**: Jumlah denda yang sudah dibayar
-   **Hari Ini**: Jumlah denda yang dibuat hari ini

### 2. Tabel Denda

-   Menampilkan daftar denda dengan informasi:
    -   Foto dan nama anggota
    -   ID peminjaman
    -   Jumlah hari terlambat
    -   Jumlah denda
    -   Status pembayaran
    -   Tanggal dibuat
    -   Aksi (detail, edit, hapus)

## Styling dan UI/UX

### 1. Design System

-   Menggunakan Tailwind CSS
-   Warna tema: Merah-Pink gradient untuk header
-   Icon: Font Awesome
-   Responsive design

### 2. User Experience

-   Form yang intuitif dengan validasi real-time
-   Informasi peminjaman terpilih ditampilkan secara dinamis
-   Status pembayaran dengan visual indicator
-   Konfirmasi untuk aksi penting (hapus)

## Troubleshooting

### 1. Masalah Umum

-   **Denda tidak muncul**: Pastikan ada peminjaman terlambat
-   **Permission denied**: Pastikan user memiliki permission denda.manage
-   **Form validation error**: Periksa semua field required

### 2. Debug

-   Cek log Laravel untuk error detail
-   Periksa database connection
-   Pastikan migration sudah dijalankan

## Maintenance

### 1. Backup Data

-   Backup tabel `denda` secara berkala
-   Backup relasi dengan tabel `peminjaman` dan `anggota`

### 2. Monitoring

-   Monitor jumlah denda yang belum dibayar
-   Track trend denda per periode
-   Periksa data integrity secara berkala

## Future Enhancement

### 1. Fitur yang Bisa Ditambahkan

-   Notifikasi otomatis untuk denda yang belum dibayar
-   Export data denda ke Excel/PDF
-   Perhitungan denda otomatis berdasarkan kebijakan
-   Integrasi dengan sistem pembayaran
-   Dashboard khusus denda dengan chart

### 2. Optimisasi

-   Pagination untuk data besar
-   Search dan filter advanced
-   Bulk operations (bulk update status)
-   API untuk integrasi mobile app

## Kesimpulan

Sistem denda perpustakaan telah berhasil diimplementasikan dengan fitur lengkap untuk mengelola denda keterlambatan. Sistem ini terintegrasi dengan baik dengan modul peminjaman dan anggota, serta menyediakan interface yang user-friendly untuk admin perpustakaan.
