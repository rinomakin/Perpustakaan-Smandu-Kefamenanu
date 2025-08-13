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
            $table->string('nama_pengunjung')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pengunjung', function (Blueprint $table) {
            $table->string('nama_pengunjung')->nullable(false)->change();
        });
    }
};
