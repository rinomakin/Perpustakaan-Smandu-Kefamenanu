<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\KategoriBuku;
use App\Models\JenisBuku;
use App\Models\SumberBuku;
use App\Models\Buku;

class CheckBukuData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buku:check-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check master data availability for buku';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Pengecekan Data Master untuk Buku ===');
        $this->newLine();

        $errors = [];

        // Cek tabel penulis
        $this->info('1. Cek Tabel Penulis:');
        try {
            $penulisCount = Penulis::count();
            $this->line("   - Jumlah penulis: {$penulisCount}");
            if ($penulisCount > 0) {
                $penulis = Penulis::take(3)->get();
                foreach ($penulis as $p) {
                    $this->line("   - ID: {$p->id}, Nama: {$p->nama_penulis}");
                }
            } else {
                $this->warn("   - ⚠️  Tidak ada data penulis!");
                $errors[] = "Tidak ada data penulis";
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel penulis: " . $e->getMessage();
        }

        $this->newLine();

        // Cek tabel penerbit
        $this->info('2. Cek Tabel Penerbit:');
        try {
            $penerbitCount = Penerbit::count();
            $this->line("   - Jumlah penerbit: {$penerbitCount}");
            if ($penerbitCount > 0) {
                $penerbit = Penerbit::take(3)->get();
                foreach ($penerbit as $p) {
                    $this->line("   - ID: {$p->id}, Nama: {$p->nama_penerbit}");
                }
            } else {
                $this->warn("   - ⚠️  Tidak ada data penerbit!");
                $errors[] = "Tidak ada data penerbit";
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel penerbit: " . $e->getMessage();
        }

        $this->newLine();

        // Cek tabel kategori buku
        $this->info('3. Cek Tabel Kategori Buku:');
        try {
            $kategoriCount = KategoriBuku::count();
            $this->line("   - Jumlah kategori: {$kategoriCount}");
            if ($kategoriCount > 0) {
                $kategoris = KategoriBuku::take(3)->get();
                foreach ($kategoris as $k) {
                    $this->line("   - ID: {$k->id}, Nama: {$k->nama_kategori}");
                }
            } else {
                $this->warn("   - ⚠️  Tidak ada data kategori!");
                $errors[] = "Tidak ada data kategori buku";
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel kategori buku: " . $e->getMessage();
        }

        $this->newLine();

        // Cek tabel jenis buku
        $this->info('4. Cek Tabel Jenis Buku:');
        try {
            $jenisCount = JenisBuku::count();
            $this->line("   - Jumlah jenis: {$jenisCount}");
            if ($jenisCount > 0) {
                $jenis = JenisBuku::take(3)->get();
                foreach ($jenis as $j) {
                    $this->line("   - ID: {$j->id}, Nama: {$j->nama_jenis}");
                }
            } else {
                $this->warn("   - ⚠️  Tidak ada data jenis buku!");
                $errors[] = "Tidak ada data jenis buku";
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel jenis buku: " . $e->getMessage();
        }

        $this->newLine();

        // Cek tabel sumber buku
        $this->info('5. Cek Tabel Sumber Buku:');
        try {
            $sumberCount = SumberBuku::count();
            $this->line("   - Jumlah sumber: {$sumberCount}");
            if ($sumberCount > 0) {
                $sumber = SumberBuku::take(3)->get();
                foreach ($sumber as $s) {
                    $this->line("   - ID: {$s->id}, Nama: {$s->nama_sumber}");
                }
            } else {
                $this->warn("   - ⚠️  Tidak ada data sumber buku!");
                $errors[] = "Tidak ada data sumber buku";
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel sumber buku: " . $e->getMessage();
        }

        $this->newLine();

        // Cek tabel buku
        $this->info('6. Cek Tabel Buku:');
        try {
            $bukuCount = Buku::count();
            $this->line("   - Jumlah buku: {$bukuCount}");
            if ($bukuCount > 0) {
                $buku = Buku::take(3)->get();
                foreach ($buku as $b) {
                    $this->line("   - ID: {$b->id}, Judul: {$b->judul_buku}, Barcode: {$b->barcode}");
                }
            }
        } catch (\Exception $e) {
            $this->error("   - ❌ Error: " . $e->getMessage());
            $errors[] = "Error pada tabel buku: " . $e->getMessage();
        }

        $this->newLine();

        // Kesimpulan
        $this->info('=== Kesimpulan ===');
        if (empty($errors)) {
            $this->info('✅ Semua data master tersedia. Buku dapat ditambahkan.');
        } else {
            $this->error('❌ Masalah ditemukan:');
            foreach ($errors as $error) {
                $this->line("   - {$error}");
            }
            $this->newLine();
            $this->info('💡 Solusi: Jalankan seeder untuk mengisi data master:');
            $this->line('   php artisan db:seed --class=PenulisSeeder');
            $this->line('   php artisan db:seed --class=PenerbitSeeder');
            $this->line('   php artisan db:seed --class=KategoriBukuSeeder');
            $this->line('   php artisan db:seed --class=JenisBukuSeeder');
            $this->line('   php artisan db:seed --class=SumberBukuSeeder');
        }

        $this->newLine();
    }
}
