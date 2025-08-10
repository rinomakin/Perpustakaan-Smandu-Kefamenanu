<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Anggota;

class DendaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }

    public function index()
    {
        $denda = Denda::with(['peminjaman', 'anggota'])->paginate(10);
        return view('admin.denda.index', compact('denda'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::where('status', 'terlambat')->get();
        $anggota = Anggota::where('status', 'aktif')->get();
        return view('admin.denda.create', compact('peminjaman', 'anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'anggota_id' => 'required|exists:anggota,id',
            'jumlah_denda' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status_pembayaran' => 'required|in:belum_dibayar,sudah_dibayar',
        ]);

        Denda::create($request->all());
        
        return redirect()->route('denda.index')
            ->with('success', 'Data denda berhasil ditambahkan.');
    }

    public function show($id)
    {
        $denda = Denda::with(['peminjaman', 'anggota'])->findOrFail($id);
        return view('admin.denda.show', compact('denda'));
    }

    public function edit($id)
    {
        $denda = Denda::findOrFail($id);
        $peminjaman = Peminjaman::where('status', 'terlambat')->get();
        $anggota = Anggota::where('status', 'aktif')->get();
        return view('admin.denda.edit', compact('denda', 'peminjaman', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);
        
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'anggota_id' => 'required|exists:anggota,id',
            'jumlah_denda' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status_pembayaran' => 'required|in:belum_dibayar,sudah_dibayar',
        ]);

        $denda->update($request->all());
        
        return redirect()->route('denda.index')
            ->with('success', 'Data denda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();
        
        return redirect()->route('denda.index')
            ->with('success', 'Data denda berhasil dihapus.');
    }
} 