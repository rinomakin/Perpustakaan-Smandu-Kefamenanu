<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class KepsekController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:KEPALA_SEKOLAH');
    }

    public function dashboard()
    {
        $totalAnggota = Anggota::count();
        $totalBuku = Buku::count();
        $totalPeminjaman = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda = Denda::where('status_pembayaran', 'belum_dibayar')->sum('jumlah_denda');
        
        $peminjamanBulanIni = Peminjaman::whereMonth('created_at', now()->month)->count();
        $pengembalianBulanIni = Peminjaman::whereMonth('tanggal_kembali', now()->month)->count();
        
        return view('kepsek.dashboard', compact(
            'totalAnggota',
            'totalBuku', 
            'totalPeminjaman',
            'totalDenda',
            'peminjamanBulanIni',
            'pengembalianBulanIni'
        ));
    }

    // Laporan method removed - kepala sekolah now uses admin laporan system

} 