<?php

namespace Database\Seeders;

use App\Models\SumberBuku;
use Illuminate\Database\Seeder;

class SumberBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sumber = [
            ['nama_sumber' => 'Pembelian', 'kode_sumber' => 'BELI', 'deskripsi' => 'Buku hasil pembelian'],
            ['nama_sumber' => 'Hibah', 'kode_sumber' => 'HIB', 'deskripsi' => 'Buku hasil hibah'],
            ['nama_sumber' => 'Bantuan Pemerintah', 'kode_sumber' => 'BANTU', 'deskripsi' => 'Buku dari bantuan pemerintah'],
            ['nama_sumber' => 'Sumbangan', 'kode_sumber' => 'SUMB', 'deskripsi' => 'Buku hasil sumbangan'],
        ];

        foreach ($sumber as $s) {
            SumberBuku::create($s);
        }
    }
} 