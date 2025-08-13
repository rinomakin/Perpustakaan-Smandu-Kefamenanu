<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class AssignPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('kode_peran', 'ADMIN')->first();
        $kepalaSekolahRole = Role::where('kode_peran', 'KEPALA_SEKOLAH')->first();
        $petugasRole = Role::where('kode_peran', 'PETUGAS')->first();

        if (!$adminRole || !$kepalaSekolahRole || !$petugasRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        // Admin gets all permissions
        $allPermissions = Permission::where('status', 'aktif')->pluck('slug')->toArray();
        $adminRole->syncPermissions($allPermissions);

        // Kepala Sekolah gets limited permissions
        $kepalaSekolahPermissions = [
            // Dashboard
            'dashboard.view',
            
            // View permissions only
            'anggota.view',
            'buku.view',
            'kategori-buku.view',
            'jenis-buku.view',
            'sumber-buku.view',
            'rak-buku.view',
            
            // Peminjaman - view only
            'peminjaman.view',
            'peminjaman.show',
            
            // Pengembalian - view only
            'pengembalian.view',
            'pengembalian.show',
            
            // Riwayat transaksi
            'riwayat-transaksi.view',
            
            // Denda - view only
            'denda.view',
            
            // Absensi - view only
            'absensi-pengunjung.view',
            
            // Laporan
            'laporan.anggota',
            'laporan.buku',
            'laporan.peminjaman',
            'laporan.pengembalian',
            'laporan.denda',
            'laporan.absensi',
            'laporan.kas',
            
            // Pengaturan - view only
            'pengaturan.view',
        ];
        
        $kepalaSekolahRole->syncPermissions($kepalaSekolahPermissions);

        // Petugas gets basic permissions
        $petugasPermissions = [
            // Dashboard
            'dashboard.view',
            
            // Anggota - basic operations
            'anggota.view',
            'anggota.create',
            'anggota.edit',
            
            // Buku - view only
            'buku.view',
            
            // Peminjaman - basic operations
            'peminjaman.view',
            'peminjaman.create',
            'peminjaman.show',
            'peminjaman.scan',
            
            // Pengembalian - basic operations
            'pengembalian.view',
            'pengembalian.create',
            'pengembalian.show',
            'pengembalian.scan',
            
            // Riwayat transaksi
            'riwayat-transaksi.view',
            
            // Denda - view only
            'denda.view',
            
            // Absensi - full access
            'absensi-pengunjung.view',
            'absensi-pengunjung.create',
            'absensi-pengunjung.edit',
            'absensi-pengunjung.export',
        ];
        
        $petugasRole->syncPermissions($petugasPermissions);

        $this->command->info('Permissions assigned successfully!');
        $this->command->info('Admin: ' . count($allPermissions) . ' permissions');
        $this->command->info('Kepala Sekolah: ' . count($kepalaSekolahPermissions) . ' permissions');
        $this->command->info('Petugas: ' . count($petugasPermissions) . ' permissions');
    }
}
