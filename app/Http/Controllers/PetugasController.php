<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiPengunjung;
use App\Models\PengaturanWebsite;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:petugas');
    }

    public function dashboard()
    {
        $totalPengunjungHariIni = AbsensiPengunjung::whereDate('created_at', today())->count();
        $pengunjungMasuk = AbsensiPengunjung::whereDate('created_at', today())
            ->where('status', 'masuk')
            ->count();
        $pengunjungKeluar = AbsensiPengunjung::whereDate('created_at', today())
            ->where('status', 'keluar')
            ->count();
            
        return view('petugas.dashboard', compact('totalPengunjungHariIni', 'pengunjungMasuk', 'pengunjungKeluar'));
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