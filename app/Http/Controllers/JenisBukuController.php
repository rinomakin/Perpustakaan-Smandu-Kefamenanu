<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBuku;

class JenisBukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $jenis = JenisBuku::paginate(10);
        return view('admin.jenis-buku.index', compact('jenis'));
    }

    public function create()
    {
        return view('admin.jenis-buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_buku,nama_jenis',
            'deskripsi' => 'nullable|string',
        ]);

        JenisBuku::create($request->all());
        
        return redirect()->route('jenis-buku.index')
            ->with('success', 'Data jenis buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        $jenis = JenisBuku::findOrFail($id);
        return view('admin.jenis-buku.show', compact('jenis'));
    }

    public function edit($id)
    {
        $jenis = JenisBuku::findOrFail($id);
        return view('admin.jenis-buku.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisBuku::findOrFail($id);
        
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_buku,nama_jenis,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $jenis->update($request->all());
        
        return redirect()->route('jenis-buku.index')
            ->with('success', 'Data jenis buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenis = JenisBuku::findOrFail($id);
        $jenis->delete();
        
        return redirect()->route('jenis-buku.index')
            ->with('success', 'Data jenis buku berhasil dihapus.');
    }
} 