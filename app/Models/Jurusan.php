<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
        'deskripsi',
        'status',
    ];

    // Accessor untuk konversi status ke boolean
    public function getStatusAttribute($value)
    {
        return $value === 'aktif' || $value == 1 ? 1 : 0;
    }

    // Mutator untuk konversi boolean ke string
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value == 1 ? 'aktif' : 'nonaktif';
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
} 