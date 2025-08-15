<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPengunjung extends Model
{
    use HasFactory;

    protected $table = 'absensi_pengunjung';

    protected $fillable = [
        'anggota_id',
        'nama_pengunjung',
        'tujuan_kunjungan',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'catatan',
        'keterangan',
        'petugas_id',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
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