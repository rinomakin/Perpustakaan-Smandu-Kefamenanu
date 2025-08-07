# Dokumentasi CRUD Role dan User

## Overview

Sistem perpustakaan ini telah dilengkapi dengan fitur manajemen Role dan User yang memungkinkan administrator untuk mengelola peran dan pengguna sistem.

## 1. CRUD Role

### Model: `App\Models\Role`

-   **Table**: `peran`
-   **Fields**:
    -   `id` (Primary Key)
    -   `nama_peran` (String)
    -   `kode_peran` (String, Unique)
    -   `deskripsi` (Text, Nullable)
    -   `status` (Enum: 'aktif', 'nonaktif')
    -   `created_at`, `updated_at` (Timestamps)

### Controller: `App\Http\Controllers\RoleController`

-   **Routes**: `Route::resource('role', RoleController::class)`
-   **Additional Routes**:
    -   `POST /role/generate-kode` - Generate kode role otomatis

### Views:

-   `resources/views/admin/role/index.blade.php` - Daftar role
-   `resources/views/admin/role/create.blade.php` - Form tambah role
-   `resources/views/admin/role/edit.blade.php` - Form edit role
-   `resources/views/admin/role/show.blade.php` - Detail role

### Fitur:

1. **List Role** - Menampilkan daftar semua role dengan pagination
2. **Create Role** - Menambah role baru dengan validasi
3. **Edit Role** - Mengubah data role yang ada
4. **Delete Role** - Menghapus role (dengan validasi relasi)
5. **Show Role** - Menampilkan detail role dan user yang menggunakan role tersebut
6. **Auto Generate Kode** - Generate kode role otomatis berdasarkan nama role

### Validasi:

-   Nama role: required, string, max 255
-   Kode role: required, string, max 50, unique
-   Deskripsi: nullable, string
-   Status: required, enum (aktif/nonaktif)

## 2. CRUD User

### Model: `App\Models\User`

-   **Table**: `users`
-   **Fields**:
    -   `id` (Primary Key)
    -   `nama_lengkap` (String)
    -   `email` (String, Unique)
    -   `password` (String, Hashed)
    -   `peran` (String - Foreign Key ke `peran.kode_peran`)
    -   `nomor_telepon` (String, Nullable)
    -   `alamat` (Text, Nullable)
    -   `status` (Enum: 'aktif', 'nonaktif')
    -   `email_verified_at` (Timestamp, Nullable)
    -   `remember_token` (String, Nullable)
    -   `created_at`, `updated_at` (Timestamps)

### Controller: `App\Http\Controllers\UserController`

-   **Routes**: `Route::resource('user', UserController::class)`
-   **Additional Routes**:
    -   `POST /user/{user}/reset-password` - Reset password user

### Views:

-   `resources/views/admin/user/index.blade.php` - Daftar user
-   `resources/views/admin/user/create.blade.php` - Form tambah user
-   `resources/views/admin/user/edit.blade.php` - Form edit user
-   `resources/views/admin/user/show.blade.php` - Detail user

### Fitur:

1. **List User** - Menampilkan daftar semua user dengan pagination
2. **Create User** - Menambah user baru dengan validasi
3. **Edit User** - Mengubah data user (password opsional)
4. **Delete User** - Menghapus user (dengan validasi user aktif)
5. **Show User** - Menampilkan detail user dan informasi akun
6. **Reset Password** - Reset password user menjadi 'password123'

### Validasi:

-   Nama lengkap: required, string, max 255
-   Email: required, email, unique
-   Password: required (create), nullable (edit), min 8, confirmed
-   Role: required, exists in peran table
-   Nomor telepon: nullable, string, max 20
-   Alamat: nullable, string
-   Status: required, enum (aktif/nonaktif)

## 3. Relationship

### Role -> User

```php
// Di model Role
public function users()
{
    return $this->hasMany(User::class, 'peran', 'kode_peran');
}
```

### User -> Role

```php
// Di model User
public function role()
{
    return $this->belongsTo(Role::class, 'peran', 'kode_peran');
}
```

## 4. Menu Navigation

### Role Menu

-   **Desktop**: Dropdown "Master" -> "Role"
-   **Mobile**: Section "Data Master" -> "Role"
-   **Route**: `route('role.index')`

### User Menu

-   **Desktop**: Menu utama "User"
-   **Mobile**: Section utama "User"
-   **Route**: `route('user.index')`

## 5. Data Default

### Role Seeder

Role default yang dibuat:

1. **Administrator** (ADMIN) - Akses penuh sistem
2. **Kepala Sekolah** (KEPALA_SEKOLAH) - Akses laporan
3. **Petugas** (PETUGAS) - Akses terbatas

### User Seeder

User default yang dibuat:

-   Admin dengan role ADMIN
-   Kepala Sekolah dengan role KEPALA_SEKOLAH
-   Petugas dengan role PETUGAS

## 6. Keamanan

### Role Protection

-   Role tidak dapat dihapus jika masih digunakan oleh user
-   Validasi kode role unik

### User Protection

-   User tidak dapat menghapus akun yang sedang digunakan
-   Password di-hash menggunakan bcrypt
-   Validasi email unik

## 7. Fitur Tambahan

### Auto Generate Kode Role

-   Kode role otomatis dibuat dari nama role
-   Format: UPPERCASE dengan underscore
-   Contoh: "Kepala Sekolah" -> "KEPALA_SEKOLAH"

### Reset Password

-   Admin dapat reset password user
-   Password default: "password123"
-   Konfirmasi sebelum reset

### Status Management

-   Role dan User memiliki status aktif/nonaktif
-   Filter berdasarkan status

## 8. Usage

### Menjalankan Seeder

```bash
php artisan db:seed --class=RoleSeeder
```

### Akses Menu

1. Login sebagai admin
2. Klik menu "Master" -> "Role" untuk mengelola role
3. Klik menu "User" untuk mengelola user

### Menambah Role Baru

1. Buka halaman Role
2. Klik "Tambah Role"
3. Isi form dengan data role
4. Kode role akan otomatis dibuat
5. Klik "Simpan"

### Menambah User Baru

1. Buka halaman User
2. Klik "Tambah User"
3. Isi form dengan data user
4. Pilih role yang sesuai
5. Klik "Simpan"

## 9. Troubleshooting

### Error: Role tidak dapat dihapus

-   Pastikan tidak ada user yang menggunakan role tersebut
-   Hapus atau pindahkan user terlebih dahulu

### Error: User tidak dapat dihapus

-   Pastikan user yang akan dihapus bukan user yang sedang login
-   Logout dan login dengan user lain untuk menghapus

### Error: Email sudah digunakan

-   Gunakan email yang berbeda
-   Atau edit user yang sudah ada

## 10. Future Enhancement

### Fitur yang bisa ditambahkan:

1. **Role Permission** - Sistem permission detail per role
2. **User Activity Log** - Log aktivitas user
3. **Bulk Operations** - Operasi massal untuk role/user
4. **Import/Export** - Import/export data role dan user
5. **Password Policy** - Kebijakan password yang lebih ketat
6. **Two-Factor Authentication** - Autentikasi dua faktor
