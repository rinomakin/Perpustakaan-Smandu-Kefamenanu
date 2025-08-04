<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBuku;

class JenisBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisBuku = [
            [
                'nama_jenis' => 'Buku Pelajaran',
                'kode_jenis' => 'BP',
                'deskripsi' => 'Buku-buku yang digunakan untuk pembelajaran di sekolah',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Referensi',
                'kode_jenis' => 'BR',
                'deskripsi' => 'Buku-buku yang berisi informasi referensi dan pengetahuan umum',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Fiksi',
                'kode_jenis' => 'BF',
                'deskripsi' => 'Buku-buku cerita fiksi, novel, dan karya sastra',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Non-Fiksi',
                'kode_jenis' => 'BNF',
                'deskripsi' => 'Buku-buku yang berisi informasi faktual dan ilmiah',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Komik',
                'kode_jenis' => 'BK',
                'deskripsi' => 'Buku-buku komik dan manga',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Ensiklopedia',
                'kode_jenis' => 'BE',
                'deskripsi' => 'Buku-buku ensiklopedia dan kamus',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Majalah',
                'kode_jenis' => 'BM',
                'deskripsi' => 'Majalah dan publikasi berkala',
                'status' => true,
            ],
            [
                'nama_jenis' => 'Buku Lainnya',
                'kode_jenis' => 'BL',
                'deskripsi' => 'Buku-buku yang tidak termasuk dalam kategori di atas',
                'status' => true,
            ],
        ];

        foreach ($jenisBuku as $jenis) {
            JenisBuku::create($jenis);
        }
    }
}