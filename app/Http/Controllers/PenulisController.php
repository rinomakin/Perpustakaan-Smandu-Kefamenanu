<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penulis;

class PenulisController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $penulis = Penulis::paginate(10);
        return view('admin.penulis.index', compact('penulis'));
    }

    public function create()
    {
        return view('admin.penulis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penulis' => 'required|string|max:255|unique:penulis,nama_penulis',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'biografi' => 'nullable|string',
        ]);

        Penulis::create($request->all());
        
        return redirect()->route('penulis.index')
            ->with('success', 'Data penulis berhasil ditambahkan.');
    }

    public function show($id)
    {
        $penulis = Penulis::findOrFail($id);
        return view('admin.penulis.show', compact('penulis'));
    }

    public function edit($id)
    {
        $penulis = Penulis::findOrFail($id);
        return view('admin.penulis.edit', compact('penulis'));
    }

    public function update(Request $request, $id)
    {
        $penulis = Penulis::findOrFail($id);
        
        $request->validate([
            'nama_penulis' => 'required|string|max:255|unique:penulis,nama_penulis,' . $id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'biografi' => 'nullable|string',
        ]);

        $penulis->update($request->all());
        
        return redirect()->route('penulis.index')
            ->with('success', 'Data penulis berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penulis = Penulis::findOrFail($id);
        $penulis->delete();
        
        return redirect()->route('penulis.index')
            ->with('success', 'Data penulis berhasil dihapus.');
    }
} 