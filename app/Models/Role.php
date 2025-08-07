<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'peran';
    
    protected $fillable = [
        'nama_peran',
        'kode_peran',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relationship dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'peran', 'kode_peran');
    }

    // Scope untuk role aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk role nonaktif
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }
}
