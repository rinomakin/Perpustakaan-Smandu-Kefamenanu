<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\JenisBuku;
use App\Models\SumberBuku;
use App\Models\RakBuku;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuExport;
use App\Exports\BukuTemplateExport;
use App\Imports\BukuImport;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Buku::with(['kategori', 'jenis', 'sumber']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_buku', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi_rak', 'LIKE', "%{$search}%")
                  ->orWhere('penulis', 'LIKE', "%{$search}%")
                  ->orWhere('penerbit', 'LIKE', "%{$search}%")
                  ->orWhereHas('kategori', function($q) use ($search) {
                      $q->where('nama_kategori', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis_id')) {
            $query->where('jenis_id', $request->jenis_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan stok
        if ($request->filled('stok')) {
            if ($request->stok === 'tersedia') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->stok === 'habis') {
                $query->where('stok_tersedia', '<=', 0);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $buku = $query->paginate(10)->withQueryString();
        
        // Data untuk filter
        $kategoris = KategoriBuku::all();
        $jenis = JenisBuku::all();

        
        return view('admin.buku.index', compact('buku', 'kategoris', 'jenis'));
    }

    public function create()
    {
        $kategoris = KategoriBuku::all();
        $jenis = JenisBuku::all();
        $sumber = SumberBuku::all();
        $rakBuku = RakBuku::aktif()->get();
        
        return view('admin.buku.create', compact('kategoris', 'jenis', 'sumber', 'rakBuku'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul_buku' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'kategori_id' => 'required|exists:kategori_buku,id',
                'jenis_id' => 'required|exists:jenis_buku,id',
                'sumber_id' => 'required|exists:sumber_buku,id',
                'isbn' => 'nullable|string|max:20',
                'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'jumlah_halaman' => 'nullable|integer|min:1',
                'bahasa' => 'nullable|string|max:50',
                'jumlah_stok' => 'required|integer|min:1',
                'lokasi_rak' => 'nullable|string|max:255',
                'rak_id' => 'nullable|exists:rak_buku,id',
                'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'deskripsi' => 'nullable|string',
                'status' => 'required|in:tersedia,tidak_tersedia',
                'barcode' => 'nullable|string|unique:buku,barcode',
            ]);

            // Handle barcode generation or manual input
            $barcode = null;
            if ($request->filled('barcode')) {
                // Manual barcode input
                $barcode = $request->barcode;
                // Check if barcode already exists
                if (Buku::where('barcode', $barcode)->exists()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['barcode' => 'Barcode sudah ada, silakan gunakan barcode lain']);
                }
            } else {
                // Auto generate barcode
                try {
                    $barcode = Buku::generateBarcode();
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['barcode' => 'Gagal generate barcode: ' . $e->getMessage()]);
                }
            }
            
            $data = $request->all();
            $data['barcode'] = $barcode;
            $data['stok_tersedia'] = $request->jumlah_stok;

            // Handle file upload for gambar_sampul
            if ($request->hasFile('gambar_sampul')) {
                $file = $request->file('gambar_sampul');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $data['gambar_sampul'] = $filename;
            }

            Buku::create($data);
            
            return redirect()->route('buku.index')
                ->with('success', 'Data buku berhasil ditambahkan.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate barcode untuk buku
     */
    public function generateBarcode(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id'
        ]);

        $buku = Buku::findOrFail($request->buku_id);
        
        // Generate barcode baru jika belum ada
        if (!$buku->barcode) {
            $buku->update(['barcode' => Buku::generateBarcode()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barcode berhasil digenerate',
            'barcode' => $buku->barcode
        ]);
    }

    /**
     * Generate barcode untuk multiple buku
     */
    public function generateMultipleBarcode(Request $request)
    {
        $request->validate([
            'buku_ids' => 'required|array',
            'buku_ids.*' => 'exists:buku,id'
        ]);

        $successCount = 0;
        $errors = [];

        foreach ($request->buku_ids as $bukuId) {
            $buku = Buku::find($bukuId);
            
            if ($buku && !$buku->barcode) {
                try {
                    $buku->update(['barcode' => Buku::generateBarcode()]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal generate barcode untuk buku: {$buku->judul_buku}";
                }
            } elseif ($buku && $buku->barcode) {
                $errors[] = "Buku {$buku->judul_buku} sudah memiliki barcode";
            }
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => "Berhasil generate barcode untuk {$successCount} buku",
            'success_count' => $successCount,
            'errors' => $errors
        ]);
    }

    /**
     * Scan barcode untuk mencari buku
     */
    public function scanBarcode(Request $request)
    {
        $barcode = $request->barcode;
        $buku = Buku::with(['kategori', 'jenis', 'sumber'])
                    ->where('barcode', $barcode)
                    ->first();

        if ($buku) {
            return response()->json([
                'success' => true,
                'data' => $buku
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Buku dengan barcode tersebut tidak ditemukan'
            ]);
        }
    }

    /**
     * Cetak barcode untuk buku tunggal
     */
    public function printBarcode($id)
    {
        $buku = Buku::with(['kategori'])->findOrFail($id);
        
        return view('admin.buku.print-barcode', compact('buku'));
    }

    /**
     * Cetak barcode untuk buku tunggal (versi cetak)
     */
    public function cetakBarcode($id)
    {
        $buku = Buku::with(['kategori'])->findOrFail($id);
        
        return view('admin.buku.cetak-barcode', compact('buku'));
    }

    /**
     * Cetak barcode untuk multiple buku
     */
    public function printMultipleBarcode(Request $request)
    {
        $request->validate([
            'buku_ids' => 'required|array',
            'buku_ids.*' => 'exists:buku,id'
        ]);

        $buku = Buku::with(['kategori', 'jenis', 'sumber'])
                    ->whereIn('id', $request->buku_ids)
                    ->get();

        return view('admin.buku.print-multiple-barcode', compact('buku'));
    }

    /**
     * Export data buku ke Excel
     */
    public function export(Request $request)
    {
        $filename = 'data_buku_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new BukuExport($request), $filename);
    }

    /**
     * Download template import buku
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_buku.xlsx';
        
        return Excel::download(new BukuTemplateExport(), $filename);
    }

    /**
     * Import data buku dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new BukuImport();
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            
            if ($importedCount > 0) {
                $message = "✅ Berhasil mengimpor {$importedCount} data buku.";
                
                if (!empty($errors)) {
                    $uniqueErrors = array_unique($errors);
                    $errorCount = count($uniqueErrors);
                    $message .= " ⚠️ Ditemukan {$errorCount} jenis error:";
                    
                    // Tampilkan hanya 3 error pertama
                    $displayErrors = array_slice($uniqueErrors, 0, 3);
                    foreach ($displayErrors as $error) {
                        $message .= "\n• " . $error;
                    }
                    
                    if ($errorCount > 3) {
                        $message .= "\n• Dan " . ($errorCount - 3) . " error lainnya.";
                    }
                }
                
                return redirect()->route('buku.index')
                    ->with('success', $message);
            } else {
                $errorMessage = '❌ Tidak ada data yang berhasil diimpor.';
                if (!empty($errors)) {
                    $uniqueErrors = array_unique($errors);
                    $errorCount = count($uniqueErrors);
                    $errorMessage .= "\n\nDitemukan {$errorCount} jenis error:";
                    
                    // Tampilkan hanya 5 error pertama
                    $displayErrors = array_slice($uniqueErrors, 0, 5);
                    foreach ($displayErrors as $error) {
                        $errorMessage .= "\n• " . $error;
                    }
                    
                    if ($errorCount > 5) {
                        $errorMessage .= "\n• Dan " . ($errorCount - 5) . " error lainnya.";
                    }
                }
                
                return redirect()->back()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '❌ Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'jenis', 'sumber'])->findOrFail($id);
        return view('admin.buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = KategoriBuku::all();
        $jenis = JenisBuku::all();
        $sumber = SumberBuku::all();
        $rakBuku = RakBuku::aktif()->get();
        
        return view('admin.buku.edit', compact('buku', 'kategoris', 'jenis', 'sumber', 'rakBuku'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_buku,id',
            'jenis_id' => 'required|exists:jenis_buku,id',
            'sumber_id' => 'required|exists:sumber_buku,id',
            'isbn' => 'nullable|string|max:20',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_halaman' => 'nullable|integer|min:1',
            'bahasa' => 'nullable|string|max:50',
            'jumlah_stok' => 'required|integer|min:1',
            'lokasi_rak' => 'nullable|string|max:255',
            'rak_id' => 'nullable|exists:rak_buku,id',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        $data = $request->all();
        $data['stok_tersedia'] = $request->jumlah_stok;
        // Barcode tidak boleh diubah saat edit
        unset($data['barcode']);

        // Handle file upload for gambar_sampul
        if ($request->hasFile('gambar_sampul')) {
            $file = $request->file('gambar_sampul');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['gambar_sampul'] = $filename;
        }

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

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'buku_ids' => 'required|array',
            'buku_ids.*' => 'exists:buku,id'
        ]);

        try {
            DB::beginTransaction();
            
            $deletedCount = 0;
            $errors = [];
            
            foreach ($request->buku_ids as $bukuId) {
                $buku = Buku::find($bukuId);
                
                if ($buku) {
                    try {
                        $buku->delete();
                        $deletedCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Gagal menghapus buku: {$buku->judul_buku}";
                    }
                }
            }

            DB::commit();

            $message = "Berhasil menghapus {$deletedCount} buku";
            if (count($errors) > 0) {
                $message .= " (" . count($errors) . " buku tidak dapat dihapus)";
            }

            return response()->json([
                'success' => true,
                'count' => $deletedCount,
                'message' => $message,
                'errors' => $errors
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