<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    protected $table = 'buku_tamu';

    protected $fillable = [
        'anggota_id',
        'nama_tamu',
        'instansi',
        'keperluan',
        'waktu_datang',
        'waktu_pulang',
        'status_kunjungan',
        'keterangan',
        'no_telepon',
        'petugas_id',
    ];

    protected $casts = [
        'waktu_datang' => 'datetime',
        'waktu_pulang' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function petugas()
    {
        return $this->belongsTo(\App\Models\User::class, 'petugas_id');
    }
}