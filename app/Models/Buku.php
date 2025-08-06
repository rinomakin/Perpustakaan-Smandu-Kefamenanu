<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul_buku',
        'isbn',
        'barcode',
        'penulis_id',
        'penerbit_id',
        'kategori_id',
        'jenis_id',
        'sumber_id',
        'tahun_terbit',
        'jumlah_halaman',
        'bahasa',
        'jumlah_stok',
        'stok_tersedia',
        'lokasi_rak',
        'gambar_sampul',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'jumlah_halaman' => 'integer',
        'jumlah_stok' => 'integer',
        'stok_tersedia' => 'integer',
    ];

    public function penulis()
    {
        return $this->belongsTo(Penulis::class);
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisBuku::class, 'jenis_id');
    }

    public function sumber()
    {
        return $this->belongsTo(SumberBuku::class, 'sumber_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    // Method untuk generate barcode otomatis
    public static function generateBarcode()
    {
        try {
            $prefix = 'BK';
            $lastBook = self::whereNotNull('barcode')
                        ->where('barcode', 'LIKE', $prefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();
            
            if ($lastBook && $lastBook->barcode) {
                $lastNumber = intval(substr($lastBook->barcode, 2));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            $barcode = $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
            
            // Cek apakah barcode sudah ada
            if (self::where('barcode', $barcode)->exists()) {
                throw new \Exception('Barcode sudah ada');
            }
            
            return $barcode;
        } catch (\Exception $e) {
            throw new \Exception('Gagal generate barcode: ' . $e->getMessage());
        }
    }

    // Method untuk generate nomor peminjaman
    public static function generateNomorPeminjaman()
    {
        $prefix = 'PJM';
        $date = now()->format('Ymd');
        $lastPeminjaman = self::where('nomor_peminjaman', 'LIKE', $prefix . $date . '%')
                              ->orderBy('id', 'desc')
                              ->first();
        
        if ($lastPeminjaman) {
            $lastNumber = intval(substr($lastPeminjaman->nomor_peminjaman, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
} 