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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable(); // Added nama_panggilan field
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('peran_id')->nullable(); // Changed from enum to foreign key
            $table->string('nomor_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable(); // Added foto field
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
