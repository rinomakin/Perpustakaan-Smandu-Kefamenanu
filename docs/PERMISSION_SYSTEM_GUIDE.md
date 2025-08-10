# 🔒 Panduan Sistem Hak Akses (Permission System)

Dokumentasi lengkap untuk sistem hak akses berbasis role dan permission yang mudah digunakan dengan interface centang-centang.

## 🚀 Fitur yang Tersedia

### 1. **Management Hak Akses** 📋

-   **URL**: `/admin/permissions`
-   **Fitur**: Kelola hak akses untuk setiap role dengan interface yang user-friendly
-   **Interface**: Checkbox untuk setiap permission, grouping berdasarkan kategori

### 2. **Operasi Hak Akses**

-   ✅ **Edit Role Permissions** - Interface checkbox untuk assign/remove permissions
-   ✅ **Copy Permissions** - Copy permissions dari satu role ke role lain
-   ✅ **Bulk Assign** - Assign permissions ke multiple roles sekaligus
-   ✅ **Reset Permissions** - Reset semua hak akses role
-   ✅ **Group Management** - Permission dikelompokkan berdasarkan modul

### 3. **Fitur Canggih**

-   ✅ **Visual Interface** - Checkbox dengan grouping dan select all
-   ✅ **Progress Indicators** - Progress bar untuk menunjukkan coverage permission
-   ✅ **Bulk Operations** - Operasi massal untuk efisiensi
-   ✅ **Permission Preview** - Preview permissions dalam card role
-   ✅ **AJAX Operations** - Smooth interactions tanpa page refresh

## 📋 Struktur Permission System

### **Model Structure:**

```
User -> Role -> Permissions
```

### **Database Tables:**

| Table              | Description                                  |
| ------------------ | -------------------------------------------- |
| `users`            | User data dengan kolom `peran` untuk role    |
| `peran` (roles)    | Master role (admin, petugas, kepala_sekolah) |
| `permissions`      | Master permissions dengan grouping           |
| `role_permissions` | Pivot table role-permission                  |

### **Permission Groups:**

-   📊 **Dashboard** - Akses dashboard
-   👥 **Manajemen User** - CRUD user, role, permissions
-   👨‍🎓 **Manajemen Anggota** - CRUD anggota perpustakaan
-   📚 **Manajemen Buku** - CRUD buku dan operasi terkait
-   🗂️ **Master Data** - Kategori, jenis, sumber, rak, jurusan, kelas
-   💸 **Transaksi** - Peminjaman, pengembalian, denda
-   📈 **Laporan** - View dan export laporan
-   ⚙️ **Pengaturan** - Konfigurasi sistem

## 🎯 Cara Penggunaan

### **1. Akses Menu Hak Akses**

1. Login sebagai Admin
2. Sidebar → **Hak Akses**
3. URL: `/admin/permissions`

### **2. Edit Hak Akses Role**

1. Pada card role yang diinginkan, klik **"Edit Hak Akses"**
2. Modal akan terbuka menampilkan semua permissions yang tersedia
3. Permissions dikelompokkan berdasarkan modul (Dashboard, User, Buku, dll)
4. **Centang/uncentang** permission yang diinginkan:
    - ✅ Centang = Role memiliki hak akses
    - ❌ Tidak dicentang = Role tidak memiliki hak akses
5. Gunakan **checkbox group** untuk select/deselect satu kategori sekaligus
6. Gunakan **"Pilih Semua"** atau **"Batal Pilih"** untuk kontrol cepat
7. Klik **"Simpan Hak Akses"**

### **3. Copy Permissions Antar Role**

1. Klik tombol **"Copy Permissions"** di header
2. Pilih **role sumber** (dari mana permission akan dicopy)
3. Pilih **role tujuan** (ke mana permission akan dicopy)
4. Klik **"Copy"**
5. Semua permissions dari role sumber akan disalin ke role tujuan

### **4. Bulk Assign Permissions**

1. Klik tombol **"Bulk Assign"** di header
2. **Pilih role-role** yang akan diubah (bisa multiple)
3. **Pilih aksi**:
    - **Tambah hak akses** - Menambah permissions tanpa menghapus yang sudah ada
    - **Ganti semua hak akses** - Replace semua permissions
    - **Hapus hak akses** - Remove permissions tertentu
4. Klik **"Proses"**

### **5. Reset Hak Akses**

1. Pada card role, klik tombol **reset** (icon undo merah)
2. Konfirmasi reset
3. Semua hak akses role akan dihapus

## 🛠️ Technical Implementation

### **Models dengan Permission Methods:**

#### **User Model:**

```php
// Check single permission
$user->hasPermission('buku.create')

// Check multiple permissions (any)
$user->hasAnyPermission(['buku.create', 'buku.view'])

// Check multiple permissions (all)
$user->hasAllPermissions(['buku.create', 'buku.view'])

// Get all user permissions
$user->getAllPermissions()

// Role checks
$user->isAdmin()
$user->isPetugas()
$user->isKepalaSekolah()
```

#### **Role Model:**

```php
// Assign permission to role
$role->givePermission('buku.create')

// Remove permission from role
$role->removePermission('buku.create')

// Sync permissions (replace all)
$role->syncPermissions(['buku.create', 'buku.view'])

// Check if role has permission
$role->hasPermission('buku.create')
```

#### **Permission Model:**

```php
// Get grouped permissions
Permission::getGroupedPermissions()

// Get permission groups
Permission::getPermissionGroups()
```

### **Middleware Usage:**

#### **Role-based Access:**

