# Panduan Tabel Pengembalian

## Struktur Tabel

### 1. Tabel `pengembalian`

Tabel ini menyimpan data utama pengembalian buku.

#### Kolom-kolom:
- `id` - Primary key auto increment
- `nomor_pengembalian` - Nomor unik pengembalian (format: KMB-YYYYMMDD-XXXX)
- `peminjaman_id` - Foreign key ke tabel peminjaman
- `anggota_id` - Foreign key ke tabel anggota
- `user_id` - Foreign key ke tabel users (petugas yang memproses)
- `tanggal_pengembalian` - Tanggal pengembalian
- `jam_pengembalian` - Jam pengembalian (nullable)
- `jumlah_hari_terlambat` - Jumlah hari keterlambatan (default: 0)
- `total_denda` - Total denda yang harus dibayar (default: 0)
- `status_denda` - Status pembayaran denda (tidak_ada/belum_dibayar/sudah_dibayar)
- `tanggal_pembayaran_denda` - Tanggal pembayaran denda (nullable)
- `catatan` - Catatan tambahan (nullable)
- `status` - Status pengembalian (diproses/selesai/dibatalkan)
- `created_at` - Timestamp pembuatan record
- `updated_at` - Timestamp update terakhir

#### Index:
- `nomor_pengembalian` - Untuk pencarian cepat berdasarkan nomor
- `tanggal_pengembalian` - Untuk pencarian berdasarkan tanggal

### 2. Tabel `detail_pengembalian`

Tabel ini menyimpan detail buku yang dikembalikan.

#### Kolom-kolom:
- `id` - Primary key auto increment
- `pengembalian_id` - Foreign key ke tabel pengembalian
- `buku_id` - Foreign key ke tabel buku
- `detail_peminjaman_id` - Foreign key ke tabel detail_peminjaman
- `kondisi_kembali` - Kondisi buku saat dikembalikan (baik/sedikit_rusak/rusak/hilang)
- `jumlah_dikembalikan` - Jumlah buku yang dikembalikan (default: 1)
- `denda_buku` - Denda khusus untuk buku ini (default: 0)
- `catatan_buku` - Catatan khusus untuk buku ini (nullable)
- `created_at` - Timestamp pembuatan record
- `updated_at` - Timestamp update terakhir

#### Index:
- `pengembalian_id, buku_id` - Composite index untuk optimasi query

## Relasi Antar Tabel

### Pengembalian
- `belongsTo` Peminjaman
- `belongsTo` Anggota
- `belongsTo` User (Petugas)
- `hasMany` DetailPengembalian
- `hasMany` Denda (melalui peminjaman_id)

### DetailPengembalian
- `belongsTo` Pengembalian
- `belongsTo` Buku
- `belongsTo` DetailPeminjaman

### Peminjaman
- `hasMany` Pengembalian

## Model Methods

### Pengembalian Model

#### Static Methods:
- `generateNomorPengembalian()` - Generate nomor pengembalian otomatis

#### Instance Methods:
- `hasDenda()` - Cek apakah ada denda
- `isDendaPaid()` - Cek apakah denda sudah dibayar
- `isCompleted()` - Cek apakah pengembalian selesai
- `isCancelled()` - Cek apakah pengembalian dibatalkan

### DetailPengembalian Model

#### Instance Methods:
- `isBookDamaged()` - Cek apakah buku rusak
- `isBookLost()` - Cek apakah buku hilang
- `isBookGood()` - Cek apakah buku dalam kondisi baik
- `hasDenda()` - Cek apakah ada denda untuk buku ini
- `getKondisiLabel()` - Dapatkan label kondisi buku
- `getKondisiClass()` - Dapatkan class CSS untuk styling

## Contoh Penggunaan

### Membuat Pengembalian Baru
```php
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
    'status' => 'diproses'
]);
```

### Menambah Detail Pengembalian
```php
DetailPengembalian::create([
    'pengembalian_id' => $pengembalian->id,
    'buku_id' => $buku->id,
    'detail_peminjaman_id' => $detailPeminjaman->id,
    'kondisi_kembali' => 'baik',
    'jumlah_dikembalikan' => 1,
    'denda_buku' => 0,
    'catatan_buku' => null
]);
```

### Query Pengembalian dengan Relasi
```php
$pengembalian = Pengembalian::with(['anggota', 'user', 'detailPengembalian.buku'])
    ->where('status', 'selesai')
    ->get();
```

## Status dan Enum Values

### Status Pengembalian:
- `diproses` - Pengembalian sedang diproses
- `selesai` - Pengembalian sudah selesai
- `dibatalkan` - Pengembalian dibatalkan

### Status Denda:
- `tidak_ada` - Tidak ada denda
- `belum_dibayar` - Denda belum dibayar
- `sudah_dibayar` - Denda sudah dibayar

### Kondisi Kembali:
- `baik` - Buku dalam kondisi baik
- `sedikit_rusak` - Buku sedikit rusak
- `rusak` - Buku rusak
- `hilang` - Buku hilang
