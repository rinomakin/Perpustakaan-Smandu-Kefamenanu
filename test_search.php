<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Anggota;

// Test the search functionality
try {
    $query = 'rd';
    
    $anggota = Anggota::where('status', 'aktif')
                      ->where(function($q) use ($query) {
                          $q->where('nama_lengkap', 'LIKE', "%{$query}%")
                            ->orWhere('nomor_anggota', 'LIKE', "%{$query}%")
                            ->orWhere('barcode_anggota', 'LIKE', "%{$query}%");
                      })
                      ->with('kelas')
                      ->take(10)
                      ->get()
                      ->map(function($anggota) {
                          return [
                              'id' => $anggota->id,
                              'nama_lengkap' => $anggota->nama_lengkap,
                              'nomor_anggota' => $anggota->nomor_anggota,
                              'barcode_anggota' => $anggota->barcode_anggota,
                              'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                              'jenis_anggota' => $anggota->jenis_anggota
                          ];
                      });

    echo "Search successful!\n";
    echo "Found " . $anggota->count() . " results\n";
    
    foreach ($anggota as $member) {
        echo "- " . $member['nama_lengkap'] . " (" . $member['nomor_anggota'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
