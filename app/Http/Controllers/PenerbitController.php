<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbit;

class PenerbitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $penerbit = Penerbit::paginate(10);
        return view('admin.penerbit.index', compact('penerbit'));
    }

    public function create()
    {
        return view('admin.penerbit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Penerbit::create($request->all());
        
        return redirect()->route('penerbit.index')
            ->with('success', 'Data penerbit berhasil ditambahkan.');
    }

    public function show($id)
    {
        $penerbit = Penerbit::findOrFail($id);
        return view('admin.penerbit.show', compact('penerbit'));
    }

    public function edit($id)
    {
        $penerbit = Penerbit::findOrFail($id);
        return view('admin.penerbit.edit', compact('penerbit'));
    }

    public function update(Request $request, $id)
    {
        $penerbit = Penerbit::findOrFail($id);
        
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit,' . $id,
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $penerbit->update($request->all());
        
        return redirect()->route('penerbit.index')
            ->with('success', 'Data penerbit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penerbit = Penerbit::findOrFail($id);
        $penerbit->delete();
        
        return redirect()->route('penerbit.index')
            ->with('success', 'Data penerbit berhasil dihapus.');
    }
} 