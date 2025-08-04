<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBuku extends Model
{
    use HasFactory;

    protected $table = 'jenis_buku';

    protected $fillable = [
        'nama_jenis',
        'kode_jenis',
        'deskripsi',
        'status',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'jenis_id');
    }
} 