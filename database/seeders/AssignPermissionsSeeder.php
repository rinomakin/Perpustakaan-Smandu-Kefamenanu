<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AssignPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign all permissions to admin role
        $adminRole = Role::where('kode_peran', 'ADMIN')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->syncPermissions($allPermissions->pluck('id')->toArray());
            
            $this->command->info('✅ All permissions assigned to Admin role');
        }

        // Assign limited permissions to kepala_sekolah role
        $kepsekRole = Role::where('kode_peran', 'KEPALA_SEKOLAH')->first();
        if ($kepsekRole) {
            $kepsekPermissions = Permission::whereIn('slug', [
                'dashboard.view',
                'laporan.anggota',
                'laporan.buku', 
                'laporan.kas',
                'anggota.view',
                'buku.view',
                'peminjaman.manage',
                'pengembalian.manage',
                'riwayat-transaksi.view',
            ])->get();
            
            $kepsekRole->syncPermissions($kepsekPermissions->pluck('id')->toArray());
            
            $this->command->info('✅ Extended permissions assigned to Kepala Sekolah role');
        }

        // Assign basic permissions to petugas role
        $petugasRole = Role::where('kode_peran', 'PETUGAS')->first();
        if ($petugasRole) {
            $petugasPermissions = Permission::whereIn('slug', [
                'dashboard.view',
                'anggota.view',
                'anggota.create',
                'anggota.update',
                'buku.view',
                'peminjaman.manage',
                'pengembalian.manage',
                'absensi.manage',
                'absensi.scan',
                'absensi.history',
            ])->get();
            
            $petugasRole->syncPermissions($petugasPermissions->pluck('id')->toArray());
            
            $this->command->info('✅ Basic permissions assigned to Petugas role');
        }
    }
}
