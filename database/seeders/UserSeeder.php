<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran' => 'admin',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);

        User::create([
            'nama_lengkap' => 'Kepala Sekolah',
            'email' => 'kepsek@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran' => 'kepala_sekolah',
            'nomor_telepon' => '081234567891',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpustakaan.com',
            'password' => Hash::make('password'),
            'peran' => 'petugas',
            'nomor_telepon' => '081234567892',
            'alamat' => 'Kefamenanu, Timor Tengah Utara',
            'status' => 'aktif',
        ]);
    }
} 