<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Kelas;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $anggota = Anggota::with('kelas')->paginate(10);
        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.anggota.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_anggota' => 'required|string|unique:anggota,nomor_anggota',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $anggota = Anggota::create($request->all());
        
        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function show($id)
    {
        $anggota = Anggota::with('kelas')->findOrFail($id);
        return view('admin.anggota.show', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.anggota.edit', compact('anggota', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_anggota' => 'required|string|unique:anggota,nomor_anggota,' . $id,
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $anggota->update($request->all());
        
        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();
        
        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
} 