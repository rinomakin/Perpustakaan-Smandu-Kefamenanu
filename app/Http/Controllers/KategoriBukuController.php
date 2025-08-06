<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriBukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    public function index()
    {
        $kategoris = KategoriBuku::withCount('buku')->orderBy('nama_kategori')->paginate(10);
        return view('admin.kategori-buku.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori-buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_buku,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();
            
            KategoriBuku::create([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
            ]);

            DB::commit();
            return redirect()->route('kategori-buku.index')->with('success', 'Kategori buku berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(KategoriBuku $kategoriBuku)
    {
        $bukuCount = $kategoriBuku->buku()->count();
        return view('admin.kategori-buku.show', compact('kategoriBuku', 'bukuCount'));
    }

    public function edit(KategoriBuku $kategoriBuku)
    {
        return view('admin.kategori-buku.edit', compact('kategoriBuku'));
    }

    public function update(Request $request, KategoriBuku $kategoriBuku)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_buku,nama_kategori,' . $kategoriBuku->id,
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        try {
            DB::beginTransaction();
            
            $kategoriBuku->update([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
            ]);

            DB::commit();
            return redirect()->route('kategori-buku.index')->with('success', 'Kategori buku berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(KategoriBuku $kategoriBuku)
    {
        try {
            // Check if kategori is being used by any books
            $bukuCount = $kategoriBuku->buku()->count();
            if ($bukuCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Kategori tidak dapat dihapus karena masih digunakan oleh {$bukuCount} buku"
                ]);
            }

            DB::beginTransaction();
            $kategoriBuku->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori buku berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'kategori_ids' => 'required|array',
            'kategori_ids.*' => 'exists:kategori_buku,id'
        ]);

        try {
            DB::beginTransaction();
            
            $deletedCount = 0;
            $errorCount = 0;
            
            foreach ($request->kategori_ids as $kategoriId) {
                $kategori = KategoriBuku::find($kategoriId);
                
                if ($kategori) {
                    $bukuCount = $kategori->buku()->count();
                    if ($bukuCount > 0) {
                        $errorCount++;
                        continue;
                    }
                    
                    $kategori->delete();
                    $deletedCount++;
                }
            }

            DB::commit();

            $message = "Berhasil menghapus {$deletedCount} kategori";
            if ($errorCount > 0) {
                $message .= " ({$errorCount} kategori tidak dapat dihapus karena masih digunakan)";
            }

            return response()->json([
                'success' => true,
                'count' => $deletedCount,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
} 