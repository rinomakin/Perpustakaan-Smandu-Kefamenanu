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
use App\Models\User;

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
        $query = Anggota::with(['jurusan', 'kelas']);
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('created_at', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        // Filter berdasarkan jurusan
        if (request('jurusan')) {
            $query->where('jurusan_id', request('jurusan'));
        }
        
        $anggota = $query->get();
        $jurusan = Jurusan::all();
        
        return view('admin.laporan.anggota', compact('anggota', 'jurusan'));
    }

    public function buku()
    {
        $query = Buku::with(['kategori', 'jenisBuku', 'rakBuku']);
        
        // Filter berdasarkan kategori
        if (request('kategori')) {
            $query->where('kategori_id', request('kategori'));
        }
        
        // Filter berdasarkan jenis buku
        if (request('jenis')) {
            $query->where('jenis_buku_id', request('jenis'));
        }
        
        // Filter berdasarkan status
        if (request('status')) {
            if (request('status') == 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif (request('status') == 'dipinjam') {
                $query->where('stok', 0);
            }
        }
        
        $buku = $query->get();
        $kategori = KategoriBuku::all();
        $jenis = JenisBuku::all();
        
        return view('admin.laporan.buku', compact('buku', 'kategori', 'jenis'));
    }

    public function kas()
    {
        // Untuk laporan kas, kita akan menggunakan data denda yang sudah dibayar
        $query = Denda::with(['peminjaman.anggota', 'user'])
                     ->where('status', 'sudah_bayar');
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('tanggal_bayar', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        $kas = $query->get();
        
        return view('admin.laporan.kas', compact('kas'));
    }

    public function peminjaman()
    {
        $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('tanggal_pinjam', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        // Filter berdasarkan status
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        $peminjaman = $query->get();
        
        return view('admin.laporan.peminjaman', compact('peminjaman'));
    }

    public function pengembalian()
    {
        $query = Pengembalian::with(['peminjaman.anggota', 'detailPengembalian.buku', 'user']);
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('tanggal_kembali', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        $pengembalian = $query->get();
        
        return view('admin.laporan.pengembalian', compact('pengembalian'));
    }

    public function denda()
    {
        $query = Denda::with(['peminjaman.anggota', 'peminjaman.detailPeminjaman.buku']);
        
        // Filter berdasarkan tanggal
        if (request('tanggal_mulai') && request('tanggal_akhir')) {
            $query->whereBetween('tanggal_bayar', [request('tanggal_mulai'), request('tanggal_akhir')]);
        }
        
        // Filter berdasarkan status
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        $denda = $query->get();
        
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