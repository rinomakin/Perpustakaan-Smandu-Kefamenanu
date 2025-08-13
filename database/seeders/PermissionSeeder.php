<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'Lihat User', 'slug' => 'user.view', 'description' => 'Dapat melihat daftar user', 'group_name' => 'User Management'],
            ['name' => 'Tambah User', 'slug' => 'user.create', 'description' => 'Dapat menambah user baru', 'group_name' => 'User Management'],
            ['name' => 'Edit User', 'slug' => 'user.edit', 'description' => 'Dapat mengedit data user', 'group_name' => 'User Management'],
            ['name' => 'Hapus User', 'slug' => 'user.delete', 'description' => 'Dapat menghapus user', 'group_name' => 'User Management'],
            ['name' => 'Kelola User', 'slug' => 'user.manage', 'description' => 'Dapat mengelola semua aspek user', 'group_name' => 'User Management'],

            // Role Management
            ['name' => 'Lihat Role', 'slug' => 'role.view', 'description' => 'Dapat melihat daftar role', 'group_name' => 'Role Management'],
            ['name' => 'Tambah Role', 'slug' => 'role.create', 'description' => 'Dapat menambah role baru', 'group_name' => 'Role Management'],
            ['name' => 'Edit Role', 'slug' => 'role.edit', 'description' => 'Dapat mengedit data role', 'group_name' => 'Role Management'],
            ['name' => 'Hapus Role', 'slug' => 'role.delete', 'description' => 'Dapat menghapus role', 'group_name' => 'Role Management'],
            ['name' => 'Kelola Role', 'slug' => 'role.manage', 'description' => 'Dapat mengelola semua aspek role', 'group_name' => 'Role Management'],

            // Permission Management
            ['name' => 'Lihat Permission', 'slug' => 'permission.view', 'description' => 'Dapat melihat daftar permission', 'group_name' => 'Permission Management'],
            ['name' => 'Kelola Permission', 'slug' => 'permission.manage', 'description' => 'Dapat mengelola permission', 'group_name' => 'Permission Management'],

            // Anggota Management
            ['name' => 'Lihat Anggota', 'slug' => 'anggota.view', 'description' => 'Dapat melihat daftar anggota', 'group_name' => 'Data Anggota'],
            ['name' => 'Tambah Anggota', 'slug' => 'anggota.create', 'description' => 'Dapat menambah anggota baru', 'group_name' => 'Data Anggota'],
            ['name' => 'Edit Anggota', 'slug' => 'anggota.edit', 'description' => 'Dapat mengedit data anggota', 'group_name' => 'Data Anggota'],
            ['name' => 'Hapus Anggota', 'slug' => 'anggota.delete', 'description' => 'Dapat menghapus anggota', 'group_name' => 'Data Anggota'],
            ['name' => 'Kelola Anggota', 'slug' => 'anggota.manage', 'description' => 'Dapat mengelola semua aspek anggota', 'group_name' => 'Data Anggota'],
            ['name' => 'Export Anggota', 'slug' => 'anggota.export', 'description' => 'Dapat export data anggota', 'group_name' => 'Data Anggota'],
            ['name' => 'Import Anggota', 'slug' => 'anggota.import', 'description' => 'Dapat import data anggota', 'group_name' => 'Data Anggota'],

            // Buku Management
            ['name' => 'Lihat Buku', 'slug' => 'buku.view', 'description' => 'Dapat melihat daftar buku', 'group_name' => 'Data Buku'],
            ['name' => 'Tambah Buku', 'slug' => 'buku.create', 'description' => 'Dapat menambah buku baru', 'group_name' => 'Data Buku'],
            ['name' => 'Edit Buku', 'slug' => 'buku.edit', 'description' => 'Dapat mengedit data buku', 'group_name' => 'Data Buku'],
            ['name' => 'Hapus Buku', 'slug' => 'buku.delete', 'description' => 'Dapat menghapus buku', 'group_name' => 'Data Buku'],
            ['name' => 'Kelola Buku', 'slug' => 'buku.manage', 'description' => 'Dapat mengelola semua aspek buku', 'group_name' => 'Data Buku'],
            ['name' => 'Export Buku', 'slug' => 'buku.export', 'description' => 'Dapat export data buku', 'group_name' => 'Data Buku'],
            ['name' => 'Import Buku', 'slug' => 'buku.import', 'description' => 'Dapat import data buku', 'group_name' => 'Data Buku'],
            ['name' => 'Cetak Barcode Buku', 'slug' => 'buku.print-barcode', 'description' => 'Dapat mencetak barcode buku', 'group_name' => 'Data Buku'],

            // Kategori Buku
            ['name' => 'Lihat Kategori Buku', 'slug' => 'kategori-buku.view', 'description' => 'Dapat melihat daftar kategori buku', 'group_name' => 'Data Master'],
            ['name' => 'Tambah Kategori Buku', 'slug' => 'kategori-buku.create', 'description' => 'Dapat menambah kategori buku baru', 'group_name' => 'Data Master'],
            ['name' => 'Edit Kategori Buku', 'slug' => 'kategori-buku.edit', 'description' => 'Dapat mengedit data kategori buku', 'group_name' => 'Data Master'],
            ['name' => 'Hapus Kategori Buku', 'slug' => 'kategori-buku.delete', 'description' => 'Dapat menghapus kategori buku', 'group_name' => 'Data Master'],
            ['name' => 'Kelola Kategori Buku', 'slug' => 'kategori-buku.manage', 'description' => 'Dapat mengelola semua aspek kategori buku', 'group_name' => 'Data Master'],

            // Jenis Buku
            ['name' => 'Lihat Jenis Buku', 'slug' => 'jenis-buku.view', 'description' => 'Dapat melihat daftar jenis buku', 'group_name' => 'Data Master'],
            ['name' => 'Tambah Jenis Buku', 'slug' => 'jenis-buku.create', 'description' => 'Dapat menambah jenis buku baru', 'group_name' => 'Data Master'],
            ['name' => 'Edit Jenis Buku', 'slug' => 'jenis-buku.edit', 'description' => 'Dapat mengedit data jenis buku', 'group_name' => 'Data Master'],
            ['name' => 'Hapus Jenis Buku', 'slug' => 'jenis-buku.delete', 'description' => 'Dapat menghapus jenis buku', 'group_name' => 'Data Master'],
            ['name' => 'Kelola Jenis Buku', 'slug' => 'jenis-buku.manage', 'description' => 'Dapat mengelola semua aspek jenis buku', 'group_name' => 'Data Master'],

            // Sumber Buku
            ['name' => 'Lihat Sumber Buku', 'slug' => 'sumber-buku.view', 'description' => 'Dapat melihat daftar sumber buku', 'group_name' => 'Data Master'],
            ['name' => 'Tambah Sumber Buku', 'slug' => 'sumber-buku.create', 'description' => 'Dapat menambah sumber buku baru', 'group_name' => 'Data Master'],
            ['name' => 'Edit Sumber Buku', 'slug' => 'sumber-buku.edit', 'description' => 'Dapat mengedit data sumber buku', 'group_name' => 'Data Master'],
            ['name' => 'Hapus Sumber Buku', 'slug' => 'sumber-buku.delete', 'description' => 'Dapat menghapus sumber buku', 'group_name' => 'Data Master'],
            ['name' => 'Kelola Sumber Buku', 'slug' => 'sumber-buku.manage', 'description' => 'Dapat mengelola semua aspek sumber buku', 'group_name' => 'Data Master'],

            // Rak Buku
            ['name' => 'Lihat Rak Buku', 'slug' => 'rak-buku.view', 'description' => 'Dapat melihat daftar rak buku', 'group_name' => 'Data Master'],
            ['name' => 'Tambah Rak Buku', 'slug' => 'rak-buku.create', 'description' => 'Dapat menambah rak buku baru', 'group_name' => 'Data Master'],
            ['name' => 'Edit Rak Buku', 'slug' => 'rak-buku.edit', 'description' => 'Dapat mengedit data rak buku', 'group_name' => 'Data Master'],
            ['name' => 'Hapus Rak Buku', 'slug' => 'rak-buku.delete', 'description' => 'Dapat menghapus rak buku', 'group_name' => 'Data Master'],
            ['name' => 'Kelola Rak Buku', 'slug' => 'rak-buku.manage', 'description' => 'Dapat mengelola semua aspek rak buku', 'group_name' => 'Data Master'],

            // Peminjaman - Granular Permissions
            ['name' => 'Lihat Peminjaman', 'slug' => 'peminjaman.view', 'description' => 'Dapat melihat daftar peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Tambah Peminjaman', 'slug' => 'peminjaman.create', 'description' => 'Dapat menambah peminjaman baru', 'group_name' => 'Transaksi'],
            ['name' => 'Edit Peminjaman', 'slug' => 'peminjaman.edit', 'description' => 'Dapat mengedit data peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Hapus Peminjaman', 'slug' => 'peminjaman.delete', 'description' => 'Dapat menghapus peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Detail Peminjaman', 'slug' => 'peminjaman.show', 'description' => 'Dapat melihat detail peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Export Peminjaman', 'slug' => 'peminjaman.export', 'description' => 'Dapat export data peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Scan Barcode Peminjaman', 'slug' => 'peminjaman.scan', 'description' => 'Dapat scan barcode untuk peminjaman', 'group_name' => 'Transaksi'],
            ['name' => 'Kelola Peminjaman', 'slug' => 'peminjaman.manage', 'description' => 'Dapat mengelola semua aspek peminjaman', 'group_name' => 'Transaksi'],

            // Pengembalian - Granular Permissions
            ['name' => 'Lihat Pengembalian', 'slug' => 'pengembalian.view', 'description' => 'Dapat melihat daftar pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Tambah Pengembalian', 'slug' => 'pengembalian.create', 'description' => 'Dapat menambah pengembalian baru', 'group_name' => 'Transaksi'],
            ['name' => 'Edit Pengembalian', 'slug' => 'pengembalian.edit', 'description' => 'Dapat mengedit data pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Hapus Pengembalian', 'slug' => 'pengembalian.delete', 'description' => 'Dapat menghapus pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Detail Pengembalian', 'slug' => 'pengembalian.show', 'description' => 'Dapat melihat detail pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Export Pengembalian', 'slug' => 'pengembalian.export', 'description' => 'Dapat export data pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Scan Barcode Pengembalian', 'slug' => 'pengembalian.scan', 'description' => 'Dapat scan barcode untuk pengembalian', 'group_name' => 'Transaksi'],
            ['name' => 'Kelola Pengembalian', 'slug' => 'pengembalian.manage', 'description' => 'Dapat mengelola semua aspek pengembalian', 'group_name' => 'Transaksi'],

            // Riwayat Transaksi
            ['name' => 'Lihat Riwayat Transaksi', 'slug' => 'riwayat-transaksi.view', 'description' => 'Dapat melihat riwayat transaksi', 'group_name' => 'Transaksi'],
            ['name' => 'Export Riwayat Transaksi', 'slug' => 'riwayat-transaksi.export', 'description' => 'Dapat export riwayat transaksi', 'group_name' => 'Transaksi'],

            // Denda
            ['name' => 'Lihat Denda', 'slug' => 'denda.view', 'description' => 'Dapat melihat daftar denda', 'group_name' => 'Denda'],
            ['name' => 'Tambah Denda', 'slug' => 'denda.create', 'description' => 'Dapat menambah denda baru', 'group_name' => 'Denda'],
            ['name' => 'Edit Denda', 'slug' => 'denda.edit', 'description' => 'Dapat mengedit data denda', 'group_name' => 'Denda'],
            ['name' => 'Hapus Denda', 'slug' => 'denda.delete', 'description' => 'Dapat menghapus denda', 'group_name' => 'Denda'],
            ['name' => 'Kelola Denda', 'slug' => 'denda.manage', 'description' => 'Dapat mengelola semua aspek denda', 'group_name' => 'Denda'],
            ['name' => 'Export Denda', 'slug' => 'denda.export', 'description' => 'Dapat export data denda', 'group_name' => 'Denda'],

            // Absensi Pengunjung
            ['name' => 'Lihat Absensi Pengunjung', 'slug' => 'absensi-pengunjung.view', 'description' => 'Dapat melihat daftar absensi pengunjung', 'group_name' => 'Absensi'],
            ['name' => 'Tambah Absensi Pengunjung', 'slug' => 'absensi-pengunjung.create', 'description' => 'Dapat menambah absensi pengunjung baru', 'group_name' => 'Absensi'],
            ['name' => 'Edit Absensi Pengunjung', 'slug' => 'absensi-pengunjung.edit', 'description' => 'Dapat mengedit data absensi pengunjung', 'group_name' => 'Absensi'],
            ['name' => 'Hapus Absensi Pengunjung', 'slug' => 'absensi-pengunjung.delete', 'description' => 'Dapat menghapus absensi pengunjung', 'group_name' => 'Absensi'],
            ['name' => 'Kelola Absensi Pengunjung', 'slug' => 'absensi-pengunjung.manage', 'description' => 'Dapat mengelola semua aspek absensi pengunjung', 'group_name' => 'Absensi'],
            ['name' => 'Export Absensi Pengunjung', 'slug' => 'absensi-pengunjung.export', 'description' => 'Dapat export data absensi pengunjung', 'group_name' => 'Absensi'],

            // Laporan
            ['name' => 'Laporan Anggota', 'slug' => 'laporan.anggota', 'description' => 'Dapat melihat laporan anggota', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Buku', 'slug' => 'laporan.buku', 'description' => 'Dapat melihat laporan buku', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Peminjaman', 'slug' => 'laporan.peminjaman', 'description' => 'Dapat melihat laporan peminjaman', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Pengembalian', 'slug' => 'laporan.pengembalian', 'description' => 'Dapat melihat laporan pengembalian', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Denda', 'slug' => 'laporan.denda', 'description' => 'Dapat melihat laporan denda', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Absensi', 'slug' => 'laporan.absensi', 'description' => 'Dapat melihat laporan absensi', 'group_name' => 'Laporan'],
            ['name' => 'Laporan Kas', 'slug' => 'laporan.kas', 'description' => 'Dapat melihat laporan kas', 'group_name' => 'Laporan'],

            // Pengaturan
            ['name' => 'Lihat Pengaturan', 'slug' => 'pengaturan.view', 'description' => 'Dapat melihat pengaturan sistem', 'group_name' => 'Pengaturan'],
            ['name' => 'Edit Pengaturan', 'slug' => 'pengaturan.edit', 'description' => 'Dapat mengedit pengaturan sistem', 'group_name' => 'Pengaturan'],
            ['name' => 'Kelola Pengaturan', 'slug' => 'pengaturan.manage', 'description' => 'Dapat mengelola semua aspek pengaturan', 'group_name' => 'Pengaturan'],

            // Dashboard
            ['name' => 'Lihat Dashboard', 'slug' => 'dashboard.view', 'description' => 'Dapat mengakses dashboard', 'group_name' => 'Dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'group_name' => $permission['group_name'],
                    'status' => 'aktif'
                ]
            );
        }
    }
}
