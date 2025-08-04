<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor anggota dan barcode otomatis
            $nomorAnggota = Anggota::generateNomorAnggota();
            $barcodeAnggota = Anggota::generateBarcodeAnggota();

            $data = $request->all();
            $data['nomor_anggota'] = $nomorAnggota;
            $data['barcode_anggota'] = $barcodeAnggota;

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
        try {
            $anggota = Anggota::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $anggota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
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
        $query = Anggota::with(['kelas.jurusan']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_anggota', 'like', "%{$search}%")
                  ->orWhere('barcode_anggota', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $anggota = $query->get();

        $filename = 'anggota_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($anggota) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Nomor Anggota',
                'Barcode',
                'Nama Lengkap',
                'NIK',
                'Alamat',
                'Nomor Telepon',
                'Email',
                'Kelas',
                'Jurusan',
                'Jabatan',
                'Jenis Anggota',
                'Status',
                'Tanggal Bergabung'
            ]);

            foreach ($anggota as $item) {
                fputcsv($file, [
                    $item->nomor_anggota,
                    $item->barcode_anggota,
                    $item->nama_lengkap,
                    $item->nik,
                    $item->alamat,
                    $item->nomor_telepon,
                    $item->email,
                    $item->kelas ? $item->kelas->nama_kelas : '-',
                    $item->kelas && $item->kelas->jurusan ? $item->kelas->jurusan->nama_jurusan : '-',
                    $item->jabatan,
                    $item->jenis_anggota,
                    $item->status,
                    $item->tanggal_bergabung->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_anggota.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'nama_lengkap',
                'nik',
                'alamat',
                'nomor_telepon',
                'email',
                'kelas_id',
                'jabatan',
                'jenis_anggota',
                'status',
                'tanggal_bergabung'
            ]);

            // Sample data
            fputcsv($file, [
                'John Doe',
                '1234567890123456',
                'Jl. Contoh No. 123',
                '081234567890',
                'john@example.com',
                '1',
                'Siswa',
                'siswa',
                'aktif',
                '2024-01-01'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $handle = fopen($file->getPathname(), 'r');
            
            // Skip header row
            $header = fgetcsv($handle);
            $imported = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    $row = array_combine($header, $data);
                    
                    // Validate required fields
                    if (empty($row['nama_lengkap']) || empty($row['nik'])) {
                        $errors[] = "Baris " . ($imported + 2) . ": Nama lengkap dan NIK wajib diisi";
                        continue;
                    }

                    // Check if NIK already exists
                    if (Anggota::where('nik', $row['nik'])->exists()) {
                        $errors[] = "Baris " . ($imported + 2) . ": NIK sudah terdaftar";
                        continue;
                    }

                    // Generate nomor anggota dan barcode
                    $nomorAnggota = Anggota::generateNomorAnggota();
                    $barcodeAnggota = Anggota::generateBarcodeAnggota();

                    Anggota::create([
                        'nomor_anggota' => $nomorAnggota,
                        'barcode_anggota' => $barcodeAnggota,
                        'nama_lengkap' => $row['nama_lengkap'],
                        'nik' => $row['nik'],
                        'alamat' => $row['alamat'] ?? '',
                        'nomor_telepon' => $row['nomor_telepon'] ?? '',
                        'email' => $row['email'] ?? null,
                        'kelas_id' => $row['kelas_id'] ?? null,
                        'jabatan' => $row['jabatan'] ?? null,
                        'jenis_anggota' => $row['jenis_anggota'] ?? 'siswa',
                        'status' => $row['status'] ?? 'aktif',
                        'tanggal_bergabung' => $row['tanggal_bergabung'] ?? now(),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($imported + 2) . ": " . $e->getMessage();
                }
            }

            fclose($handle);
            DB::commit();

            $message = "Berhasil mengimpor {$imported} data anggota.";
            if (!empty($errors)) {
                $message .= " Error: " . implode(', ', $errors);
            }

            return redirect()->route('anggota.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
} 