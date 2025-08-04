# Sistem Perpustakaan SMAN 1 Kefamenanu

Sistem informasi perpustakaan digital untuk SMAN 1 Kefamenanu yang dikembangkan menggunakan Laravel 10 dengan fitur lengkap untuk mengelola data perpustakaan.

## Fitur Utama

### 🎯 Admin Perpustakaan
- **Dashboard Admin** - Overview statistik perpustakaan
- **Manajemen Data Master**:
  - Data Anggota (Siswa, Guru, Staff)
  - Data Buku dengan fitur barcode
  - Data Jurusan dan Kelas
  - Kategori dan Jenis Buku
  - Sumber Buku, Penerbit, Penulis
- **Transaksi**:
  - Peminjaman dan Pengembalian Buku
  - Sistem Denda Otomatis
- **Laporan**:
  - Laporan Anggota
  - Laporan Buku
  - Laporan Kas
- **Cetak**:
  - Kartu Perpustakaan Siswa
  - Label Buku
- **Pengaturan Website** - Konfigurasi dinamis website

### 👨‍🏫 Kepala Sekolah
- Dashboard dengan statistik perpustakaan
- Laporan bulanan peminjaman
- Monitoring kinerja perpustakaan

### 👨‍💼 Petugas Perpustakaan
- Beranda dengan informasi perpustakaan
- Halaman tentang (sejarah, visi, misi sekolah)
- Absensi pengunjung perpustakaan

### 👨‍🎓 Frontend (Siswa/Guru)
- Pencarian buku berdasarkan:
  - Judul buku
  - Penulis
  - ISBN/Barcode
  - Penerbit
- Informasi lokasi buku di rak perpustakaan
- Status ketersediaan buku

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Alpine.js
- **Icons**: Font Awesome
- **Barcode**: Sistem generate otomatis

## Instalasi

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone [repository-url]
cd perpus-smandu-kefamenanu
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpus_smandu
DB_USERNAME=root
DB_PASSWORD=
```

5. **Jalankan Migration & Seeder**
```bash
php artisan migrate:fresh --seed
```

6. **Build Assets**
```bash
npm run build
```

7. **Jalankan Server**
```bash
php artisan serve
```

## Akun Default

Setelah menjalankan seeder, sistem akan memiliki akun default:

### Admin
- Email: `admin@perpustakaan.com`
- Password: `password`

### Kepala Sekolah
- Email: `kepsek@perpustakaan.com`
- Password: `password`

### Petugas
- Email: `petugas@perpustakaan.com`
- Password: `password`

## Struktur Database

### Tabel Utama
- `users` - Pengguna sistem (admin, kepala sekolah, petugas)
- `pengaturan_website` - Konfigurasi website dinamis
- `jurusan` - Data jurusan sekolah
- `kelas` - Data kelas
- `kategori_buku` - Kategori buku
- `jenis_buku` - Jenis buku
- `sumber_buku` - Sumber buku
- `penerbit` - Data penerbit
- `penulis` - Data penulis
- `buku` - Data buku dengan barcode
- `anggota` - Data anggota perpustakaan
- `peminjaman` - Data peminjaman
- `detail_peminjaman` - Detail buku yang dipinjam
- `denda` - Data denda keterlambatan
- `absensi_pengunjung` - Absensi pengunjung perpustakaan

## Fitur Barcode

Sistem ini mendukung:
- **Generate Barcode Otomatis** untuk buku dan anggota
- **Scan Barcode** untuk peminjaman/pengembalian
- **Input Manual** barcode jika diperlukan

## API Endpoints

### Authentication
- `POST /login` - Login user
- `POST /logout` - Logout user

### Admin Routes
- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/pengaturan-website` - Pengaturan website
- `POST /admin/pengaturan-website` - Update pengaturan

### Frontend Routes
- `GET /` - Halaman utama
- `GET /cari-buku` - Pencarian buku
- `GET /tentang` - Halaman tentang

## Kontribusi

1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## Kontak

- **Email**: sman1kefamenanu@gmail.com
- **Telepon**: (0388) 123456
- **Alamat**: Jl. Soekarno-Hatta No. 1, Kefamenanu, Timor Tengah Utara, NTT

## Changelog

### v1.0.0 (2024-01-01)
- Initial release
- Fitur lengkap CRUD untuk semua modul
- Sistem barcode otomatis
- Dashboard untuk semua role
- Frontend pencarian buku
- Pengaturan website dinamis
