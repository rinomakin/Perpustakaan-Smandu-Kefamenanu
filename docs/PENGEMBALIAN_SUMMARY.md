# Summary Implementasi Tabel Pengembalian

## Yang Telah Dibuat

### 1. Migration Files

-   ✅ `2025_08_11_101400_create_pengembalian_table.php` - Tabel utama pengembalian
-   ✅ `2025_08_11_102000_create_detail_pengembalian_table.php` - Tabel detail pengembalian

### 2. Model Files

-   ✅ `app/Models/Pengembalian.php` - Model dengan relasi dan methods
-   ✅ `app/Models/DetailPengembalian.php` - Model detail dengan helper methods

### 3. Seeder

-   ✅ `database/seeders/PengembalianSeeder.php` - Seeder untuk data contoh
-   ✅ Ditambahkan ke `DatabaseSeeder.php`

### 4. Dokumentasi

-   ✅ `docs/PENGEMBALIAN_TABLE_GUIDE.md` - Panduan lengkap tabel pengembalian

## Struktur Database

### Tabel `pengembalian`

```sql
CREATE TABLE pengembalian (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_pengembalian VARCHAR(255) UNIQUE,
    peminjaman_id BIGINT UNSIGNED,
    anggota_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    tanggal_pengembalian DATE,
    jam_pengembalian TIME NULL,
    jumlah_hari_terlambat INT DEFAULT 0,
    total_denda DECIMAL(10,2) DEFAULT 0,
    status_denda ENUM('tidak_ada','belum_dibayar','sudah_dibayar') DEFAULT 'tidak_ada',
    tanggal_pembayaran_denda DATE NULL,
    catatan TEXT NULL,
    status ENUM('diproses','selesai','dibatalkan') DEFAULT 'diproses',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_nomor_pengembalian (nomor_pengembalian),
    INDEX idx_tanggal_pengembalian (tanggal_pengembalian),
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel `detail_pengembalian`

```sql
CREATE TABLE detail_pengembalian (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengembalian_id BIGINT UNSIGNED,
    buku_id BIGINT UNSIGNED,
    detail_peminjaman_id BIGINT UNSIGNED,
    kondisi_kembali ENUM('baik','sedikit_rusak','rusak','hilang') DEFAULT 'baik',
    jumlah_dikembalikan INT DEFAULT 1,
    denda_buku DECIMAL(10,2) DEFAULT 0,
    catatan_buku TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_pengembalian_buku (pengembalian_id, buku_id),
    FOREIGN KEY (pengembalian_id) REFERENCES pengembalian(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE,
    FOREIGN KEY (detail_peminjaman_id) REFERENCES detail_peminjaman(id) ON DELETE CASCADE
);
```

## Fitur Utama

### 1. Nomor Pengembalian Otomatis

-   Format: `KMB-YYYYMMDD-XXXX`
-   Generate otomatis dengan method `Pengembalian::generateNomorPengembalian()`

### 2. Perhitungan Denda Otomatis

-   Denda keterlambatan: Rp 1.000/hari
-   Denda buku rusak: Rp 5.000
-   Denda buku rusak parah: Rp 25.000
-   Denda buku hilang: Rp 100.000

### 3. Tracking Kondisi Buku

-   Baik
-   Sedikit Rusak
-   Rusak
-   Hilang

### 4. Status Pengembalian

-   Diproses
-   Selesai
-   Dibatalkan

### 5. Status Denda

-   Tidak Ada
-   Belum Dibayar
-   Sudah Dibayar

## Relasi Antar Tabel

```
peminjaman (1) ←→ (N) pengembalian
pengembalian (1) ←→ (N) detail_pengembalian
detail_pengembalian (N) ←→ (1) buku
detail_pengembalian (N) ←→ (1) detail_peminjaman
pengembalian (N) ←→ (1) anggota
pengembalian (N) ←→ (1) user (petugas)
```

## Cara Menjalankan

### 1. Migration

```bash
php artisan migrate
```

### 2. Seeder (Opsional)

```bash
php artisan db:seed --class=PengembalianSeeder
```

### 3. Atau jalankan semua seeder

```bash
php artisan db:seed
```

## Contoh Penggunaan dalam Controller

```php
// Membuat pengembalian baru
public function store(Request $request)
{
    $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

    // Hitung denda
    $hariTerlambat = max(0, now()->diffInDays($peminjaman->tanggal_harus_kembali, false));
    $totalDenda = $hariTerlambat * 1000; // Rp 1000 per hari

    $pengembalian = Pengembalian::create([
        'nomor_pengembalian' => Pengembalian::generateNomorPengembalian(),
        'peminjaman_id' => $peminjaman->id,
        'anggota_id' => $peminjaman->anggota_id,
        'user_id' => auth()->id(),
        'tanggal_pengembalian' => now(),
        'jam_pengembalian' => now(),
        'jumlah_hari_terlambat' => $hariTerlambat,
        'total_denda' => $totalDenda,
        'status_denda' => $totalDenda > 0 ? 'belum_dibayar' : 'tidak_ada',
        'status' => 'selesai'
    ]);

    // Update status peminjaman
    $peminjaman->update(['status' => 'dikembalikan']);

    return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diproses');
}
```

## Next Steps

Untuk melengkapi implementasi, Anda mungkin perlu:

1. **Controller** - Buat `PengembalianController` untuk CRUD operations
2. **Views** - Buat view untuk list, create, edit, dan show pengembalian
3. **Routes** - Tambahkan routes untuk pengembalian
4. **Validation** - Buat Request classes untuk validasi
5. **Export/Import** - Buat fitur export data pengembalian
6. **Reports** - Buat laporan pengembalian

## Catatan Penting

-   Tabel pengembalian terhubung dengan tabel peminjaman yang sudah ada
-   Sistem denda otomatis berdasarkan hari keterlambatan dan kondisi buku
-   Nomor pengembalian generate otomatis dengan format yang konsisten
-   Relasi antar tabel sudah diatur dengan foreign key constraints
-   Seeder tersedia untuk testing dan development
