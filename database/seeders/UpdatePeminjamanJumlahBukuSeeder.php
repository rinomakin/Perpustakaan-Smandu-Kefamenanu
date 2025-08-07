<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class UpdatePeminjamanJumlahBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update semua peminjaman yang sudah ada
        $peminjamanList = Peminjaman::all();
        
        foreach ($peminjamanList as $peminjaman) {
            // Hitung jumlah buku yang dipinjamkan
            $jumlahBuku = $peminjaman->detailPeminjaman()->count();
            
            // Update field jumlah_buku
            $peminjaman->update([
                'jumlah_buku' => $jumlahBuku
            ]);
        }
        
        $this->command->info('Berhasil mengupdate ' . count($peminjamanList) . ' data peminjaman dengan jumlah buku yang benar.');
    }
}
