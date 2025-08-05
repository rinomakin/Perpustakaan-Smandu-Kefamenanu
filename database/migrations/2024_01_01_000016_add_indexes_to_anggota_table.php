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
        Schema::table('anggota', function (Blueprint $table) {
            // Add composite index for better import performance
            $table->index(['jenis_anggota', 'status'], 'anggota_jenis_status_index');
            
            // Add index for email for faster email validation
            $table->index('email', 'anggota_email_index');
            
            // Add index for kelas_id for faster foreign key lookups
            $table->index('kelas_id', 'anggota_kelas_id_index');
            
            // Add index for tanggal_bergabung for date range queries
            $table->index('tanggal_bergabung', 'anggota_tanggal_bergabung_index');
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropIndex('anggota_jenis_status_index');
            $table->dropIndex('anggota_email_index');
            $table->dropIndex('anggota_kelas_id_index');
            $table->dropIndex('anggota_tanggal_bergabung_index');
        });
    }
}; 