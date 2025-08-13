<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id',
        'anggota_id',
        'jumlah_hari_terlambat',
        'jumlah_denda',
        'status_pembayaran',
        'tanggal_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_denda' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class, 'peminjaman_id', 'peminjaman_id');
    }
} 