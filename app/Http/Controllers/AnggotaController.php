<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnggotaExport;
use App\Exports\AnggotaTemplateExport;
use App\Imports\AnggotaImport;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Anggota::with(['kelas.jurusan']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_anggota', 'like', "%{$search}%")
                  ->orWhere('barcode_anggota', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter by jurusan
        if ($request->filled('jurusan_id')) {
            $query->whereHas('kelas', function($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        // Filter by jenis anggota
        if ($request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $anggota = $query->paginate(10)->withQueryString();
        $kelas = Kelas::with('jurusan')->get();
        $jurusan = Jurusan::all();

        return view('admin.anggota.index', compact('anggota', 'kelas', 'jurusan'));
    }

    public function create()
    {
        $kelas = Kelas::with('jurusan')->get();
        return view('admin.anggota.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|unique:anggota,nik',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jabatan' => 'nullable|string|max:255',
            'jenis_anggota' => 'required|in:siswa,guru,staff',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif,ditangguhkan',
            'tanggal_bergabung' => 'required|date',
            'barcode_anggota' => 'required|string|unique:anggota,barcode_anggota',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor anggota otomatis
            $nomorAnggota = Anggota::generateNomorAnggota();

            $data = $request->all();
            $data['nomor_anggota'] = $nomorAnggota;

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/anggota', $fotoName);
                $data['foto'] = $fotoName;
            }

            Anggota::create($data);
            DB::commit();

            return redirect()->route('anggota.index')
                ->with('success', 'Data anggota berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $anggota = Anggota::with(['kelas.jurusan'])->findOrFail($id);
        return view('admin.anggota.show', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $kelas = Kelas::with('jurusan')->get();
        return view('admin.anggota.edit', compact('anggota', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|unique:anggota,nik,' . $id,
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jabatan' => 'nullable|string|max:255',
            'jenis_anggota' => 'required|in:siswa,guru,staff',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif,ditangguhkan',
            'tanggal_bergabung' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $anggota = Anggota::findOrFail($id);
            $data = $request->all();

            // Handle barcode - if empty, keep current barcode
            if (empty($data['barcode_anggota'])) {
                unset($data['barcode_anggota']);
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto if exists
                if ($anggota->foto && Storage::exists('public/anggota/' . $anggota->foto)) {
                    Storage::delete('public/anggota/' . $anggota->foto);
                }

                $foto = $request->file('foto');
                $fotoName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/anggota', $fotoName);
                $data['foto'] = $fotoName;
            }

            $anggota->update($data);
            DB::commit();

            return redirect()->route('anggota.index')
                ->with('success', 'Data anggota berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            
            // Delete foto if exists
            if ($anggota->foto && Storage::exists('public/anggota/' . $anggota->foto)) {
                Storage::delete('public/anggota/' . $anggota->foto);
            }
            
            $anggota->delete();
            
            return redirect()->route('anggota.index')
                ->with('success', 'Data anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:anggota,id'
        ]);

        DB::beginTransaction();
        try {
            $anggota = Anggota::whereIn('id', $request->ids)->get();
            
            foreach ($anggota as $item) {
                if ($item->foto && Storage::exists('public/anggota/' . $item->foto)) {
                    Storage::delete('public/anggota/' . $item->foto);
                }
            }
            
            Anggota::whereIn('id', $request->ids)->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' data anggota berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        $filename = 'anggota_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new AnggotaExport($request), $filename);
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_anggota.xlsx';
        
        return Excel::download(new AnggotaTemplateExport(), $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            // Clean existing duplicates before import
            $cleanedCount = Anggota::cleanAndRegenerateDuplicates();
            
            $import = new AnggotaImport();
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            
            if ($importedCount > 0) {
                $message = "✅ Berhasil mengimpor {$importedCount} data anggota.";
                
                if ($cleanedCount > 0) {
                    $message .= " 🔧 {$cleanedCount} data duplikasi telah dibersihkan.";
                }
                
                if (!empty($errors)) {
                    $uniqueErrors = array_unique($errors);
                    // $errorCount = count($uniqueErrors);
                    // $message .= " ⚠️ Ditemukan {$errorCount} jenis error:";
                    
                    // Tampilkan hanya 3 error pertama untuk tidak terlalu panjang
                    $displayErrors = array_slice($uniqueErrors, 0, 3);
                    foreach ($displayErrors as $error) {
                        $message .= "\n• " . $error;
                    }
                    
                    if ($errorCount > 3) {
                        $message .= "\n• Dan " . ($errorCount - 3) . " error lainnya.";
                    }
                }
                
                return redirect()->route('anggota.index')
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

    public function cetakKartu($id)
    {
        $anggota = Anggota::with(['kelas.jurusan'])->findOrFail($id);
        return view('admin.anggota.cetak-kartu', compact('anggota'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->barcode;
        $anggota = Anggota::where('barcode_anggota', $barcode)
                          ->orWhere('nomor_anggota', $barcode)
                          ->first();

        if ($anggota) {
            return response()->json([
                'success' => true,
                'data' => $anggota
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ]);
        }
    }

    public function generateBarcode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        try {
            $barcodeImage = \App\Helpers\BarcodeHelper::generateBarcodeImage($request->code, 'C128');
            return response()->json([
                'success' => true,
                'barcode' => $barcodeImage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal generate barcode'
            ]);
        }
    }

    // Method untuk membersihkan data duplikasi
    public function cleanDuplicateData()
    {
        try {
            $cleaned = Anggota::cleanDuplicateData();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil membersihkan {$cleaned} data duplikasi."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk regenerate kode yang duplikasi
    public function regenerateDuplicateCodes()
    {
        try {
            $regenerated = Anggota::regenerateDuplicateCodes();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil regenerate {$regenerated} kode duplikasi."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk membersihkan dan regenerate data duplikasi
    public function cleanAndRegenerateDuplicates()
    {
        try {
            $processed = Anggota::cleanAndRegenerateDuplicates();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil membersihkan dan regenerate {$processed} data duplikasi."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk mengecek data duplikasi
    public function checkDuplicateData()
    {
        try {
            $duplicateNomor = Anggota::select('nomor_anggota')
                ->groupBy('nomor_anggota')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            $duplicateBarcode = Anggota::select('barcode_anggota')
                ->groupBy('barcode_anggota')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            $duplicateNIK = Anggota::select('nik')
                ->groupBy('nik')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'duplicate_nomor' => $duplicateNomor,
                    'duplicate_barcode' => $duplicateBarcode,
                    'duplicate_nik' => $duplicateNIK,
                    'total_duplicates' => $duplicateNomor + $duplicateBarcode + $duplicateNIK
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk menampilkan statistik import
    public function getImportStats()
    {
        try {
            $totalAnggota = Anggota::count();
            $duplicateNomor = Anggota::select('nomor_anggota')
                ->groupBy('nomor_anggota')
                ->havingRaw('COUNT(*) > 1')
                ->count();
            $duplicateBarcode = Anggota::select('barcode_anggota')
                ->groupBy('barcode_anggota')
                ->havingRaw('COUNT(*) > 1')
                ->count();
            $duplicateNIK = Anggota::select('nik')
                ->groupBy('nik')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_anggota' => $totalAnggota,
                    'duplicate_nomor' => $duplicateNomor,
                    'duplicate_barcode' => $duplicateBarcode,
                    'duplicate_nik' => $duplicateNIK,
                    'total_duplicates' => $duplicateNomor + $duplicateBarcode + $duplicateNIK,
                    'clean_data' => $totalAnggota - ($duplicateNomor + $duplicateBarcode + $duplicateNIK)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
} 