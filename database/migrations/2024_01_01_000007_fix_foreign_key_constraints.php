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
        // Fix users table foreign key constraint
        if (Schema::hasTable('users') && Schema::hasTable('peran')) {
            // Drop existing foreign key if exists
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['peran_id']);
                });
            } catch (Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Add foreign key constraint
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('peran_id')->references('id')->on('peran')->onDelete('set null');
            });
        }
        
        // Fix role_permissions table if it doesn't exist
        if (!Schema::hasTable('role_permissions') && Schema::hasTable('peran') && Schema::hasTable('permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('peran')->onDelete('cascade');
                $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
                $table->timestamps();

                // Unique constraint untuk mencegah duplikasi
                $table->unique(['role_id', 'permission_id']);
                
                // Index untuk performance
                $table->index('role_id');
                $table->index('permission_id');
            });
        }
        
        // Fix buku table foreign key constraints
        if (Schema::hasTable('buku')) {
            // Add foreign key constraints for buku table
            if (Schema::hasTable('kategori_buku')) {
                try {
                    Schema::table('buku', function (Blueprint $table) {
                        $table->dropForeign(['kategori_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('buku', function (Blueprint $table) {
                    $table->foreign('kategori_id')->references('id')->on('kategori_buku')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('jenis_buku')) {
                try {
                    Schema::table('buku', function (Blueprint $table) {
                        $table->dropForeign(['jenis_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('buku', function (Blueprint $table) {
                    $table->foreign('jenis_id')->references('id')->on('jenis_buku')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('sumber_buku')) {
                try {
                    Schema::table('buku', function (Blueprint $table) {
                        $table->dropForeign(['sumber_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('buku', function (Blueprint $table) {
                    $table->foreign('sumber_id')->references('id')->on('sumber_buku')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('rak_buku')) {
                try {
                    Schema::table('buku', function (Blueprint $table) {
                        $table->dropForeign(['rak_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('buku', function (Blueprint $table) {
                    $table->foreign('rak_id')->references('id')->on('rak_buku')->onDelete('set null');
                });
            }
        }
        
        // Fix denda table foreign key constraints
        if (Schema::hasTable('denda')) {
            // Add foreign key constraints for denda table
            if (Schema::hasTable('peminjaman')) {
                try {
                    Schema::table('denda', function (Blueprint $table) {
                        $table->dropForeign(['peminjaman_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('denda', function (Blueprint $table) {
                    $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('anggota')) {
                try {
                    Schema::table('denda', function (Blueprint $table) {
                        $table->dropForeign(['anggota_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('denda', function (Blueprint $table) {
                    $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('pengembalian')) {
                try {
                    Schema::table('denda', function (Blueprint $table) {
                        $table->dropForeign(['pengembalian_id']);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                Schema::table('denda', function (Blueprint $table) {
                    $table->foreign('pengembalian_id')->references('id')->on('pengembalian')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key from users table
        if (Schema::hasTable('users')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['peran_id']);
                });
            } catch (Exception $e) {
                // Foreign key doesn't exist, continue
            }
        }
        
        // Drop role_permissions table
        if (Schema::hasTable('role_permissions')) {
            Schema::dropIfExists('role_permissions');
        }
        
        // Drop foreign keys from buku table
        if (Schema::hasTable('buku')) {
            $foreignKeys = ['kategori_id', 'jenis_id', 'sumber_id', 'rak_id'];
            foreach ($foreignKeys as $foreignKey) {
                try {
                    Schema::table('buku', function (Blueprint $table) use ($foreignKey) {
                        $table->dropForeign([$foreignKey]);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
            }
        }
        
        // Drop foreign keys from denda table
        if (Schema::hasTable('denda')) {
            $foreignKeys = ['peminjaman_id', 'anggota_id', 'pengembalian_id'];
            foreach ($foreignKeys as $foreignKey) {
                try {
                    Schema::table('denda', function (Blueprint $table) use ($foreignKey) {
                        $table->dropForeign([$foreignKey]);
                    });
                } catch (Exception $e) {
                    // Foreign key doesn't exist, continue
                }
            }
        }
    }
};
