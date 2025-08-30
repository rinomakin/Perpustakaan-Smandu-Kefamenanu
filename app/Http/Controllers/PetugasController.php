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
        
        $totalBulanIni = BukuTamu::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Recent activities - tamu yang baru saja berkunjung hari ini
        $recentActivities = BukuTamu::whereDate('created_at', today())
            ->with(['anggota.kelas', 'anggota.jurusan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($activity) {
                return (object) [
                    'nama_pengunjung' => $activity->nama_tamu ?? ($activity->anggota ? $activity->anggota->nama_lengkap : 'Tamu Umum'),
                    'asal_instansi' => $activity->instansi ?? ($activity->anggota && $activity->anggota->kelas ? $activity->anggota->kelas->nama_kelas : 'Umum'),
                    'tujuan_kunjungan' => $activity->tujuan_kunjungan ?? 'Kunjungan perpustakaan',
                    'waktu_keluar' => $activity->waktu_keluar,
                    'created_at' => $activity->created_at
                ];
            });
            
        return view('petugas.dashboard', compact('totalTamuHariIni', 'tamuDatang', 'tamuPulang', 'totalBulanIni', 'recentActivities'));
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