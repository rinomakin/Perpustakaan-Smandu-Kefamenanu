<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nomor_anggota',
        'barcode_anggota',
        'nama_lengkap',
        'nik',
        'alamat',
        'nomor_telepon',
        'email',
        'kelas_id',
        'jabatan',
        'jenis_anggota',
        'foto',
        'status',
        'tanggal_bergabung',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function denda()
    {
        return $this->hasMany(Denda::class);
    }

    public function absensiPengunjung()
    {
        return $this->hasMany(AbsensiPengunjung::class);
    }

    // Method untuk generate nomor anggota otomatis
    public static function generateNomorAnggota()
    {
        $prefix = 'AGT';
        $lastMember = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastMember ? intval(substr($lastMember->nomor_anggota, 3)) : 0;
        $newNumber = $lastNumber + 1;
        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Method untuk generate barcode anggota otomatis
    public static function generateBarcodeAnggota()
    {
        $prefix = 'BC';
        $lastMember = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastMember ? intval(substr($lastMember->barcode_anggota, 2)) : 0;
        $newNumber = $lastNumber + 1;
        return $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }
} 