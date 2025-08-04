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
        $prefix = 'BK';
        $lastBook = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastBook ? intval(substr($lastBook->barcode, 2)) : 0;
        $newNumber = $lastNumber + 1;
        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
} 