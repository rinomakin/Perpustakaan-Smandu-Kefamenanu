<?php

namespace Database\Seeders;

use App\Models\Penulis;
use Illuminate\Database\Seeder;

class PenulisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penulis = [
            [
                'nama_penulis' => 'Tim Penulis Erlangga',
                'kode_penulis' => 'TPE',
                'biografi' => 'Tim penulis buku pelajaran Erlangga',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1980-01-01'
            ],
            [
                'nama_penulis' => 'Tim Penulis Yudhistira',
                'kode_penulis' => 'TPY',
                'biografi' => 'Tim penulis buku pelajaran Yudhistira',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-01-01'
            ],
            [
                'nama_penulis' => 'Dewi Sartika',
                'kode_penulis' => 'DS',
                'biografi' => 'Penulis novel dan buku fiksi',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-01-01'
            ],
        ];

        foreach ($penulis as $p) {
            Penulis::create($p);
        }
    }
} 