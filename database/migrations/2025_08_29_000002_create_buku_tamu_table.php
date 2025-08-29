<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel buku_tamu.
     */
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->onDelete('set null');
            $table->string('nama_tamu')->nullable(); // Nama tamu (jika bukan anggota)
            $table->string('instansi')->nullable(); // Instansi/sekolah asal tamu
            $table->string('keperluan'); // Keperluan/tujuan kunjungan
            $table->dateTime('waktu_datang'); // Waktu kedatangan
            $table->dateTime('waktu_pulang')->nullable(); // Waktu pulang
            $table->enum('status_kunjungan', ['datang', 'pulang'])->default('datang');
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->string('no_telepon')->nullable(); // Nomor telepon tamu
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi dengan menghapus tabel buku_tamu.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};