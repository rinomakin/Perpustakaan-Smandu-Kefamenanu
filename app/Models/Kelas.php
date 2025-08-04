<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'kode_kelas',
        'jurusan_id',
        'tahun_ajaran',
        'status',
    ];

    // Accessor untuk konversi status ke boolean
    public function getStatusAttribute($value)
    {
        return $value === 'aktif' ? 1 : 0;
    }

    // Mutator untuk konversi boolean ke string
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value == 1 ? 'aktif' : 'nonaktif';
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }
} 