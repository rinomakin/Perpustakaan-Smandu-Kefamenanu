<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rak_buku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rak', 100);
            $table->string('kode_rak', 20)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->integer('kapasitas')->default(0);
            $table->integer('jumlah_buku')->default(0);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rak_buku');
    }
};
