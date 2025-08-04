<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'user'])->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->get();
        return view('admin.peminjaman.create', compact('anggota', 'buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_ids' => 'required|array',
            'buku_ids.*' => 'exists:buku,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::create([
            'nomor_peminjaman' => Peminjaman::generateNomorPeminjaman(),
            'anggota_id' => $request->anggota_id,
            'user_id' => auth()->id(),
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => 'dipinjam',
            'keterangan' => $request->keterangan,
        ]);

        // Create detail peminjaman for each book
        foreach ($request->buku_ids as $buku_id) {
            $peminjaman->detailPeminjaman()->create([
                'buku_id' => $buku_id,
                'return_condition' => 'baik',
            ]);

            // Update book stock
            $buku = Buku::find($buku_id);
            $buku->decrement('stok_tersedia');
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $anggota = Anggota::where('status', 'aktif')->get();
        return view('admin.peminjaman.edit', compact('peminjaman', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman->update($request->all());
        
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
} 