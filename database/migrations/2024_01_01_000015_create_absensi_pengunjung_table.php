<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('absensi_pengunjung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->onDelete('set null');
            $table->string('nama_pengunjung'); // Nama pengunjung (jika bukan anggota)
            $table->string('tujuan_kunjungan'); // Tujuan kunjungan
            $table->time('waktu_masuk');
            $table->time('waktu_keluar')->nullable();
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_pengunjung');
    }
}; 