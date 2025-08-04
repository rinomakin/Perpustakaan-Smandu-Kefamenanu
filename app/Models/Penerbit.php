<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    use HasFactory;

    protected $table = 'penerbit';

    protected $fillable = [
        'nama_penerbit',
        'kode_penerbit',
        'alamat',
        'telepon',
        'email',
        'status',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'penerbit_id');
    }
} 