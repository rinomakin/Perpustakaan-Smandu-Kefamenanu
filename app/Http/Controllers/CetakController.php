<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Buku;
use App\Helpers\BarcodeHelper;

class CetakController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }

    public function kartuAnggota($id)
    {
        $anggota = Anggota::with('kelas')->findOrFail($id);
        return view('admin.cetak.kartu-anggota', compact('anggota'));
    }

    public function labelBuku($id)
    {
        $buku = Buku::with(['kategori'])->findOrFail($id);
        return view('admin.cetak.label-buku', compact('buku'));
    }
} 