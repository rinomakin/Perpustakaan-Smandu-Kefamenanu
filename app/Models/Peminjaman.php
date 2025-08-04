<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'nomor_peminjaman',
        'anggota_id',
        'user_id',
        'tanggal_peminjaman',
        'tanggal_harus_kembali',
        'tanggal_kembali',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_harus_kembali' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function denda()
    {
        return $this->hasOne(Denda::class);
    }

    // Method untuk generate nomor peminjaman otomatis
    public static function generateNomorPeminjaman()
    {
        $prefix = 'PJM';
        $date = now()->format('Ymd');
        $lastLoan = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        $lastNumber = $lastLoan ? intval(substr($lastLoan->nomor_peminjaman, -3)) : 0;
        $newNumber = $lastNumber + 1;
        return $prefix . $date . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
} 