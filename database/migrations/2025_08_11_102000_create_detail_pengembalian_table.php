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
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->constrained('pengembalian')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->foreignId('detail_peminjaman_id')->constrained('detail_peminjaman')->onDelete('cascade');
            $table->enum('kondisi_kembali', ['baik', 'sedikit_rusak', 'rusak', 'hilang'])->default('baik');
            $table->integer('jumlah_dikembalikan')->default(1);
            $table->decimal('denda_buku', 10, 2)->default(0); // Denda khusus untuk buku ini
            $table->text('catatan_buku')->nullable();
            $table->timestamps();
            
            $table->index(['pengembalian_id', 'buku_id']);
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
