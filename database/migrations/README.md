# Database Migrations

## Urutan Migrasi yang Benar

Migrasi dijalankan berdasarkan timestamp. Berikut adalah urutan yang benar:

1. `2014_10_12_000000_create_users_table.php` - Tabel users (tanpa foreign key)
2. `2014_10_12_100000_create_password_reset_tokens_table.php`
3. `2019_08_19_000000_create_failed_jobs_table.php`
4. `2019_12_14_000001_create_personal_access_tokens_table.php`
5. `2024_01_01_000001_create_website_settings_table.php`
6. `2024_01_01_000002_create_roles_table.php` - Tabel peran
7. `2024_01_01_000003_create_jurusan_table.php`
8. `2024_01_01_000003_create_permissions_table.php` - Tabel permissions
9. `2024_01_01_000005_create_kategori_buku_table.php` - Tabel kategori_buku
10. `2024_01_01_000006_create_jenis_buku_table.php` - Tabel jenis_buku
11. `2024_01_01_000007_create_sumber_buku_table.php` - Tabel sumber_buku
12. `2024_01_01_000010_create_buku_table.php` - Tabel buku (tanpa foreign key)
13. `2024_01_01_000014_create_denda_table.php` - Tabel denda (tanpa foreign key)
14. `2025_01_06_000000_create_rak_buku_table.php` - Tabel rak_buku
15. `2025_08_11_101400_create_pengembalian_table.php` - Tabel pengembalian
16. `2024_01_01_000007_fix_foreign_key_constraints.php` - Menambahkan foreign keys

## Troubleshooting

### Error: Foreign key constraint is incorrectly formed

Jika Anda mendapatkan error ini, pastikan:

1. Tabel `peran` sudah dibuat sebelum menambahkan foreign key ke `users`
2. Tabel `kategori_buku`, `jenis_buku`, `sumber_buku`, dan `rak_buku` sudah dibuat sebelum menambahkan foreign key ke `buku`
3. Tabel `peminjaman`, `anggota`, dan `pengembalian` sudah dibuat sebelum menambahkan foreign key ke `denda`
4. Jalankan migrasi dengan urutan yang benar
5. Jika masih error, coba:
    ```bash
    php artisan migrate:reset
    php artisan migrate
    ```

### Struktur Tabel yang Sudah Digabungkan

Beberapa migrasi telah digabungkan untuk mengurangi jumlah file:

-   **absensi_pengunjung**: Semua field sudah termasuk dalam satu migrasi
-   **peminjaman**: Field `jumlah_buku` sudah ditambahkan
-   **detail_peminjaman**: Field `jumlah` sudah ditambahkan
-   **anggota**: Field `jenis_kelamin` sudah ditambahkan
-   **users**: Field `nama_panggilan`, `foto`, dan `peran_id` sudah ditambahkan
-   **buku**: Field `rak_id` sudah ditambahkan
-   **denda**: Field `pengembalian_id` sudah ditambahkan

## Catatan Penting

-   Migrasi `2024_01_01_000007_fix_foreign_key_constraints.php` akan menangani semua foreign key constraints dengan aman
-   Pengecekan tabel dilakukan sebelum menambahkan foreign key untuk menghindari error
-   Jika ada error, migrasi akan skip dan melanjutkan ke migrasi berikutnya
