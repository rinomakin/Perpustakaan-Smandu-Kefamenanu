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
        Schema::table('users', function (Blueprint $table) {
            // Drop the enum column and replace with foreign key
            $table->dropColumn('peran');
        });
        
        Schema::table('users', function (Blueprint $table) {
            // Add new foreign key column
            $table->unsignedBigInteger('peran_id')->nullable()->after('password');
            $table->foreign('peran_id')->references('id')->on('peran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['peran_id']);
            $table->dropColumn('peran_id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            // Restore original enum column
            $table->enum('peran', ['admin', 'kepala_sekolah', 'petugas'])->default('petugas')->after('password');
        });
    }
};
