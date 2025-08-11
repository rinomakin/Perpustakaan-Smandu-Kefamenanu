# Panduan Permission Sistem Pengembalian

## Masalah 404 pada Riwayat Pengembalian - Solusi Permission

### 1. **Permission yang Diperlukan**

Untuk mengakses sistem pengembalian, user harus memiliki permission:

-   `pengembalian.manage` - untuk semua fitur pengembalian

### 2. **Perbedaan Permission Peminjaman vs Pengembalian**

**Peminjaman:**

-   Permission: `peminjaman.manage`
-   Controller: `PeminjamanController`
-   Routes: `/admin/peminjaman/*`

**Pengembalian:**

-   Permission: `pengembalian.manage`
-   Controller: `PengembalianController`
-   Routes: `/admin/pengembalian/*`

### 3. **Middleware yang Digunakan**

**PengembalianController:**

```php
public function __construct()
{
    $this->middleware(['auth']);
    $this->middleware('permission:pengembalian.manage')->only(['index', 'create', 'store', 'show', 'history']);
    $this->middleware('permission:pengembalian.manage')->only(['searchAnggota', 'getPeminjamanAktif', 'scanBarcode', 'scanBarcodeAnggota']);
}
```

### 4. **Cara Mengecek Permission User**

**Test Route:**

```
/admin/pengembalian/test-permission
```

**Response yang diharapkan:**

```json
{
    "user_id": 1,
    "user_name": "Admin",
    "has_pengembalian_permission": true,
    "has_peminjaman_permission": true,
    "is_admin": true,
    "role": "Administrator",
    "permissions": ["pengembalian.manage", "peminjaman.manage", ...]
}
```

### 5. **Langkah Troubleshooting**

#### A. Cek Permission User

1. Login sebagai user yang bermasalah
2. Akses: `/admin/pengembalian/test-permission`
3. Periksa apakah `has_pengembalian_permission: true`

#### B. Jika Permission False

1. Periksa role user di database
2. Periksa apakah permission `pengembalian.manage` sudah di-assign ke role
3. Jalankan seeder permission jika belum

#### C. Assign Permission Manual

```sql
-- Cek permission ID
SELECT id, name, slug FROM permissions WHERE slug = 'pengembalian.manage';

-- Cek role ID
SELECT id, nama_peran FROM roles WHERE kode_peran = 'ADMIN';

-- Assign permission ke role
INSERT INTO role_permissions (role_id, permission_id) VALUES (role_id, permission_id);
```

### 6. **Seeder Permission**

Permission sudah didefinisikan di `PermissionSeeder.php`:

```php
[
    'name' => 'Kelola Pengembalian',
    'slug' => 'pengembalian.manage',
    'description' => 'Dapat mengelola pengembalian buku',
    'group_name' => 'Transaksi'
]
```

### 7. **Jalankan Seeder**

```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=AssignPermissionsSeeder
```

### 8. **Cek Role User**

**Di User Model:**

```php
// Cek apakah user adalah admin
$user->isAdmin()

// Cek permission spesifik
$user->hasPermission('pengembalian.manage')

// Cek semua permission user
$user->role->permissions->pluck('slug')
```

### 9. **Solusi Cepat**

Jika masih 404, coba:

1. **Clear Cache:**

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

2. **Restart Server:**

```bash
php artisan serve
```

3. **Cek Log Error:**

```bash
tail -f storage/logs/laravel.log
```

### 10. **Test Akses**

1. Login sebagai user dengan role ADMIN
2. Akses: `/admin/pengembalian` (harus bisa)
3. Akses: `/admin/pengembalian/history` (harus bisa)
4. Akses: `/admin/pengembalian/test-permission` (untuk cek permission)

### 11. **Catatan Penting**

-   **Admin selalu memiliki akses penuh** (cek `isAdmin()` method)
-   **Permission harus di-assign ke role** sebelum bisa digunakan
-   **Middleware permission lebih spesifik** dari middleware role
-   **Pastikan user memiliki role yang tepat** dengan permission yang sesuai

## Kesimpulan

Masalah 404 kemungkinan disebabkan oleh:

1. User tidak memiliki permission `pengembalian.manage`
2. Permission belum di-assign ke role user
3. Cache route yang perlu di-clear
4. Server yang perlu di-restart

Gunakan test route `/admin/pengembalian/test-permission` untuk mendiagnosis masalah permission.
