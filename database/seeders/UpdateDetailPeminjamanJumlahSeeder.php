<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;

class UpdateDetailPeminjamanJumlahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update semua detail peminjaman yang belum memiliki jumlah
        $updatedCount = DetailPeminjaman::whereNull('jumlah')->update(['jumlah' => 1]);
        
        $this->command->info("Updated {$updatedCount} detail peminjaman records with default jumlah = 1");
        
        // Update jumlah_buku di tabel peminjaman berdasarkan sum dari detail
        $peminjamanIds = DetailPeminjaman::select('peminjaman_id')
            ->groupBy('peminjaman_id')
            ->pluck('peminjaman_id');
            
        foreach ($peminjamanIds as $peminjamanId) {
            $totalJumlah = DetailPeminjaman::where('peminjaman_id', $peminjamanId)
                ->sum('jumlah');
                
            DB::table('peminjaman')
                ->where('id', $peminjamanId)
                ->update(['jumlah_buku' => $totalJumlah]);
        }
        
        $this->command->info("Updated jumlah_buku for " . count($peminjamanIds) . " peminjaman records");
    }
}
