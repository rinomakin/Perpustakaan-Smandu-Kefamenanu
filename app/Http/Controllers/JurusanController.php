<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $jurusan = Jurusan::paginate(10);
        return view('admin.jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan',
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Jurusan::create($request->all());
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('admin.jurusan.show', compact('jurusan'));
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan,' . $id,
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan,' . $id,
            'deskripsi' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $jurusan->update($request->all());
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();
        
        return redirect()->route('jurusan.index')
            ->with('success', 'Data jurusan berhasil dihapus.');
    }
} 