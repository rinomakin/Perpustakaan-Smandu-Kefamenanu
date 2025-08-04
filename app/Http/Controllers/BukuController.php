<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\KategoriBuku;
use App\Models\JenisBuku;
use App\Models\SumberBuku;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $buku = Buku::with(['penulis', 'penerbit', 'kategori'])->paginate(10);
        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        $penulis = Penulis::all();
        $penerbit = Penerbit::all();
        $kategori = KategoriBuku::all();
        $jenis = JenisBuku::all();
        $sumber = SumberBuku::all();
        
        return view('admin.buku.create', compact('penulis', 'penerbit', 'kategori', 'jenis', 'sumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis_id' => 'required|exists:penulis,id',
            'penerbit_id' => 'required|exists:penerbit,id',
            'kategori_buku_id' => 'required|exists:kategori_buku,id',
            'jenis_buku_id' => 'required|exists:jenis_buku,id',
            'sumber_buku_id' => 'required|exists:sumber_buku,id',
            'isbn' => 'nullable|string|max:20',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_halaman' => 'nullable|integer|min:1',
            'jumlah_stok' => 'required|integer|min:1',
            'lokasi_rak' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        // Generate barcode otomatis
        $barcode = Buku::generateBarcode();
        
        $data = $request->all();
        $data['barcode'] = $barcode;
        $data['stok_tersedia'] = $request->jumlah_stok;

        Buku::create($data);
        
        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        $buku = Buku::with(['penulis', 'penerbit', 'kategori', 'jenis', 'sumber'])->findOrFail($id);
        return view('admin.buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $penulis = Penulis::all();
        $penerbit = Penerbit::all();
        $kategori = KategoriBuku::all();
        $jenis = JenisBuku::all();
        $sumber = SumberBuku::all();
        
        return view('admin.buku.edit', compact('buku', 'penulis', 'penerbit', 'kategori', 'jenis', 'sumber'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis_id' => 'required|exists:penulis,id',
            'penerbit_id' => 'required|exists:penerbit,id',
            'kategori_buku_id' => 'required|exists:kategori_buku,id',
            'jenis_buku_id' => 'required|exists:jenis_buku,id',
            'sumber_buku_id' => 'required|exists:sumber_buku,id',
            'isbn' => 'nullable|string|max:20',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_halaman' => 'nullable|integer|min:1',
            'jumlah_stok' => 'required|integer|min:1',
            'lokasi_rak' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        $data = $request->all();
        $data['stok_tersedia'] = $request->jumlah_stok;

        $buku->update($data);
        
        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        
        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil dihapus.');
    }
} 