<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permissions {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test permissions for a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
        } else {
            // Get kepala sekolah user
            $user = User::whereHas('role', function($query) {
                $query->where('kode_peran', 'KEPALA_SEKOLAH');
            })->first();
        }
        
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }
        
        $this->info("Testing permissions for user: {$user->nama_lengkap}");
        $this->info("Role: {$user->role->nama_peran} ({$user->role->kode_peran})");
        $this->info('');
        
        // Test peminjaman permissions
        $this->info('=== PEMINJAMAN PERMISSIONS ===');
        $this->info('peminjaman.view: ' . ($user->hasPermission('peminjaman.view') ? 'YES' : 'NO'));
        $this->info('peminjaman.create: ' . ($user->hasPermission('peminjaman.create') ? 'YES' : 'NO'));
        $this->info('peminjaman.edit: ' . ($user->hasPermission('peminjaman.edit') ? 'YES' : 'NO'));
        $this->info('peminjaman.delete: ' . ($user->hasPermission('peminjaman.delete') ? 'YES' : 'NO'));
        $this->info('peminjaman.show: ' . ($user->hasPermission('peminjaman.show') ? 'YES' : 'NO'));
        $this->info('');
        
        // Test pengembalian permissions
        $this->info('=== PENGEMBALIAN PERMISSIONS ===');
        $this->info('pengembalian.view: ' . ($user->hasPermission('pengembalian.view') ? 'YES' : 'NO'));
        $this->info('pengembalian.create: ' . ($user->hasPermission('pengembalian.create') ? 'YES' : 'NO'));
        $this->info('pengembalian.edit: ' . ($user->hasPermission('pengembalian.edit') ? 'YES' : 'NO'));
        $this->info('pengembalian.delete: ' . ($user->hasPermission('pengembalian.delete') ? 'YES' : 'NO'));
        $this->info('pengembalian.show: ' . ($user->hasPermission('pengembalian.show') ? 'YES' : 'NO'));
        $this->info('');
        
        // Test other permissions
        $this->info('=== OTHER PERMISSIONS ===');
        $this->info('dashboard.view: ' . ($user->hasPermission('dashboard.view') ? 'YES' : 'NO'));
        $this->info('anggota.view: ' . ($user->hasPermission('anggota.view') ? 'YES' : 'NO'));
        $this->info('buku.view: ' . ($user->hasPermission('buku.view') ? 'YES' : 'NO'));
        $this->info('riwayat-transaksi.view: ' . ($user->hasPermission('riwayat-transaksi.view') ? 'YES' : 'NO'));
        $this->info('');
        
        // Show all permissions
        $this->info('=== ALL PERMISSIONS ===');
        $permissions = $user->getAllPermissions();
        foreach ($permissions as $permission) {
            $this->info("- {$permission->name} ({$permission->slug})");
        }
        
        return 0;
    }
}
