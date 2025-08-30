<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\AbsensiPengunjung;
use App\Models\Jurusan;
use App\Models\KategoriBuku;
use App\Models\JenisBuku;
use App\Models\Kelas;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnggotaExport;
use App\Exports\BukuExport;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use App\Exports\DendaExport;
use App\Exports\KasExport;
use App\Exports\AbsensiExport;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,KEPALA_SEKOLAH']);
    }

    public function index()
    {
        return view('admin.laporan.index');
    }

    public function anggota(Request $request)
    {
        $query = Anggota::with(['kelas.jurusan']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        
        // Filter berdasarkan jenis anggota
        if ($request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan jurusan
        if ($request->filled('jurusan_id')) {
            $query->whereHas('kelas', function($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }
        
        $anggota = $query->orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::all();
        $kelas = Kelas::with('jurusan')->get();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new AnggotaExport($anggota), 'laporan-anggota-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.anggota', compact('anggota', 'jurusan', 'kelas'));
    }

    public function buku(Request $request)
    {
        $query = Buku::with(['kategoriBuku', 'jenisBuku', 'rakBuku']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        // Filter berdasarkan jenis buku
        if ($request->filled('jenis_buku_id')) {
            $query->where('jenis_buku_id', $request->jenis_buku_id);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status == 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->status == 'dipinjam') {
                $query->where('stok', 0);
            }
        }
        
        $buku = $query->orderBy('created_at', 'desc')->get();
        $kategori = KategoriBuku::all();
        $jenis = JenisBuku::all();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new BukuExport($buku), 'laporan-buku-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.buku', compact('buku', 'kategori', 'jenis'));
    }

    public function kas(Request $request)
    {
        // Untuk laporan kas, kita akan menggunakan data denda yang sudah dibayar
        $query = Denda::with(['peminjaman.anggota.kelas.jurusan', 'peminjaman.detailPeminjaman.buku.kategoriBuku', 'user'])
                     ->where('status', 'sudah_bayar');
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_bayar', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        
        $kas = $query->orderBy('tanggal_bayar', 'desc')->get();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new KasExport($kas), 'laporan-kas-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.kas', compact('kas'));
    }

    public function peminjaman(Request $request)
    {
        $query = Peminjaman::with(['anggota.kelas.jurusan', 'detailPeminjaman.buku.kategoriBuku', 'user']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pinjam', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new PeminjamanExport($peminjaman), 'laporan-peminjaman-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.peminjaman', compact('peminjaman'));
    }

    public function pengembalian(Request $request)
    {
        $query = Pengembalian::with(['anggota.kelas.jurusan', 'detailPengembalian.buku.kategoriBuku', 'user', 'peminjaman']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pengembalian', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        
        $pengembalian = $query->orderBy('tanggal_pengembalian', 'desc')->get();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new PengembalianExport($pengembalian), 'laporan-pengembalian-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.pengembalian', compact('pengembalian'));
    }

    public function denda(Request $request)
    {
        $query = Denda::with(['peminjaman.anggota.kelas.jurusan', 'peminjaman.detailPeminjaman.buku.kategoriBuku', 'user']);
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            if ($request->filled('status') && $request->status === 'sudah_bayar') {
                $query->whereBetween('tanggal_bayar', [$request->tanggal_mulai, $request->tanggal_akhir]);
            } else {
                $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir]);
            }
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $denda = $query->orderBy('created_at', 'desc')->get();
        
        // Export to Excel if requested
        if ($request->filled('export') && $request->export === 'excel') {
            return Excel::download(new DendaExport($denda), 'laporan-denda-' . date('Y-m-d') . '.xlsx');
        }
        
        return view('admin.laporan.denda', compact('denda'));
    }

    public function absensi()
    {
        $query = AbsensiPengunjung::query();
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('tanggal', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        // Filter berdasarkan jenis pengunjung
        if (request('jenis')) {
            $query->where('jenis_pengunjung', request('jenis'));
        }
        
        $absensi = $query->get();
        
        return view('admin.laporan.absensi', compact('absensi'));
    }
} 