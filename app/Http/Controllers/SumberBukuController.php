<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SumberBuku;

class SumberBukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $sumber = SumberBuku::paginate(10);
        return view('admin.sumber-buku.index', compact('sumber'));
    }

    public function create()
    {
        return view('admin.sumber-buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sumber' => 'required|string|max:255|unique:sumber_buku,nama_sumber',
            'deskripsi' => 'nullable|string',
        ]);

        SumberBuku::create($request->all());
        
        return redirect()->route('sumber-buku.index')
            ->with('success', 'Data sumber buku berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sumber = SumberBuku::findOrFail($id);
        return view('admin.sumber-buku.show', compact('sumber'));
    }

    public function edit($id)
    {
        $sumber = SumberBuku::findOrFail($id);
        return view('admin.sumber-buku.edit', compact('sumber'));
    }

    public function update(Request $request, $id)
    {
        $sumber = SumberBuku::findOrFail($id);
        
        $request->validate([
            'nama_sumber' => 'required|string|max:255|unique:sumber_buku,nama_sumber,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $sumber->update($request->all());
        
        return redirect()->route('sumber-buku.index')
            ->with('success', 'Data sumber buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sumber = SumberBuku::findOrFail($id);
        $sumber->delete();
        
        return redirect()->route('sumber-buku.index')
            ->with('success', 'Data sumber buku berhasil dihapus.');
    }
} 