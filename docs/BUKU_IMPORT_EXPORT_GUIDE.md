# 📚 Panduan Import, Export, dan Template Buku

Dokumentasi lengkap untuk fitur import, export, dan download template data buku dalam sistem perpustakaan.

## 🚀 Fitur yang Tersedia

### 1. **Download Template Import** 📋

-   **URL**: `/admin/buku/download-template`
-   **Method**: `GET`
-   **File Output**: `template_import_buku.xlsx`

### 2. **Export Data Buku** 📤

-   **URL**: `/admin/buku/export`
-   **Method**: `GET`
-   **File Output**: `data_buku_YYYY-MM-DD_HH-mm-ss.xlsx`

### 3. **Import Data Buku** 📥

-   **URL**: `/admin/buku/import`
-   **Method**: `POST`
-   **File Input**: `.xlsx`, `.xls`, `.csv` (max 2MB)

## 📋 Format Template Import

### Kolom yang Tersedia:

| Kolom            | Wajib | Tipe    | Keterangan                       |
| ---------------- | ----- | ------- | -------------------------------- |
| `judul_buku`     | ✅    | String  | Judul buku                       |
| `isbn`           | ❌    | String  | Nomor ISBN (opsional)            |
| `barcode`        | ❌    | String  | Auto-generate jika kosong        |
| `penulis`        | ✅    | String  | Nama penulis                     |
| `penerbit`       | ✅    | String  | Nama penerbit                    |
| `kategori_id`    | ✅    | Integer | ID dari master kategori          |
| `jenis_id`       | ✅    | Integer | ID dari master jenis             |
| `sumber_id`      | ✅    | Integer | ID dari master sumber            |
| `tahun_terbit`   | ❌    | Integer | Tahun terbit (1900 - sekarang+1) |
| `jumlah_halaman` | ❌    | Integer | Jumlah halaman buku              |
| `bahasa`         | ❌    | String  | Bahasa buku (default: Indonesia) |
| `jumlah_stok`    | ✅    | Integer | Jumlah stok buku (min: 1)        |
| `lokasi_rak`     | ❌    | String  | Lokasi rak buku                  |
| `status`         | ❌    | String  | `tersedia` atau `tidak_tersedia` |
| `deskripsi`      | ❌    | String  | Deskripsi buku                   |

### Master Data Reference:

Template akan otomatis menyertakan daftar referensi untuk:

-   ✅ **Kategori Buku** (ID dan nama)
-   ✅ **Jenis Buku** (ID dan nama)
-   ✅ **Sumber Buku** (ID dan nama)

## 🎯 Cara Penggunaan

### 1. Download Template

```bash
# Via UI: Klik tombol "Template" (hijau)
# Via URL: /admin/buku/download-template
```

### 2. Isi Data di Template

1. Gunakan contoh data yang sudah disediakan sebagai referensi
2. Lihat daftar ID master data di bagian bawah template
3. Pastikan `kategori_id`, `jenis_id`, dan `sumber_id` sesuai dengan yang tersedia

### 3. Import Data

```bash
# Via UI:
# 1. Klik tombol "Import" (kuning)
# 2. Pilih file Excel/CSV
# 3. Klik "Import"
```

### 4. Export Data

```bash
# Via UI: Klik tombol "Export" (abu-abu)
# Export akan menggunakan filter yang sedang aktif
```

## ⚙️ Validasi Import

### Validasi Otomatis:

-   ✅ **Wajib**: `judul_buku`, `penulis`, `penerbit`, `kategori_id`, `jenis_id`, `sumber_id`, `jumlah_stok`
-   ✅ **ID Master Data**: Cek keberadaan kategori, jenis, dan sumber
-   ✅ **Barcode Unik**: Tidak boleh duplikat
-   ✅ **Tahun Terbit**: Range 1900 - tahun depan
-   ✅ **Stok**: Minimal 1

### Error Handling:

-   ❌ Baris dengan error akan dilewati
-   📝 Laporan detail error akan ditampilkan
-   ✅ Data valid tetap akan diimpor

## 📊 Contoh Data Template

```excel
| judul_buku | isbn | barcode | penulis | penerbit | kategori_id | jenis_id | sumber_id | tahun_terbit | jumlah_halaman | bahasa | jumlah_stok | lokasi_rak | status | deskripsi |
|------------|------|---------|---------|----------|-------------|----------|-----------|--------------|----------------|--------|-------------|------------|--------|-----------|
| Pemrograman Web dengan Laravel | 978-602-123456-7-8 | BK000001 | John Doe | Penerbit Teknologi | 1 | 1 | 1 | 2024 | 300 | Indonesia | 5 | Rak A-1 | tersedia | Buku panduan lengkap... |
```

## 🛠️ Technical Details

### Files Structure:

```
app/
├── Exports/
│   ├── BukuExport.php           # Export data buku
│   └── BukuTemplateExport.php   # Template import
├── Imports/
│   └── BukuImport.php           # Import logic
└── Http/Controllers/
    └── BukuController.php       # Controller methods
```

### Controller Methods:

```php
// Export data buku dengan filter
public function export(Request $request)

// Download template import
public function downloadTemplate()

// Import data dari file
public function import(Request $request)
```

### Routes:

```php
Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
Route::get('/buku/download-template', [BukuController::class, 'downloadTemplate'])->name('buku.download-template');
Route::post('/buku/import', [BukuController::class, 'import'])->name('buku.import');
```

## 🎨 UI Components

### Tombol-tombol di Admin Panel:

1. **🟢 Template** - Download template import
2. **🟡 Import** - Modal untuk upload file
3. **⚫ Export** - Export data dengan filter aktif

### Modal Import:

-   ✅ File upload dengan validasi extension
-   ✅ Petunjuk penggunaan
-   ✅ Progress indicator saat upload

## 🔧 Troubleshooting

### Error "Route not found":

1. Pastikan routes sudah di-clear: `php artisan route:clear`
2. Cek urutan routes di `routes/web.php`
3. Pastikan routes khusus di atas resource routes

### Error "Class not found":

1. Jalankan: `composer dump-autoload`
2. Pastikan namespace benar di file Export/Import

### Error saat Import:

1. Cek format file (harus .xlsx, .xls, atau .csv)
2. Pastikan ukuran file < 2MB
3. Validasi data sesuai dengan kolom wajib

## 📈 Performance Tips

1. **Batch Import**: File diproses dalam batch 50 records
2. **Memory Efficient**: Menggunakan Laravel Excel untuk optimasi memory
3. **Error Tracking**: Error dikumpulkan dan ditampilkan secara batch

---

✅ **Semua fitur sudah siap digunakan!**

Akses melalui: `/admin/buku` dan gunakan tombol Template, Import, dan Export.
