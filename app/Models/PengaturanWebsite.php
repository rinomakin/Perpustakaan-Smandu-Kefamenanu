<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanWebsite extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_website';

    protected $fillable = [
        'nama_website',
        'logo',
        'favicon',
        'deskripsi_website',
        'alamat_sekolah',
        'telepon_sekolah',
        'email_sekolah',
        'nama_kepala_sekolah',
        'visi_sekolah',
        'misi_sekolah',
        'sejarah_sekolah',
        'jam_operasional',
        'kebijakan_perpustakaan',
    ];
} 