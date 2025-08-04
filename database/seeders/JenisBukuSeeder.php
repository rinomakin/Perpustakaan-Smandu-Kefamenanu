<?php

namespace Database\Seeders;

use App\Models\JenisBuku;
use Illuminate\Database\Seeder;

class JenisBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = [
            ['nama_jenis' => 'Buku Teks', 'kode_jenis' => 'TEKS', 'deskripsi' => 'Buku teks pelajaran'],
            ['nama_jenis' => 'Buku Non-Teks', 'kode_jenis' => 'NON-TEKS', 'deskripsi' => 'Buku non-teks pelajaran'],
            ['nama_jenis' => 'Buku Fiksi', 'kode_jenis' => 'FIKSI', 'deskripsi' => 'Buku fiksi'],
            ['nama_jenis' => 'Buku Non-Fiksi', 'kode_jenis' => 'NON-FIKSI', 'deskripsi' => 'Buku non-fiksi'],
        ];

        foreach ($jenis as $j) {
            JenisBuku::create($j);
        }
    }
} 