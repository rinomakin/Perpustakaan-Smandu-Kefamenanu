<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku'])->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->get();
        return view('admin.peminjaman.create', compact('anggota', 'buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_ids' => 'required|array',
            'buku_ids.*' => 'exists:buku,id',
            'jumlah_buku' => 'required|array',
            'jumlah_buku.*' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date',
            'jam_peminjaman' => 'nullable|date_format:H:i',
            'tanggal_harus_kembali' => 'required|date|after:tanggal_peminjaman',
            'jam_kembali' => 'nullable|date_format:H:i',
            'catatan' => 'nullable|string',
        ]);

        // Custom validation for jam_kembali
        if ($request->jam_peminjaman && $request->jam_kembali) {
            if ($request->jam_peminjaman === $request->jam_kembali) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jam_kembali' => 'Jam pengembalian tidak boleh sama dengan jam peminjaman']);
            }
        }

        DB::beginTransaction();
        try {
            // Calculate total books
            $totalBooks = array_sum($request->jumlah_buku);
            
            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'nomor_peminjaman' => Peminjaman::generateNomorPeminjaman(),
                'anggota_id' => $request->anggota_id,
                'user_id' => auth()->id(),
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'jam_peminjaman' => $request->jam_peminjaman ?? now()->format('H:i'),
                'tanggal_harus_kembali' => $request->tanggal_harus_kembali,
                'jam_kembali' => $request->jam_kembali,
                'status' => 'dipinjam',
                'catatan' => $request->catatan,
                'jumlah_buku' => $totalBooks,
            ]);

            // Create detail peminjaman for each book with quantity
            foreach ($request->buku_ids as $index => $buku_id) {
                $buku = Buku::find($buku_id);
                $jumlah = $request->jumlah_buku[$buku_id] ?? 1;
                
                // Check if book is available
                if ($buku->stok_tersedia < $jumlah) {
                    throw new \Exception("Buku {$buku->judul_buku} hanya tersedia {$buku->stok_tersedia} eksemplar, diminta {$jumlah} eksemplar");
                }

                // Create detail peminjaman
                $peminjaman->detailPeminjaman()->create([
                    'buku_id' => $buku_id,
                    'jumlah' => $jumlah,
                    'kondisi_kembali' => 'baik',
                    'catatan' => null,
                ]);

                // Update book stock
                $buku->decrement('stok_tersedia', $jumlah);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.buku'])->findOrFail($id);
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->get();
        return view('admin.peminjaman.edit', compact('peminjaman', 'anggota', 'buku'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_peminjaman' => 'required|date',
            'jam_peminjaman' => 'nullable|date_format:H:i',
            'tanggal_harus_kembali' => 'required|date|after:tanggal_peminjaman',
            'jam_kembali' => 'nullable|date_format:H:i',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
            'catatan' => 'nullable|string',
        ]);

        // Jika status diubah menjadi dikembalikan, set jam_kembali otomatis
        if ($request->status === 'dikembalikan' && !$request->jam_kembali) {
            $request->merge(['jam_kembali' => now()->format('H:i')]);
        }

        $peminjaman->update($request->all());
        
        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Return all books to stock with correct quantity
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->buku->increment('stok_tersedia', $detail->jumlah ?? 1);
            }
            
            $peminjaman->delete();
            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk menampilkan detail peminjaman dengan QR scanner
    public function detail($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku'])->findOrFail($id);
        $buku = Buku::where('stok_tersedia', '>', 0)->get();
        return view('admin.peminjaman.detail', compact('peminjaman', 'buku'));
    }

    // AJAX method untuk menambah buku ke peminjaman
    public function addBook(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'buku_id' => 'required|exists:buku,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $buku = Buku::findOrFail($request->buku_id);

        // Check if book is already in this loan
        $existingDetail = $peminjaman->detailPeminjaman()->where('buku_id', $request->buku_id)->first();
        if ($existingDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini sudah ada dalam peminjaman ini'
            ]);
        }

        // Check if book is available
        if ($buku->stok_tersedia < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => "Buku hanya tersedia {$buku->stok_tersedia} eksemplar, diminta {$request->jumlah} eksemplar"
            ]);
        }

        DB::beginTransaction();
        try {
            $detail = $peminjaman->detailPeminjaman()->create([
                'buku_id' => $request->buku_id,
                'jumlah' => $request->jumlah,
                'kondisi_kembali' => 'baik',
                'catatan' => null,
            ]);

            $buku->decrement('stok_tersedia', $request->jumlah);

            // Update jumlah_buku di peminjaman
            $peminjaman->update([
                'jumlah_buku' => $peminjaman->detailPeminjaman()->sum('jumlah')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'detail' => $detail->load('buku')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // AJAX method untuk menghapus buku dari peminjaman
    public function removeBook(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|exists:detail_peminjaman,id',
        ]);

        $detail = DetailPeminjaman::findOrFail($request->detail_id);
        $buku = $detail->buku;
        $jumlah = $detail->jumlah ?? 1;

        DB::beginTransaction();
        try {
            $buku->increment('stok_tersedia', $jumlah);
            $detail->delete();

            // Update jumlah_buku di peminjaman
            $peminjaman = $detail->peminjaman;
            $peminjaman->update([
                'jumlah_buku' => $peminjaman->detailPeminjaman()->sum('jumlah')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus dari peminjaman'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // AJAX method untuk scan QR code
    public function scanQR(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'barcode' => 'required|string',
            'jumlah' => 'required|integer|min:1',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $buku = Buku::where('barcode', $request->barcode)->first();

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
        }

        // Check if book is already in this loan
        $existingDetail = $peminjaman->detailPeminjaman()->where('buku_id', $buku->id)->first();
        if ($existingDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini sudah ada dalam peminjaman ini'
            ]);
        }

        // Check if book is available
        if ($buku->stok_tersedia < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => "Buku hanya tersedia {$buku->stok_tersedia} eksemplar, diminta {$request->jumlah} eksemplar"
            ]);
        }

        DB::beginTransaction();
        try {
            $detail = $peminjaman->detailPeminjaman()->create([
                'buku_id' => $buku->id,
                'jumlah' => $request->jumlah,
                'kondisi_kembali' => 'baik',
                'catatan' => null,
            ]);

            $buku->decrement('stok_tersedia', $request->jumlah);

            // Update jumlah_buku di peminjaman
            $peminjaman->update([
                'jumlah_buku' => $peminjaman->detailPeminjaman()->sum('jumlah')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'detail' => $detail->load('buku')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API untuk scan barcode anggota
     */
    public function scanAnggota(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $anggota = Anggota::where('barcode_anggota', $request->barcode)
                          ->where('status', 'aktif')
                          ->with('kelas')
                          ->first();

        if (!$anggota) {
            // Get some example barcodes for better error message
            $exampleBarcodes = Anggota::where('status', 'aktif')
                                     ->whereNotNull('barcode_anggota')
                                     ->take(3)
                                     ->pluck('barcode_anggota')
                                     ->toArray();
            
            $message = 'Anggota dengan barcode "' . $request->barcode . '" tidak ditemukan atau tidak aktif.';
            if (!empty($exampleBarcodes)) {
                $message .= ' Contoh barcode yang valid: ' . implode(', ', $exampleBarcodes);
            }
            
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $anggota->id,
                'nama_lengkap' => $anggota->nama_lengkap,
                'nomor_anggota' => $anggota->nomor_anggota,
                'barcode_anggota' => $anggota->barcode_anggota,
                'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                'jenis_anggota' => $anggota->jenis_anggota
            ]
        ]);
    }

    /**
     * API untuk scan barcode buku
     */
    public function scanBuku(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $buku = Buku::where('barcode_buku', $request->barcode)
                    ->where('stok_tersedia', '>', 0)
                    ->with('kategoriBuku', 'jenisBuku')
                    ->first();

        if (!$buku) {
            // Get some example barcodes for better error message
            $exampleBarcodes = Buku::where('stok_tersedia', '>', 0)
                                   ->whereNotNull('barcode_buku')
                                   ->take(3)
                                   ->pluck('barcode_buku')
                                   ->toArray();
            
            $message = 'Buku dengan barcode "' . $request->barcode . '" tidak ditemukan atau stok habis.';
            if (!empty($exampleBarcodes)) {
                $message .= ' Contoh barcode yang valid: ' . implode(', ', $exampleBarcodes);
            }
            
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $buku->id,
                'judul_buku' => $buku->judul_buku,
                'barcode_buku' => $buku->barcode_buku,
                'isbn' => $buku->isbn,
                'stok_tersedia' => $buku->stok_tersedia,
                'penulis' => $buku->penulis ?? 'N/A',
                'penerbit' => $buku->penerbit ?? 'N/A',
                'kategori' => $buku->kategoriBuku ? $buku->kategoriBuku->nama_kategori : 'N/A'
            ]
        ]);
    }

    /**
     * API untuk scan multiple barcode buku
     */
    public function scanMultipleBuku(Request $request)
    {
        $request->validate([
            'barcodes' => 'required|array',
            'barcodes.*' => 'string'
        ]);

        $bukuList = [];
        $errors = [];

        foreach ($request->barcodes as $barcode) {
            $buku = Buku::where('barcode_buku', $barcode)
                        ->where('stok_tersedia', '>', 0)
                        ->with('kategoriBuku', 'jenisBuku')
                        ->first();

            if ($buku) {
                $bukuList[] = [
                    'id' => $buku->id,
                    'judul_buku' => $buku->judul_buku,
                    'barcode_buku' => $buku->barcode_buku,
                    'isbn' => $buku->isbn,
                    'stok_tersedia' => $buku->stok_tersedia,
                    'penulis' => $buku->penulis ?? 'N/A',
                    'penerbit' => $buku->penerbit ?? 'N/A',
                    'kategori' => $buku->kategoriBuku ? $buku->kategoriBuku->nama_kategori : 'N/A'
                ];
            } else {
                $errors[] = "Buku dengan barcode {$barcode} tidak ditemukan atau stok habis";
            }
        }

        return response()->json([
            'success' => count($bukuList) > 0,
            'data' => $bukuList,
            'errors' => $errors
        ]);
    }

    /**
     * API untuk search anggota
     */
    public function searchAnggota(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->query;
        
        $anggota = Anggota::where('status', 'aktif')
                          ->where(function($q) use ($query) {
                              $q->where('nama_lengkap', 'LIKE', "%{$query}%")
                                ->orWhere('nomor_anggota', 'LIKE', "%{$query}%")
                                ->orWhere('nisn', 'LIKE', "%{$query}%")
                                ->orWhere('barcode_anggota', 'LIKE', "%{$query}%");
                          })
                          ->with('kelas')
                          ->take(10)
                          ->get()
                          ->map(function($anggota) {
                              return [
                                  'id' => $anggota->id,
                                  'nama_lengkap' => $anggota->nama_lengkap,
                                  'nomor_anggota' => $anggota->nomor_anggota,
                                  'nisn' => $anggota->nisn,
                                  'barcode_anggota' => $anggota->barcode_anggota,
                                  'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                                  'jenis_anggota' => $anggota->jenis_anggota
                              ];
                          });

        return response()->json([
            'success' => true,
            'data' => $anggota
        ]);
    }

    /**
     * API untuk search buku
     */
    public function searchBuku(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->query;
        
        $buku = Buku::where('stok_tersedia', '>', 0)
                    ->where(function($q) use ($query) {
                        $q->where('judul_buku', 'LIKE', "%{$query}%")
                          ->orWhere('penulis', 'LIKE', "%{$query}%")
                          ->orWhere('isbn', 'LIKE', "%{$query}%")
                          ->orWhere('barcode_buku', 'LIKE', "%{$query}%");
                    })
                    ->with('kategoriBuku', 'jenisBuku')
                    ->take(10)
                    ->get()
                    ->map(function($buku) {
                        return [
                            'id' => $buku->id,
                            'judul_buku' => $buku->judul_buku,
                            'penulis' => $buku->penulis ?? 'N/A',
                            'penerbit' => $buku->penerbit ?? 'N/A',
                            'isbn' => $buku->isbn ?? 'N/A',
                            'barcode_buku' => $buku->barcode_buku ?? 'N/A',
                            'stok_tersedia' => $buku->stok_tersedia,
                            'kategori' => $buku->kategoriBuku ? $buku->kategoriBuku->nama_kategori : 'N/A'
                        ];
                    });

        return response()->json([
            'success' => true,
            'data' => $buku
        ]);
    }
} 