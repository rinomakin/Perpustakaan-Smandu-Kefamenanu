<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $table = 'penulis';

    protected $fillable = [
        'nama_penulis',
        'kode_penulis',
        'biografi',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'penulis_id');
    }
} 