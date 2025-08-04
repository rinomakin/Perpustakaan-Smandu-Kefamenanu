<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            ['nama_jurusan' => 'IPA', 'kode_jurusan' => 'IPA', 'deskripsi' => 'Ilmu Pengetahuan Alam'],
            ['nama_jurusan' => 'IPS', 'kode_jurusan' => 'IPS', 'deskripsi' => 'Ilmu Pengetahuan Sosial'],
        ];

        foreach ($jurusan as $j) {
            Jurusan::create($j);
        }
    }
} 