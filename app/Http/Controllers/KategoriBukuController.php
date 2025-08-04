<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;

class KategoriBukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $kategori = KategoriBuku::paginate(10);
        return view('admin.kategori-buku.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori-buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_buku,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriBuku::create($request->all());
        
        return redirect()->route('kategori-buku.index')
            ->with('success', 'Data kategori buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        return view('admin.kategori-buku.show', compact('kategori'));
    }

    public function edit($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        return view('admin.kategori-buku.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_buku,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->all());
        
        return redirect()->route('kategori-buku.index')
            ->with('success', 'Data kategori buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $kategori->delete();
        
        return redirect()->route('kategori-buku.index')
            ->with('success', 'Data kategori buku berhasil dihapus.');
    }
} 