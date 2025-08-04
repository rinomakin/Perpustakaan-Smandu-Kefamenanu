<?php

namespace Database\Seeders;

use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;

class KategoriBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Pelajaran', 'kode_kategori' => 'PEL', 'deskripsi' => 'Buku pelajaran sekolah'],
            ['nama_kategori' => 'Novel', 'kode_kategori' => 'NOV', 'deskripsi' => 'Buku novel fiksi'],
            ['nama_kategori' => 'Ensiklopedia', 'kode_kategori' => 'ENS', 'deskripsi' => 'Buku ensiklopedia'],
            ['nama_kategori' => 'Kamus', 'kode_kategori' => 'KAM', 'deskripsi' => 'Buku kamus'],
            ['nama_kategori' => 'Majalah', 'kode_kategori' => 'MAJ', 'deskripsi' => 'Majalah dan jurnal'],
            ['nama_kategori' => 'Referensi', 'kode_kategori' => 'REF', 'deskripsi' => 'Buku referensi'],
        ];

        foreach ($kategori as $k) {
            KategoriBuku::create($k);
        }
    }
} 