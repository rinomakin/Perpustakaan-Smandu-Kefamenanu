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
            $table->string('nama_pengunjung')->nullable(); // Nama pengunjung (jika bukan anggota)
            $table->string('tujuan_kunjungan'); // Tujuan kunjungan
            $table->dateTime('waktu_masuk'); // Changed from time to datetime
            $table->dateTime('waktu_keluar')->nullable(); // Changed from time to datetime
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->text('catatan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
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