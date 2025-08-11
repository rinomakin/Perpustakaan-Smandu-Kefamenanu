<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class RiwayatPengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,KEPALA_SEKOLAH']);
    }

    public function index(Request $request)
    {
        $query = Pengembalian::with(['anggota.kelas', 'user', 'detailPengembalian.buku.kategoriBuku', 'peminjaman.detailPeminjaman.buku']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_pengembalian', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal_pengembalian', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan jam
        if ($request->filled('jam_mulai')) {
            $query->where('jam_pengembalian', '>=', $request->jam_mulai);
        }

        if ($request->filled('jam_akhir')) {
            $query->where('jam_pengembalian', '<=', $request->jam_akhir);
        }

        // Filter berdasarkan status terlambat
        if ($request->filled('status_terlambat')) {
            if ($request->status_terlambat == 'terlambat') {
                $query->where('jumlah_hari_terlambat', '>', 0);
            } else {
                $query->where('jumlah_hari_terlambat', '=', 0);
            }
        }

        // Filter berdasarkan anggota
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        // Filter berdasarkan buku
        if ($request->filled('buku_id')) {
            $query->whereHas('detailPengembalian', function($q) use ($request) {
                $q->where('buku_id', $request->buku_id);
            });
        }

        // Pencarian berdasarkan nomor pengembalian atau nama anggota
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengembalian', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nomor_anggota', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tanggal_pengembalian');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pengembalian = $query->paginate(15);

        // Data untuk filter
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::all();

        return view('admin.riwayat-pengembalian.index', compact('pengembalian', 'anggota', 'buku'));
    }

    public function export(Request $request)
    {
        $query = Pengembalian::with(['anggota.kelas', 'user', 'detailPengembalian.buku.kategoriBuku', 'peminjaman.detailPeminjaman.buku']);

        // Apply same filters as index method
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_pengembalian', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal_pengembalian', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status_terlambat')) {
            if ($request->status_terlambat == 'terlambat') {
                $query->where('jumlah_hari_terlambat', '>', 0);
            } else {
                $query->where('jumlah_hari_terlambat', '=', 0);
            }
        }

        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        if ($request->filled('buku_id')) {
            $query->whereHas('detailPengembalian', function($q) use ($request) {
                $q->where('buku_id', $request->buku_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengembalian', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nomor_anggota', 'like', "%{$search}%");
                  });
            });
        }

        $pengembalian = $query->get();

        // Generate CSV
        $filename = 'riwayat_pengembalian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pengembalian) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No', 'Nomor Pengembalian', 'Nama Anggota', 'Nomor Anggota', 
                'Tanggal Pengembalian', 'Jam Pengembalian', 'Jumlah Buku',
                'Jumlah Hari Terlambat', 'Total Denda', 'Petugas', 'Catatan'
            ]);

            $no = 1;
            foreach ($pengembalian as $return) {
                fputcsv($file, [
                    $no++,
                    $return->nomor_pengembalian,
                    $return->anggota->nama_lengkap,
                    $return->anggota->nomor_anggota,
                    $return->tanggal_pengembalian ? $return->tanggal_pengembalian->format('d/m/Y') : '',
                    $return->jam_pengembalian ? $return->jam_pengembalian->format('H:i') : '',
                    $return->jumlah_buku,
                    $return->jumlah_hari_terlambat,
                    $return->total_denda,
                    $return->user->name ?? '',
                    $return->catatan ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