```php
Route::middleware(['role:admin'])->group(function () {
    // Admin only routes
});
```

#### **Permission-based Access:**

```php
Route::middleware(['permission:buku.create'])->group(function () {
    // Routes for users with buku.create permission
});
```

#### **In Controller:**

```php
public function __construct()
{
    $this->middleware(['auth', 'permission:buku.view']);
}
```

#### **In Blade Templates:**

```php
@if(auth()->user()->hasPermission('buku.create'))
    <button>Tambah Buku</button>
@endif
```

### **Permission Slugs:**

#### **Dashboard:**

-   `dashboard.view`

#### **User Management:**

-   `users.view`, `users.create`, `users.update`, `users.delete`
-   `permissions.manage`

#### **Anggota Management:**

-   `anggota.view`, `anggota.create`, `anggota.update`, `anggota.delete`
-   `anggota.export`, `anggota.import`

#### **Buku Management:**

-   `buku.view`, `buku.create`, `buku.update`, `buku.delete`
-   `buku.export`, `buku.import`

#### **Master Data:**

-   `kategori-buku.manage`, `jenis-buku.manage`, `sumber-buku.manage`
-   `rak-buku.manage`, `jurusan.manage`, `kelas.manage`

#### **Transaksi:**

-   `peminjaman.manage`, `pengembalian.manage`, `denda.manage`

#### **Laporan:**

-   `laporan.view`, `laporan.export`

#### **Pengaturan:**

-   `pengaturan.manage`

## 🎨 UI/UX Features

### **Permission Cards:**

-   📊 **Progress Bar** - Visual coverage permissions per role
-   🏷️ **Status Badge** - Active/inactive role indicator
-   👁️ **Permission Preview** - Quick preview assigned permissions
-   🎯 **Action Buttons** - Edit, reset dengan hover effects

### **Permission Modal:**

-   ✅ **Grouped Checkboxes** - Permissions dikelompokkan per modul
-   🔘 **Group Selection** - Checkbox grup untuk select satu kategori
-   ⚡ **Quick Controls** - Select all, deselect all buttons
-   📱 **Responsive Design** - Modal responsive untuk mobile
-   🎨 **Visual Feedback** - Hover effects dan state indicators

### **Bulk Operations:**

-   👥 **Multiple Role Selection** - Checkbox untuk pilih multiple roles
-   🔄 **Flexible Actions** - Add, replace, remove permissions
-   📊 **Preview Mode** - Lihat permissions before applying

## ⚙️ Security & Validation

### **Security Features:**

-   ✅ **CSRF Protection** - Semua form dilindungi CSRF
-   ✅ **Role-based Middleware** - Admin only access
-   ✅ **Permission Validation** - Validasi permission exists
-   ✅ **Unique Constraints** - Prevent duplicate role-permission
-   ✅ **SQL Injection Protection** - Eloquent ORM protection

### **Access Control Logic:**

1. **Admin** → Full access to everything (bypass permission check)
2. **Non-Admin** → Check specific permissions
3. **No Permission** → Redirect with error message
4. **AJAX Requests** → JSON error response

### **Validation Rules:**

-   Permission IDs must exist in permissions table
-   Role IDs must exist in roles table
-   Unique role-permission combinations
-   Active permissions only

## 📈 Performance Features

### **Optimizations:**

-   ✅ **Eager Loading** - Load permissions with roles
-   ✅ **Efficient Queries** - Optimized permission checks
-   ✅ **Caching Ready** - Structure ready for permission caching
-   ✅ **Bulk Operations** - Single query for multiple updates
-   ✅ **AJAX Interface** - Smooth user experience

### **Database Indexing:**

-   Role-Permission pivot table indexed
-   Permission slug indexed for fast lookup
-   Group name indexed for grouping

## 🚀 Setup & Installation

### **1. Run Migrations:**

```bash
php artisan migrate
```

### **2. Seed Permissions:**

```bash
php artisan db:seed --class=PermissionSeeder
```

### **3. Assign Default Permissions:**

```php
// In DatabaseSeeder or manual assignment
$adminRole = Role::where('kode_peran', 'admin')->first();
$allPermissions = Permission::aktif()->pluck('id');
$adminRole->permissions()->sync($allPermissions);
```

### **4. Clear Routes:**

```bash
php artisan route:clear
```

## 🧪 Testing

### **Manual Testing Checklist:**

-   [ ] Edit role permissions via checkbox interface
-   [ ] Test group select/deselect functionality
-   [ ] Test select all/deselect all controls
-   [ ] Copy permissions between roles
-   [ ] Bulk assign permissions to multiple roles
-   [ ] Reset role permissions
-   [ ] Test permission middleware protection
-   [ ] Test user permission checking methods
-   [ ] Test mobile responsiveness
-   [ ] Test AJAX error handling

### **Permission Testing:**

-   [ ] Admin access (should bypass all permission checks)
-   [ ] User with specific permission (should have access)
-   [ ] User without permission (should be denied)
-   [ ] Invalid permission checks
-   [ ] Multiple permission checks (any/all)

---

## 🎯 **Sistem Sudah Siap Digunakan!**

✅ **URL**: `/admin/permissions`  
✅ **Navigation**: Sidebar Admin → **Hak Akses**  
✅ **Interface**: Checkbox dengan grouping yang user-friendly  
✅ **Features**: Edit, copy, bulk assign, reset permissions  
✅ **Security**: Comprehensive protection dan validation

**Sekarang Anda dapat mengatur hak akses hanya dengan centang-centang saja!** 🎉
