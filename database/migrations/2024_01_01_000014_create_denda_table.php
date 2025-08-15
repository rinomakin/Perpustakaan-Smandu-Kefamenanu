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
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_id'); // Remove foreign key constraint
            $table->unsignedBigInteger('pengembalian_id')->nullable(); // Added pengembalian_id field
            $table->unsignedBigInteger('anggota_id'); // Remove foreign key constraint
            $table->integer('jumlah_hari_terlambat');
            $table->decimal('jumlah_denda', 10, 2);
            $table->enum('status_pembayaran', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
}; 