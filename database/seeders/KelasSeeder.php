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
            // Format kode: [KodeJurusan][TahunPendek][NomorUrut]
            // IPA
            ['nama_kelas' => 'X IPA 1', 'kode_kelas' => 'IPA241', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'X IPA 2', 'kode_kelas' => 'IPA242', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XI IPA 1', 'kode_kelas' => 'IPA243', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XI IPA 2', 'kode_kelas' => 'IPA244', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XII IPA 1', 'kode_kelas' => 'IPA245', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XII IPA 2', 'kode_kelas' => 'IPA246', 'jurusan_id' => 1, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            
            // IPS
            ['nama_kelas' => 'X IPS 1', 'kode_kelas' => 'IPS241', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'X IPS 2', 'kode_kelas' => 'IPS242', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XI IPS 1', 'kode_kelas' => 'IPS243', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XI IPS 2', 'kode_kelas' => 'IPS244', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XII IPS 1', 'kode_kelas' => 'IPS245', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
            ['nama_kelas' => 'XII IPS 2', 'kode_kelas' => 'IPS246', 'jurusan_id' => 2, 'tahun_ajaran' => '2024/2025', 'status' => 'aktif'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
} 