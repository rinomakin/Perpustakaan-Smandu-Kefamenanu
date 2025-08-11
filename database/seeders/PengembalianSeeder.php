<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use Carbon\Carbon;

class PengembalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa peminjaman yang sudah ada
        $peminjamans = Peminjaman::with(['detailPeminjaman.buku', 'anggota'])
            ->where('status', 'dipinjam')
            ->take(5)
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->command->info('Tidak ada data peminjaman yang tersedia untuk membuat pengembalian.');
            return;
        }

        // Ambil user petugas
        $petugas = User::where('role_id', 2)->first(); // Asumsi role_id 2 adalah petugas
        if (!$petugas) {
            $petugas = User::first();
        }

        foreach ($peminjamans as $peminjaman) {
            // Hitung hari terlambat
            $tanggalHarusKembali = Carbon::parse($peminjaman->tanggal_harus_kembali);
            $tanggalPengembalian = Carbon::now();
            $hariTerlambat = max(0, $tanggalPengembalian->diffInDays($tanggalHarusKembali, false));
            
            // Hitung denda (asumsi 1000 per hari)
            $dendaPerHari = 1000;
            $totalDenda = $hariTerlambat * $dendaPerHari;

            // Buat pengembalian
            $pengembalian = Pengembalian::create([
                'nomor_pengembalian' => Pengembalian::generateNomorPengembalian(),
                'peminjaman_id' => $peminjaman->id,
                'anggota_id' => $peminjaman->anggota_id,
                'user_id' => $petugas->id,
                'tanggal_pengembalian' => $tanggalPengembalian->toDateString(),
                'jam_pengembalian' => $tanggalPengembalian->toTimeString(),
                'jumlah_hari_terlambat' => $hariTerlambat,
                'total_denda' => $totalDenda,
                'status_denda' => $totalDenda > 0 ? 'belum_dibayar' : 'tidak_ada',
                'status' => 'selesai',
                'catatan' => 'Pengembalian buku tepat waktu'
            ]);

            // Buat detail pengembalian untuk setiap buku
            foreach ($peminjaman->detailPeminjaman as $detailPeminjaman) {
                $kondisiKembali = $this->getRandomKondisi();
                $dendaBuku = $this->calculateDendaBuku($kondisiKembali, $hariTerlambat, $dendaPerHari);
                
                DetailPengembalian::create([
                    'pengembalian_id' => $pengembalian->id,
                    'buku_id' => $detailPeminjaman->buku_id,
                    'detail_peminjaman_id' => $detailPeminjaman->id,
                    'kondisi_kembali' => $kondisiKembali,
                    'jumlah_dikembalikan' => $detailPeminjaman->jumlah ?? 1,
                    'denda_buku' => $dendaBuku,
                    'catatan_buku' => $this->getCatatanBuku($kondisiKembali)
                ]);
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $tanggalPengembalian->toDateString(),
                'jam_kembali' => $tanggalPengembalian->toTimeString()
            ]);
        }

        $this->command->info('Seeder Pengembalian berhasil dijalankan!');
    }

    /**
     * Mendapatkan kondisi buku secara random
     */
    private function getRandomKondisi(): string
    {
        $kondisi = ['baik', 'baik', 'baik', 'sedikit_rusak', 'rusak']; // 60% baik, 20% sedikit rusak, 20% rusak
        return $kondisi[array_rand($kondisi)];
    }

    /**
     * Menghitung denda buku berdasarkan kondisi
     */
    private function calculateDendaBuku(string $kondisi, int $hariTerlambat, int $dendaPerHari): float
    {
        $denda = $hariTerlambat * $dendaPerHari;
        
        // Tambahan denda berdasarkan kondisi buku
        switch ($kondisi) {
            case 'sedikit_rusak':
                $denda += 5000; // Denda tambahan untuk buku sedikit rusak
                break;
            case 'rusak':
                $denda += 25000; // Denda tambahan untuk buku rusak
                break;
            case 'hilang':
                $denda += 100000; // Denda tambahan untuk buku hilang
                break;
        }
        
        return $denda;
    }

    /**
     * Mendapatkan catatan buku berdasarkan kondisi
     */
    private function getCatatanBuku(string $kondisi): ?string
    {
        return match($kondisi) {
            'baik' => 'Buku dalam kondisi baik',
            'sedikit_rusak' => 'Buku sedikit rusak pada bagian cover',
            'rusak' => 'Buku rusak pada beberapa halaman',
            'hilang' => 'Buku tidak ditemukan',
            default => null
        };
    }
}
