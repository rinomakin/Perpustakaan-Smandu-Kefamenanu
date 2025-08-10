<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class MigrateUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping enum ke role ID
        $roleMapping = [
            'admin' => Role::where('kode_peran', 'ADMIN')->first()?->id,
            'kepala_sekolah' => Role::where('kode_peran', 'KEPALA_SEKOLAH')->first()?->id,
            'petugas' => Role::where('kode_peran', 'PETUGAS')->first()?->id,
        ];

        // Update semua user yang ada (skip karena kolom peran sudah tidak ada)
        // Seeder ini hanya untuk migrasi dari enum ke foreign key
        // Jika diperlukan, bisa dijalankan sebelum migration yang menghapus kolom peran
        
        // foreach ($roleMapping as $enumValue => $roleId) {
        //     if ($roleId) {
        //         DB::table('users')
        //             ->where('peran', $enumValue)
        //             ->update(['peran_id' => $roleId]);
        //     }
        // }
    }
}
