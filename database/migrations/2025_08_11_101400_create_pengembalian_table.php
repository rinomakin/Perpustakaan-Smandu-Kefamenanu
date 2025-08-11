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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengembalian')->unique();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Petugas yang memproses pengembalian
            $table->date('tanggal_pengembalian');
            $table->time('jam_pengembalian')->nullable();
            $table->integer('jumlah_hari_terlambat')->default(0);
            $table->decimal('total_denda', 10, 2)->default(0);
            $table->enum('status_denda', ['tidak_ada', 'belum_dibayar', 'sudah_dibayar'])->default('tidak_ada');
            $table->date('tanggal_pembayaran_denda')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['diproses', 'selesai', 'dibatalkan'])->default('diproses');
            $table->timestamps();
            
            $table->index('nomor_pengembalian');
            $table->index('tanggal_pengembalian');
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
