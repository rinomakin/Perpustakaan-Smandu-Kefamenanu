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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama permission yang human readable
            $table->string('slug')->unique(); // Slug untuk checking permission
            $table->text('description')->nullable(); // Deskripsi permission
            $table->string('group_name'); // Group untuk kategorisasi (e.g., 'Manajemen Buku', 'Manajemen User')
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->index(['slug', 'status']);
            $table->index('group_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
