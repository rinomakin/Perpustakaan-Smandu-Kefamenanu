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
        Schema::create('pengaturan_website', function (Blueprint $table) {
            $table->id();
            $table->string('nama_website');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('deskripsi_website')->nullable();
            $table->string('alamat_sekolah');
            $table->string('telepon_sekolah');
            $table->string('email_sekolah');
            $table->string('nama_kepala_sekolah');
            $table->text('visi_sekolah');
            $table->text('misi_sekolah');
            $table->text('sejarah_sekolah');
            $table->string('jam_operasional');
            $table->text('kebijakan_perpustakaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_website');
    }
}; 