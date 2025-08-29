<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuTamu;
use App\Models\PengaturanWebsite;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:PETUGAS');
    }

    public function dashboard()
    {
        $totalTamuHariIni = BukuTamu::whereDate('created_at', today())->count();
        $tamuDatang = BukuTamu::whereDate('created_at', today())
            ->where('status_kunjungan', 'datang')
            ->count();
        $tamuPulang = BukuTamu::whereDate('created_at', today())
            ->where('status_kunjungan', 'pulang')
            ->count();
            
        return view('petugas.dashboard', compact('totalTamuHariIni', 'tamuDatang', 'tamuPulang'));
    }

    public function beranda()
    {
        $pengaturan = PengaturanWebsite::first();
        return view('petugas.beranda', compact('pengaturan'));
    }

    public function tentang()
    {
        $pengaturan = PengaturanWebsite::first();
        return view('petugas.tentang', compact('pengaturan'));
    }
} 