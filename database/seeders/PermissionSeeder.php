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
            // Dashboard
            [
                'name' => 'Akses Dashboard',
                'slug' => 'dashboard.view',
                'description' => 'Dapat mengakses halaman dashboard admin',
                'group_name' => 'Dashboard'
            ],

            // Data Master
            [
                'name' => 'Kelola Role',
                'slug' => 'role.manage',
                'description' => 'Dapat mengelola data role/peran pengguna',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Data Jurusan',
                'slug' => 'jurusan.manage',
                'description' => 'Dapat mengelola data jurusan',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Data Kelas',
                'slug' => 'kelas.manage',
                'description' => 'Dapat mengelola data kelas',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Jenis Buku',
                'slug' => 'jenis-buku.manage',
                'description' => 'Dapat mengelola jenis buku',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Kategori Buku',
                'slug' => 'kategori-buku.manage',
                'description' => 'Dapat mengelola kategori buku',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Rak Buku',
                'slug' => 'rak-buku.manage',
                'description' => 'Dapat mengelola data rak buku',
                'group_name' => 'Data Master'
            ],
            [
                'name' => 'Kelola Sumber Buku',
                'slug' => 'sumber-buku.manage',
                'description' => 'Dapat mengelola sumber buku',
                'group_name' => 'Data Master'
            ],

            // Data Anggota
            [
                'name' => 'Lihat Data Anggota',
                'slug' => 'anggota.view',
                'description' => 'Dapat melihat daftar anggota perpustakaan',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Tambah Anggota',
                'slug' => 'anggota.create',
                'description' => 'Dapat menambah anggota baru',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Edit Anggota',
                'slug' => 'anggota.update',
                'description' => 'Dapat mengubah data anggota',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Hapus Anggota',
                'slug' => 'anggota.delete',
                'description' => 'Dapat menghapus anggota',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Export Anggota',
                'slug' => 'anggota.export',
                'description' => 'Dapat export data anggota',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Import Anggota',
                'slug' => 'anggota.import',
                'description' => 'Dapat import data anggota',
                'group_name' => 'Data Anggota'
            ],
            [
                'name' => 'Cetak Kartu Anggota',
                'slug' => 'anggota.cetak-kartu',
                'description' => 'Dapat mencetak kartu anggota',
                'group_name' => 'Data Anggota'
            ],

            // User Management
            [
                'name' => 'Kelola User',
                'slug' => 'user.manage',
                'description' => 'Dapat mengelola data user sistem',
                'group_name' => 'User Management'
            ],
            [
                'name' => 'Reset Password User',
                'slug' => 'user.reset-password',
                'description' => 'Dapat mereset password user',
                'group_name' => 'User Management'
            ],

            // Hak Akses
            [
                'name' => 'Kelola Hak Akses',
                'slug' => 'permissions.manage',
                'description' => 'Dapat mengelola hak akses/permission sistem',
                'group_name' => 'Hak Akses'
            ],

            // Data Buku
            [
                'name' => 'Lihat Data Buku',
                'slug' => 'buku.view',
                'description' => 'Dapat melihat daftar buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Tambah Buku',
                'slug' => 'buku.create',
                'description' => 'Dapat menambah buku baru',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Edit Buku',
                'slug' => 'buku.update',
                'description' => 'Dapat mengubah data buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Hapus Buku',
                'slug' => 'buku.delete',
                'description' => 'Dapat menghapus buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Export Buku',
                'slug' => 'buku.export',
                'description' => 'Dapat export data buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Import Buku',
                'slug' => 'buku.import',
                'description' => 'Dapat import data buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Generate Barcode Buku',
                'slug' => 'buku.generate-barcode',
                'description' => 'Dapat generate barcode untuk buku',
                'group_name' => 'Data Buku'
            ],
            [
                'name' => 'Cetak Barcode Buku',
                'slug' => 'buku.cetak-barcode',
                'description' => 'Dapat mencetak barcode buku',
                'group_name' => 'Data Buku'
            ],

            // Transaksi
            [
                'name' => 'Kelola Peminjaman',
                'slug' => 'peminjaman.manage',
                'description' => 'Dapat mengelola transaksi peminjaman buku',
                'group_name' => 'Transaksi'
            ],
            [
                'name' => 'Kelola Pengembalian',
                'slug' => 'pengembalian.manage',
                'description' => 'Dapat mengelola pengembalian buku',
                'group_name' => 'Transaksi'
            ],
            [
                'name' => 'Lihat Riwayat Transaksi',
                'slug' => 'riwayat-transaksi.view',
                'description' => 'Dapat melihat riwayat transaksi peminjaman',
                'group_name' => 'Transaksi'
            ],

            // Laporan
            [
                'name' => 'Laporan Anggota',
                'slug' => 'laporan.anggota',
                'description' => 'Dapat melihat laporan data anggota',
                'group_name' => 'Laporan'
            ],
            [
                'name' => 'Laporan Buku',
                'slug' => 'laporan.buku',
                'description' => 'Dapat melihat laporan data buku',
                'group_name' => 'Laporan'
            ],
            [
                'name' => 'Laporan Kas',
                'slug' => 'laporan.kas',
                'description' => 'Dapat melihat laporan kas/keuangan',
                'group_name' => 'Laporan'
            ],

            // Pengaturan
            [
                'name' => 'Kelola Pengaturan',
                'slug' => 'pengaturan.manage',
                'description' => 'Dapat mengelola pengaturan sistem',
                'group_name' => 'Pengaturan'
            ],

            // Absensi Pengunjung
            [
                'name' => 'Kelola Absensi Pengunjung',
                'slug' => 'absensi.manage',
                'description' => 'Dapat mengelola absensi pengunjung perpustakaan',
                'group_name' => 'Absensi'
            ],
            [
                'name' => 'Scan Barcode Anggota',
                'slug' => 'absensi.scan',
                'description' => 'Dapat melakukan scan barcode untuk absensi anggota',
                'group_name' => 'Absensi'
            ],
            [
                'name' => 'Lihat Riwayat Kunjungan',
                'slug' => 'absensi.history',
                'description' => 'Dapat melihat riwayat kunjungan anggota',
                'group_name' => 'Absensi'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
