# Sistem Perpustakaan SMAN 1 Kefamenanu

Sistem informasi perpustakaan digital untuk SMAN 2 Kefamenanu yang dikembangkan menggunakan Laravel 10 dengan fitur lengkap untuk mengelola data perpustakaan. sistem ini masih dalam tahap pengembangan

## Teknologi yang Digunakan

-   **Backend**: Laravel 10
-   **Database**: MySQL
-   **Frontend**: Tailwind CSS, Alpine.js
-   **Icons**: Font Awesome
-   **Barcode**: Sistem generate otomatis

## Instalasi

### Prerequisites

-   PHP 8.1+
-   Composer
-   MySQL 8.0+
-   Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**

```bash
git clone https://github.com/rinomakin/Perpustakaan-Smandu-Kefamenanu.git
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

### Admin

-   Email: `admin@perpustakaan.com`
-   Password: `password`

### Kepala Sekolah

-   Email: `kepsek@perpustakaan.com`
-   Password: `password`

### Petugas

-   Email: `petugas@perpustakaan.com`
-   Password: `password`
