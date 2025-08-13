<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DendaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,PETUGAS']);
    }

    public function index()
    {
        $denda = Denda::with(['peminjaman.detailPeminjaman.buku', 'anggota.kelas', 'anggota.jurusan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistik denda
        $totalDenda = Denda::sum('jumlah_denda');
        $dendaBelumDibayar = Denda::where('status_pembayaran', 'belum_dibayar')->sum('jumlah_denda');
        $dendaSudahDibayar = Denda::where('status_pembayaran', 'sudah_dibayar')->sum('jumlah_denda');
        $totalDendaHariIni = Denda::whereDate('created_at', today())->sum('jumlah_denda');

        return view('admin.denda.index', compact('denda', 'totalDenda', 'dendaBelumDibayar', 'dendaSudahDibayar', 'totalDendaHariIni'));
    }

    public function create()
    {
        // Ambil peminjaman yang terlambat atau belum dikembalikan
        $peminjamanTerlambat = Peminjaman::with(['anggota.kelas', 'anggota.jurusan', 'detailPeminjaman.buku'])
            ->where('status', 'dipinjam')
            ->where('tanggal_harus_kembali', '<', now())
            ->get();

        $anggota = Anggota::where('status', 'aktif')->get();
        
        return view('admin.denda.create', compact('peminjamanTerlambat', 'anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'jumlah_hari_terlambat' => 'required|integer|min:1',
            'jumlah_denda' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
            'status_pembayaran' => 'required|in:belum_dibayar,sudah_dibayar',
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,sudah_dibayar',
        ]);

        // Ambil data peminjaman
        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        
        // Cek apakah sudah ada denda untuk peminjaman ini
        $existingDenda = Denda::where('peminjaman_id', $request->peminjaman_id)->first();
        
        if ($existingDenda) {
            return back()->with('error', 'Denda untuk peminjaman ini sudah ada.')
                ->withInput();
        }

        Denda::create([
            'peminjaman_id' => $request->peminjaman_id,
            'anggota_id' => $peminjaman->anggota_id,
            'jumlah_hari_terlambat' => $request->jumlah_hari_terlambat,
            'jumlah_denda' => $request->jumlah_denda,
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_pembayaran' => $request->status_pembayaran === 'sudah_dibayar' ? $request->tanggal_pembayaran : null,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Data denda berhasil ditambahkan.');
    }

    public function show($id)
    {
        $denda = Denda::with(['peminjaman.detailPeminjaman.buku', 'anggota.kelas', 'anggota.jurusan'])
            ->findOrFail($id);
            
        return view('admin.denda.show', compact('denda'));
    }

    public function edit($id)
    {
        $denda = Denda::with(['peminjaman.detailPeminjaman.buku', 'anggota.kelas', 'anggota.jurusan'])
            ->findOrFail($id);
            
        $peminjamanTerlambat = Peminjaman::with(['anggota.kelas', 'anggota.jurusan', 'detailPeminjaman.buku'])
            ->where('status', 'dipinjam')
            ->where('tanggal_harus_kembali', '<', now())
            ->get();

        $anggota = Anggota::where('status', 'aktif')->get();
        
        return view('admin.denda.edit', compact('denda', 'peminjamanTerlambat', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);
        
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'jumlah_hari_terlambat' => 'required|integer|min:1',
            'jumlah_denda' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
            'status_pembayaran' => 'required|in:belum_dibayar,sudah_dibayar',
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,sudah_dibayar',
        ]);

        $denda->update([
            'peminjaman_id' => $request->peminjaman_id,
            'jumlah_hari_terlambat' => $request->jumlah_hari_terlambat,
            'jumlah_denda' => $request->jumlah_denda,
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_pembayaran' => $request->status_pembayaran === 'sudah_dibayar' ? $request->tanggal_pembayaran : null,
            'catatan' => $request->catatan,
        ]);
        
        return redirect()->route('admin.denda.index')
            ->with('success', 'Data denda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();
        
        return redirect()->route('admin.denda.index')
            ->with('success', 'Data denda berhasil dihapus.');
    }

    /**
     * Hitung denda otomatis berdasarkan peminjaman
     */
    public function hitungDenda(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        
        // Hitung hari terlambat
        $tanggalHarusKembali = Carbon::parse($peminjaman->tanggal_harus_kembali);
        $tanggalSekarang = Carbon::now();
        
        $jumlahHariTerlambat = $tanggalSekarang->diffInDays($tanggalHarusKembali, false);
        
        if ($jumlahHariTerlambat <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman belum terlambat'
            ]);
        }

        // Hitung denda (misalnya Rp 1000 per hari)
        $tarifDendaPerHari = 1000; // Bisa disesuaikan dengan kebijakan
        $jumlahDenda = $jumlahHariTerlambat * $tarifDendaPerHari;

        return response()->json([
            'success' => true,
            'data' => [
                'jumlah_hari_terlambat' => $jumlahHariTerlambat,
                'jumlah_denda' => $jumlahDenda,
                'tanggal_harus_kembali' => $peminjaman->tanggal_harus_kembali,
                'anggota' => [
                    'nama' => $peminjaman->anggota->nama_lengkap,
                    'nomor_anggota' => $peminjaman->anggota->nomor_anggota,
                    'kelas' => $peminjaman->anggota->kelas ? $peminjaman->anggota->kelas->nama_kelas : '-',
                ]
            ]
        ]);
    }

    /**
     * Update status pembayaran denda
     */
    public function updateStatusPembayaran(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_dibayar,sudah_dibayar',
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,sudah_dibayar',
        ]);

        $denda = Denda::findOrFail($id);
        
        $denda->update([
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_pembayaran' => $request->status_pembayaran === 'sudah_dibayar' ? $request->tanggal_pembayaran : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran denda berhasil diperbarui'
        ]);
    }

    /**
     * Cari denda berdasarkan anggota
     */
    public function searchDenda(Request $request)
    {
        $query = Denda::with(['peminjaman.detailPeminjaman.buku', 'anggota.kelas', 'anggota.jurusan']);

        if ($request->anggota) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->anggota . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->anggota . '%');
            });
        }

        if ($request->status_pembayaran) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->tanggal_mulai) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->tanggal_selesai) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        $denda = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $denda->items(),
            'pagination' => [
                'current_page' => $denda->currentPage(),
                'last_page' => $denda->lastPage(),
                'per_page' => $denda->perPage(),
                'total' => $denda->total()
            ]
        ]);
    }
} 