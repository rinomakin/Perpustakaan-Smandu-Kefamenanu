<?php

// Script untuk mengecek data master yang diperlukan
// Jalankan dengan: php check_master_data.php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\KategoriBuku;
use App\Models\JenisBuku;
use App\Models\SumberBuku;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Pengecekan Data Master untuk Buku ===\n\n";

// Cek tabel penulis
echo "1. Cek Tabel Penulis:\n";
try {
    $penulisCount = Penulis::count();
    echo "   - Jumlah penulis: {$penulisCount}\n";
    if ($penulisCount > 0) {
        $penulis = Penulis::take(3)->get();
        foreach ($penulis as $p) {
            echo "   - ID: {$p->id}, Nama: {$p->nama_penulis}\n";
        }
    } else {
        echo "   - ⚠️  Tidak ada data penulis!\n";
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n2. Cek Tabel Penerbit:\n";
try {
    $penerbitCount = Penerbit::count();
    echo "   - Jumlah penerbit: {$penerbitCount}\n";
    if ($penerbitCount > 0) {
        $penerbit = Penerbit::take(3)->get();
        foreach ($penerbit as $p) {
            echo "   - ID: {$p->id}, Nama: {$p->nama_penerbit}\n";
        }
    } else {
        echo "   - ⚠️  Tidak ada data penerbit!\n";
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n3. Cek Tabel Kategori Buku:\n";
try {
    $kategoriCount = KategoriBuku::count();
    echo "   - Jumlah kategori: {$kategoriCount}\n";
    if ($kategoriCount > 0) {
        $kategoris = KategoriBuku::take(3)->get();
        foreach ($kategoris as $k) {
            echo "   - ID: {$k->id}, Nama: {$k->nama_kategori}\n";
        }
    } else {
        echo "   - ⚠️  Tidak ada data kategori!\n";
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n4. Cek Tabel Jenis Buku:\n";
try {
    $jenisCount = JenisBuku::count();
    echo "   - Jumlah jenis: {$jenisCount}\n";
    if ($jenisCount > 0) {
        $jenis = JenisBuku::take(3)->get();
        foreach ($jenis as $j) {
            echo "   - ID: {$j->id}, Nama: {$j->nama_jenis}\n";
        }
    } else {
        echo "   - ⚠️  Tidak ada data jenis buku!\n";
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n5. Cek Tabel Sumber Buku:\n";
try {
    $sumberCount = SumberBuku::count();
    echo "   - Jumlah sumber: {$sumberCount}\n";
    if ($sumberCount > 0) {
        $sumber = SumberBuku::take(3)->get();
        foreach ($sumber as $s) {
            echo "   - ID: {$s->id}, Nama: {$s->nama_sumber}\n";
        }
    } else {
        echo "   - ⚠️  Tidak ada data sumber buku!\n";
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n6. Cek Tabel Buku:\n";
try {
    $bukuCount = DB::table('buku')->count();
    echo "   - Jumlah buku: {$bukuCount}\n";
    if ($bukuCount > 0) {
        $buku = DB::table('buku')->take(3)->get();
        foreach ($buku as $b) {
            echo "   - ID: {$b->id}, Judul: {$b->judul_buku}, Barcode: {$b->barcode}\n";
        }
    }
} catch (Exception $e) {
    echo "   - ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Kesimpulan ===\n";
$errors = [];

if (Penulis::count() == 0) $errors[] = "Tidak ada data penulis";
if (Penerbit::count() == 0) $errors[] = "Tidak ada data penerbit";
if (KategoriBuku::count() == 0) $errors[] = "Tidak ada data kategori buku";
if (JenisBuku::count() == 0) $errors[] = "Tidak ada data jenis buku";
if (SumberBuku::count() == 0) $errors[] = "Tidak ada data sumber buku";

if (empty($errors)) {
    echo "✅ Semua data master tersedia. Buku dapat ditambahkan.\n";
} else {
    echo "❌ Masalah ditemukan:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n💡 Solusi: Jalankan seeder untuk mengisi data master:\n";
    echo "   php artisan db:seed --class=PenulisSeeder\n";
    echo "   php artisan db:seed --class=PenerbitSeeder\n";
    echo "   php artisan db:seed --class=KategoriBukuSeeder\n";
    echo "   php artisan db:seed --class=JenisBukuSeeder\n";
    echo "   php artisan db:seed --class=SumberBukuSeeder\n";
}

echo "\n";
