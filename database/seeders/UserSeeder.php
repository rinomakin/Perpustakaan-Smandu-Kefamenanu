<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('kode_peran', 'ADMIN')->first();
        $kepsekRole = Role::where('kode_peran', 'KEPALA_SEKOLAH')->first();
        $petugasRole = Role::where('kode_peran', 'PETUGAS')->first();

        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran_id' => $adminRole?->id,
            'nomor_telepon' => '081234567890',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);

        User::create([
            'nama_lengkap' => 'Kepala Sekolah',
            'email' => 'kepsek@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran_id' => $kepsekRole?->id,
            'nomor_telepon' => '081234567891',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran_id' => $petugasRole?->id,
            'nomor_telepon' => '081234567892',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);
    }
} 