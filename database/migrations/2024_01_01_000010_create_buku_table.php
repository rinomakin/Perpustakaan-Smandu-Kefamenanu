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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rak_id')->nullable(); // Added rak_id field without foreign key constraint
            $table->string('judul_buku');
            $table->string('isbn')->nullable();
            $table->string('barcode')->unique(); // Barcode unik untuk setiap buku
            $table->string('pengarang')->nullable(); // Pengarang sebagai string
            $table->string('penerbit')->nullable(); // Penerbit sebagai string
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('jenis_id');
            $table->unsignedBigInteger('sumber_id');
            $table->integer('tahun_terbit')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->string('bahasa')->default('Indonesia');
            $table->integer('jumlah_stok')->default(1);
            $table->integer('stok_tersedia')->default(1);
            $table->string('lokasi_rak')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'rusak'])->default('tersedia');
            $table->timestamps();
            
            $table->index('barcode');
            $table->index('isbn');
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
}; 