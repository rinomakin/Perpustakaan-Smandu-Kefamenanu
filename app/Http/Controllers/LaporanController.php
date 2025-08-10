<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\AbsensiPengunjung;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }

    public function index()
    {
        return view('admin.laporan.index');
    }

    public function anggota()
    {
        $anggota = Anggota::with('kelas')->get();
        return view('admin.laporan.anggota', compact('anggota'));
    }

    public function buku()
    {
        $buku = Buku::with(['kategori'])->get();
        return view('admin.laporan.buku', compact('buku'));
    }

    public function kas()
    {
        $denda = Denda::all();
        $totalDenda = $denda->sum('jumlah_denda');
        $dendaDibayar = $denda->where('status_pembayaran', 'sudah_dibayar')->sum('jumlah_denda');
        $dendaBelumDibayar = $denda->where('status_pembayaran', 'belum_dibayar')->sum('jumlah_denda');
        
        return view('admin.laporan.kas', compact('denda', 'totalDenda', 'dendaDibayar', 'dendaBelumDibayar'));
    }

    public function peminjaman()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->get();
        return view('admin.laporan.peminjaman', compact('peminjaman'));
    }

    public function denda()
    {
        $denda = Denda::with(['anggota', 'peminjaman'])->get();
        return view('admin.laporan.denda', compact('denda'));
    }

    public function absensi()
    {
        $absensi = AbsensiPengunjung::all();
        return view('admin.laporan.absensi', compact('absensi'));
    }
} 