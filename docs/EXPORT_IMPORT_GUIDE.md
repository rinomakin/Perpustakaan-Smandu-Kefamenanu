# Panduan Export dan Import Data Anggota

## Fitur Export Data

### Deskripsi
Fitur export memungkinkan admin untuk mengekspor data anggota ke dalam format Excel (.xlsx) dengan berbagai filter yang tersedia.

### Cara Menggunakan
1. Buka halaman **Data Anggota** di admin panel
2. Gunakan filter yang tersedia (pencarian, kelas, jurusan, jenis anggota, status)
3. Klik tombol **"Export Data"** (ikon download)
4. File Excel akan otomatis terdownload dengan nama `anggota_YYYY-MM-DD_HH-MM-SS.xlsx`

### Data yang Diekspor
- Nomor Anggota
- Barcode
- Nama Lengkap
- NIK
- Alamat
- Nomor Telepon
- Email
- Kelas
- Jurusan
- Jabatan
- Jenis Anggota
- Status
- Tanggal Bergabung
- Tanggal Dibuat

### Filter yang Didukung
- **Pencarian**: Nama, NIK, Nomor Anggota, Barcode, Email
- **Kelas**: Filter berdasarkan kelas tertentu
- **Jurusan**: Filter berdasarkan jurusan tertentu
- **Jenis Anggota**: Siswa, Guru, Staff
- **Status**: Aktif, Nonaktif, Ditangguhkan

## Fitur Import Data

### Deskripsi
Fitur import memungkinkan admin untuk mengimpor data anggota secara massal dari file Excel atau CSV.

### Format File yang Didukung
- Excel (.xlsx, .xls)
- CSV (.csv)

### Cara Menggunakan
1. Buka halaman **Data Anggota** di admin panel
2. Klik tombol **"Download Template"** untuk mendapatkan template Excel
3. Isi template dengan data anggota yang akan diimpor
4. Klik tombol **"Import Data"** (ikon upload)
5. Pilih file Excel/CSV yang sudah diisi
6. Klik **"Import"** untuk memulai proses import

### Template Import
Template Excel berisi:
- Header dengan nama kolom yang benar
- Contoh data untuk referensi
- Daftar kelas beserta ID untuk referensi

### Kolom yang Wajib Diisi
- `nama_lengkap` (Wajib)
- `nik` (Wajib, harus unik)

### Kolom Opsional
- `alamat`
- `nomor_telepon`
- `email`
- `kelas_id` (ID kelas dari daftar kelas)
- `jabatan`
- `jenis_anggota` (siswa/guru/staff, default: siswa)
- `status` (aktif/nonaktif/ditangguhkan, default: aktif)
- `tanggal_bergabung` (format: YYYY-MM-DD, default: hari ini)

### Validasi Import
- NIK harus unik (tidak boleh duplikat)
- Kelas ID harus valid (ada di database)
- Format tanggal harus benar
- Jenis anggota dan status harus sesuai enum

### Auto-Generate
Saat import, sistem akan otomatis generate:
- **Nomor Anggota**: Format AGT + 6 digit angka (contoh: AGT000001)
- **Barcode**: Format BC + 8 digit angka (contoh: BC00000001)

## Error Handling

### Error yang Mungkin Terjadi
1. **NIK Duplikat**: NIK sudah terdaftar di database
2. **Kelas ID Invalid**: ID kelas tidak ditemukan
3. **Format Tanggal Salah**: Format tanggal tidak sesuai
4. **Jenis Anggota Invalid**: Nilai tidak sesuai enum
5. **Status Invalid**: Nilai tidak sesuai enum

### Pesan Error
- Error akan ditampilkan dalam pesan sukses dengan detail
- Maksimal 5 error pertama akan ditampilkan
- Jika lebih dari 5 error, akan ditampilkan jumlah error lainnya

## Tips Penggunaan

### Untuk Export
1. Gunakan filter untuk mendapatkan data yang spesifik
2. Export data yang sudah difilter untuk analisis yang lebih mudah
3. File Excel yang dihasilkan sudah berformat dengan baik

### Untuk Import
1. Selalu download template terbaru sebelum import
2. Periksa daftar kelas di template untuk referensi ID kelas
3. Pastikan NIK unik untuk setiap anggota
4. Gunakan format tanggal YYYY-MM-DD
5. Test import dengan data kecil terlebih dahulu

### Best Practices
1. **Backup Data**: Selalu backup data sebelum import massal
2. **Validasi Data**: Periksa data sebelum import
3. **Test Import**: Test dengan data kecil sebelum import besar
4. **Monitoring**: Periksa hasil import dan error yang terjadi

## Teknis Implementasi

### Package yang Digunakan
- `maatwebsite/excel`: Untuk handling Excel/CSV
- `phpoffice/phpspreadsheet`: Untuk styling Excel

### Class yang Terlibat
- `AnggotaController`: Controller utama
- `AnggotaExport`: Class untuk export data
- `AnggotaTemplateExport`: Class untuk template
- `AnggotaImport`: Class untuk import data

### Performance
- Import menggunakan batch processing (100 record per batch)
- Chunk reading untuk file besar
- Error handling yang robust
- Memory efficient untuk file besar

## Troubleshooting

### Masalah Umum
1. **File tidak terupload**: Periksa ukuran file (max 2MB)
2. **Format file salah**: Pastikan file Excel/CSV
3. **Data tidak terimport**: Periksa format data sesuai template
4. **Error validasi**: Periksa data sesuai aturan validasi

### Solusi
1. Gunakan template yang disediakan
2. Periksa format data sebelum import
3. Pastikan semua field wajib terisi
4. Periksa log error untuk detail masalah 