<?php

namespace Database\Seeders;

use App\Models\Penerbit;
use Illuminate\Database\Seeder;

class PenerbitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penerbit = [
            [
                'nama_penerbit' => 'Erlangga',
                'kode_penerbit' => 'ERL',
                'alamat' => 'Jakarta',
                'telepon' => '021-123456',
                'email' => 'info@erlangga.co.id'
            ],
            [
                'nama_penerbit' => 'Yudhistira',
                'kode_penerbit' => 'YUD',
                'alamat' => 'Jakarta',
                'telepon' => '021-654321',
                'email' => 'info@yudhistira.co.id'
            ],
            [
                'nama_penerbit' => 'Gramedia',
                'kode_penerbit' => 'GRA',
                'alamat' => 'Jakarta',
                'telepon' => '021-789012',
                'email' => 'info@gramedia.co.id'
            ],
        ];

        foreach ($penerbit as $p) {
            Penerbit::create($p);
        }
    }
} 