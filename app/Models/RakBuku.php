<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakBuku extends Model
{
    use HasFactory;

    protected $table = 'rak_buku';
    
    protected $fillable = [
        'nama_rak',
        'kode_rak',
        'deskripsi',
        'lokasi',
        'kapasitas',
        'jumlah_buku',
        'status'
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'jumlah_buku' => 'integer',
    ];

    // Relationship dengan buku
    public function buku()
    {
        return $this->hasMany(Buku::class, 'rak_id');
    }

    // Scope untuk rak yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    // Method untuk mengecek apakah rak penuh
    public function isFull()
    {
        return $this->jumlah_buku >= $this->kapasitas;
    }

    // Method untuk mengecek kapasitas tersisa
    public function getSisaKapasitas()
    {
        return $this->kapasitas - $this->jumlah_buku;
    }

    // Method untuk update jumlah buku
    public function updateJumlahBuku()
    {
        $this->jumlah_buku = $this->buku()->count();
        $this->save();
    }
}
