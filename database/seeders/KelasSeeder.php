<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['nama_kelas' => 'X IPA 1', 'kode_kelas' => 'X-IPA-1', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPA 2', 'kode_kelas' => 'X-IPA-2', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPS 1', 'kode_kelas' => 'X-IPS-1', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPS 2', 'kode_kelas' => 'X-IPS-2', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPA 1', 'kode_kelas' => 'XI-IPA-1', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPA 2', 'kode_kelas' => 'XI-IPA-2', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPS 1', 'kode_kelas' => 'XI-IPS-1', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPS 2', 'kode_kelas' => 'XI-IPS-2', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPA 1', 'kode_kelas' => 'XII-IPA-1', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPA 2', 'kode_kelas' => 'XII-IPA-2', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPS 1', 'kode_kelas' => 'XII-IPS-1', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPS 2', 'kode_kelas' => 'XII-IPS-2', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
} 