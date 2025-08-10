<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }

    public function index()
    {
        $pengembalian = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku', 'denda'])
            ->where('status', 'dikembalikan')
            ->whereDate('tanggal_kembali', today())
            ->orderBy('tanggal_kembali', 'desc')
            ->paginate(10);
            
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        return view('admin.pengembalian.create');
    }

    /**
     * Show all pengembalian history (not just today)
     */
    public function history()
    {
        $pengembalian = Peminjaman::with(['anggota', 'user', 'detailPeminjaman.buku', 'denda'])
            ->where('status', 'dikembalikan')
            ->orderBy('tanggal_kembali', 'desc')
            ->paginate(10);
            
        return view('admin.pengembalian.history', compact('pengembalian'));
    }

    /**
     * Search anggota for pengembalian by barcode or manual search
     */
    public function searchAnggota(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Query terlalu pendek'
            ]);
        }

        try {
            // Cari anggota yang memiliki peminjaman aktif
            $anggota = Anggota::with(['kelas'])
                ->where('status', 'aktif')
                ->whereHas('peminjaman', function($q) {
                    $q->where('status', 'dipinjam');
                })
                ->where(function($q) use ($query) {
                    $q->where('nama_lengkap', 'like', "%{$query}%")
                      ->orWhere('nomor_anggota', 'like', "%{$query}%")
                      ->orWhere('barcode_anggota', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function($item) {
                    // Hitung jumlah peminjaman aktif
                    $jumlahPeminjamanAktif = $item->peminjaman()
                        ->where('status', 'dipinjam')
                        ->count();
                    
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'nomor_anggota' => $item->nomor_anggota,
                        'barcode_anggota' => $item->barcode_anggota,
                        'kelas' => $item->kelas ? $item->kelas->nama_kelas : 'N/A',
                        'jenis_anggota' => $item->jenis_anggota,
                        'jumlah_peminjaman_aktif' => $jumlahPeminjamanAktif
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $anggota
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Scan barcode anggota untuk pengembalian
     */
    public function scanBarcodeAnggota(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        try {
            $anggota = Anggota::where('barcode_anggota', $request->barcode)
                              ->where('status', 'aktif')
                              ->with('kelas')
                              ->first();

            if (!$anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota dengan barcode tersebut tidak ditemukan atau tidak aktif'
                ], 404);
            }

            // Get active peminjaman for this anggota
            $peminjaman = Peminjaman::with(['detailPeminjaman.buku.kategoriBuku', 'anggota'])
                ->where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->get()
                ->map(function($item) {
                    $today = Carbon::now();
                    $tanggalKembali = Carbon::parse($item->tanggal_harus_kembali);
                    $isLate = $today->gt($tanggalKembali);
                    $daysLate = $isLate ? $today->diffInDays($tanggalKembali) : 0;
                    
                    return [
                        'id' => $item->id,
                        'nomor_peminjaman' => $item->nomor_peminjaman,
                        'tanggal_peminjaman' => $item->tanggal_peminjaman->format('d/m/Y'),
                        'tanggal_harus_kembali' => $item->tanggal_harus_kembali->format('d/m/Y'),
                        'is_late' => $isLate,
                        'days_late' => $daysLate,
                        'catatan' => $item->catatan,
                        'detail_peminjaman' => $item->detailPeminjaman->map(function($detail) {
                            return [
                                'id' => $detail->id,
                                'buku_id' => $detail->buku_id,
                                'judul_buku' => $detail->buku->judul_buku,
                                'penulis' => $detail->buku->pengarang ?? 'N/A',
                                'kategori' => $detail->buku->kategoriBuku ? $detail->buku->kategoriBuku->nama_kategori : 'N/A',
                                'jumlah' => $detail->jumlah,
                                'kondisi_kembali' => $detail->kondisi_kembali
                            ];
                        })
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'anggota' => [
                        'id' => $anggota->id,
                        'nama_lengkap' => $anggota->nama_lengkap,
                        'nomor_anggota' => $anggota->nomor_anggota,
                        'barcode_anggota' => $anggota->barcode_anggota,
                        'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                        'jenis_anggota' => $anggota->jenis_anggota
                    ],
                    'peminjaman' => $peminjaman
                ],
                'message' => $peminjaman->count() > 0 ? 'Peminjaman aktif ditemukan' : 'Tidak ada peminjaman aktif'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active borrowings for an anggota
     */
    public function getPeminjamanAktif(Request $request)
    {
        $anggotaId = $request->get('anggota_id');
        
        if (!$anggotaId) {
            return response()->json([
                'success' => false,
                'message' => 'ID anggota tidak ditemukan'
            ]);
        }

        try {
            $peminjaman = Peminjaman::with(['detailPeminjaman.buku.kategori', 'anggota'])
                ->where('anggota_id', $anggotaId)
                ->where('status', 'dipinjam')
                ->get()
                ->map(function($item) {
                    $today = Carbon::now();
                    $tanggalKembali = Carbon::parse($item->tanggal_harus_kembali);
                    $isLate = $today->gt($tanggalKembali);
                    $daysLate = $isLate ? $today->diffInDays($tanggalKembali) : 0;
                    
                    return [
                        'id' => $item->id,
                        'nomor_peminjaman' => $item->nomor_peminjaman,
                        'tanggal_peminjaman' => $item->tanggal_peminjaman->format('d/m/Y'),
                        'tanggal_harus_kembali' => $item->tanggal_harus_kembali->format('d/m/Y'),
                        'is_late' => $isLate,
                        'days_late' => $daysLate,
                        'catatan' => $item->catatan,
                        'detail_peminjaman' => $item->detailPeminjaman->map(function($detail) {
                            return [
                                'id' => $detail->id,
                                'buku_id' => $detail->buku_id,
                                'judul_buku' => $detail->buku->judul_buku,
                                'penulis' => $detail->buku->penulis,
                                'kategori' => $detail->buku->kategori ? $detail->buku->kategori->nama_kategori : 'N/A',
                                'jumlah' => $detail->jumlah,
                                'kondisi_kembali' => $detail->kondisi_kembali
                            ];
                        })
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $peminjaman,
                'message' => $peminjaman->count() > 0 ? 'Peminjaman aktif ditemukan' : 'Tidak ada peminjaman aktif'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process book return
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date',
            'jam_kembali' => 'nullable|date_format:H:i',
            'catatan_pengembalian' => 'nullable|string',
            'kondisi_kembali' => 'required|array',
            'kondisi_kembali.*' => 'required|in:baik,rusak,hilang',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPeminjaman.buku')->findOrFail($request->peminjaman_id);
            
            // Check if already returned
            if ($peminjaman->status === 'dikembalikan') {
                throw new \Exception('Peminjaman ini sudah dikembalikan sebelumnya.');
            }

            // Calculate late fee if any
            $tanggalKembali = Carbon::parse($request->tanggal_kembali);
            $tanggalHarusKembali = Carbon::parse($peminjaman->tanggal_harus_kembali);
            $isLate = $tanggalKembali->gt($tanggalHarusKembali);
            $daysLate = $isLate ? $tanggalKembali->diffInDays($tanggalHarusKembali) : 0;
            
            // Update peminjaman status
            $peminjaman->update([
                'tanggal_kembali' => $tanggalKembali,
                'jam_kembali' => $request->jam_kembali ?? now()->format('H:i'),
                'status' => $isLate ? 'terlambat' : 'dikembalikan',
                'catatan' => $peminjaman->catatan . ($request->catatan_pengembalian ? "\n\nCatatan Pengembalian: " . $request->catatan_pengembalian : '')
            ]);

            // Update detail peminjaman and book stock
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $kondisi = $request->kondisi_kembali[$detail->id] ?? 'baik';
                
                $detail->update([
                    'kondisi_kembali' => $kondisi
                ]);

                // Return book stock if condition is good or damaged (not lost)
                if ($kondisi !== 'hilang') {
                    $detail->buku->increment('stok_tersedia', $detail->jumlah);
                }
            }

            // Create denda if late
            $dendaAmount = 0;
            if ($isLate && $daysLate > 0) {
                $dendaPerHari = 1000; // Rp 1000 per hari
                $dendaAmount = $daysLate * $dendaPerHari;
                
                Denda::create([
                    'peminjaman_id' => $peminjaman->id,
                    'jumlah_denda' => $dendaAmount,
                    'alasan' => "Keterlambatan pengembalian {$daysLate} hari",
                    'status' => 'belum_bayar',
                    'tanggal_denda' => now()
                ]);
            }

            // Hapus peminjaman setelah diproses
            $peminjaman->delete();

            DB::commit();
            
            $message = 'Pengembalian berhasil diproses dan peminjaman telah dihapus.';
            if ($isLate) {
                $message .= " Anggota terlambat {$daysLate} hari dan dikenakan denda Rp " . number_format($dendaAmount, 0, ',', '.');
            }
            
            return redirect()->route('pengembalian.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Scan barcode anggota untuk pengembalian
     */
    public function scanBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        
        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak ditemukan'
            ]);
        }

        try {
            $anggota = Anggota::with('kelas')
                ->where('barcode_anggota', $barcode)
                ->where('status', 'aktif')
                ->first();

            if (!$anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota tidak ditemukan atau tidak aktif'
                ]);
            }

            // Get active borrowings
            $peminjaman = Peminjaman::with(['detailPeminjaman.buku.kategori'])
                ->where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'anggota' => [
                        'id' => $anggota->id,
                        'nama_lengkap' => $anggota->nama_lengkap,
                        'nomor_anggota' => $anggota->nomor_anggota,
                        'barcode_anggota' => $anggota->barcode_anggota,
                        'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                        'jenis_anggota' => $anggota->jenis_anggota
                    ],
                    'peminjaman' => $peminjaman->map(function($item) {
                        $today = Carbon::now();
                        $tanggalKembali = Carbon::parse($item->tanggal_harus_kembali);
                        $isLate = $today->gt($tanggalKembali);
                        $daysLate = $isLate ? $today->diffInDays($tanggalKembali) : 0;
                        
                        return [
                            'id' => $item->id,
                            'nomor_peminjaman' => $item->nomor_peminjaman,
                            'tanggal_peminjaman' => $item->tanggal_peminjaman->format('d/m/Y'),
                            'tanggal_harus_kembali' => $item->tanggal_harus_kembali->format('d/m/Y'),
                            'is_late' => $isLate,
                            'days_late' => $daysLate,
                            'catatan' => $item->catatan,
                            'detail_peminjaman' => $item->detailPeminjaman->map(function($detail) {
                                return [
                                    'id' => $detail->id,
                                    'buku_id' => $detail->buku_id,
                                    'judul_buku' => $detail->buku->judul_buku,
                                    'penulis' => $detail->buku->penulis,
                                    'kategori' => $detail->buku->kategori ? $detail->buku->kategori->nama_kategori : 'N/A',
                                    'jumlah' => $detail->jumlah,
                                    'kondisi_kembali' => $detail->kondisi_kembali
                                ];
                            })
                        ];
                    })
                ],
                'message' => 'Anggota ditemukan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
