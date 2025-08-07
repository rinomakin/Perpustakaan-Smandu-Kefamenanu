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
        // Hapus foreign key constraints dari tabel buku
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['penulis_id']);
            $table->dropForeign(['penerbit_id']);
        });

        // Hapus kolom penulis_id dan penerbit_id dari tabel buku
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn(['penulis_id', 'penerbit_id']);
        });

        // Tambah kolom baru untuk penulis dan penerbit sebagai string
        Schema::table('buku', function (Blueprint $table) {
            $table->string('penulis')->nullable()->after('barcode');
            $table->string('penerbit')->nullable()->after('penulis');
        });

        // Hapus tabel penulis
        Schema::dropIfExists('penulis');

        // Hapus tabel penerbit
        Schema::dropIfExists('penerbit');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Buat ulang tabel penerbit
        Schema::create('penerbit', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penerbit');
            $table->string('kode_penerbit')->unique();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Buat ulang tabel penulis
        Schema::create('penulis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penulis');
            $table->string('kode_penulis')->unique();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('biografi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Hapus kolom penulis dan penerbit dari tabel buku
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn(['penulis', 'penerbit']);
        });

        // Tambah kembali kolom foreign key
        Schema::table('buku', function (Blueprint $table) {
            $table->foreignId('penulis_id')->constrained('penulis')->onDelete('cascade');
            $table->foreignId('penerbit_id')->constrained('penerbit')->onDelete('cascade');
        });
    }
};
