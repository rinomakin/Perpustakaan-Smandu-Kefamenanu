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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_anggota')->unique(); // Nomor anggota otomatis
            $table->string('barcode_anggota')->unique(); // Barcode kartu anggota
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(); // Added jenis_kelamin field
            $table->string('nik')->unique();
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->string('email')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('jabatan')->nullable(); // Untuk guru/staff
            $table->enum('jenis_anggota', ['siswa', 'guru', 'staff'])->default('siswa');
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'ditangguhkan'])->default('aktif');
            $table->date('tanggal_bergabung');
            $table->timestamps();
            
            $table->index('barcode_anggota');
            $table->index('nomor_anggota');
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
}; 