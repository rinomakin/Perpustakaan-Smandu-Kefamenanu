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
        Schema::table('absensi_pengunjung', function (Blueprint $table) {
            // Add foreign key constraint for anggota_id
            $table->foreign('anggota_id')
                  ->references('id')
                  ->on('anggota')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Add foreign key constraint for petugas_id
            $table->foreign('petugas_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pengunjung', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['anggota_id']);
            $table->dropForeign(['petugas_id']);
        });
    }
};
