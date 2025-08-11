<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:pengembalian.manage')->only(['index', 'create', 'store', 'show', 'history']);
        $this->middleware('permission:pengembalian.manage')->only(['searchAnggota', 'getPeminjamanAktif', 'scanBarcode', 'scanBarcodeAnggota']);
    }

    public function index()
    {
        // Hanya tampilkan pengembalian hari ini dengan detail lengkap
        $pengembalian = Pengembalian::with([
            'anggota.kelas', 
            'user', 
            'detailPengembalian.buku.kategoriBuku',
            'peminjaman.detailPeminjaman.buku'
        ])
            ->whereDate('tanggal_pengembalian', today())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        return view('admin.pengembalian.create');
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::with([
            'anggota.kelas', 
            'user', 
            'detailPengembalian.buku.kategoriBuku',
            'peminjaman.detailPeminjaman.buku'
        ])->findOrFail($id);
        
        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Test method to check permission
     */
    public function testPermission()
    {
        $user = auth()->user();
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'has_pengembalian_permission' => $user->hasPermission('pengembalian.manage'),
            'has_peminjaman_permission' => $user->hasPermission('peminjaman.manage'),
            'is_admin' => $user->isAdmin(),
            'role' => $user->role ? $user->role->nama_peran : 'No Role',
            'permissions' => $user->role ? $user->role->permissions->pluck('slug') : []
        ]);
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
            'kondisi_kembali.*' => 'required|in:baik,sedikit_rusak,rusak,hilang',
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
            
            // Calculate total denda
            $dendaPerHari = 1000; // Rp 1000 per hari
            $totalDenda = $daysLate * $dendaPerHari;
            
            // Create pengembalian record
            $pengembalian = Pengembalian::create([
                'nomor_pengembalian' => Pengembalian::generateNomorPengembalian(),
                'peminjaman_id' => $peminjaman->id,
                'anggota_id' => $peminjaman->anggota_id,
                'user_id' => auth()->id(),
                'tanggal_pengembalian' => $tanggalKembali,
                'jam_pengembalian' => $request->jam_kembali ?? now()->format('H:i'),
                'jumlah_hari_terlambat' => $daysLate,
                'total_denda' => $totalDenda,
                'status_denda' => $totalDenda > 0 ? 'belum_dibayar' : 'tidak_ada',
                'catatan' => $request->catatan_pengembalian,
                'status' => 'selesai'
            ]);

            // Create detail pengembalian and update book stock
            $totalDendaBuku = 0;
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $kondisi = $request->kondisi_kembali[$detail->id] ?? 'baik';
                
                // Calculate denda buku berdasarkan kondisi
                $dendaBuku = 0;
                switch ($kondisi) {
                    case 'sedikit_rusak':
                        $dendaBuku = 5000;
                        break;
                    case 'rusak':
                        $dendaBuku = 25000;
                        break;
                    case 'hilang':
                        $dendaBuku = 100000;
                        break;
                }
                $totalDendaBuku += $dendaBuku;
                
                DetailPengembalian::create([
                    'pengembalian_id' => $pengembalian->id,
                    'buku_id' => $detail->buku_id,
                    'detail_peminjaman_id' => $detail->id,
                    'kondisi_kembali' => $kondisi,
                    'jumlah_dikembalikan' => $detail->jumlah ?? 1,
                    'denda_buku' => $dendaBuku,
                    'catatan_buku' => $this->getCatatanBuku($kondisi)
                ]);

                // Update detail peminjaman
                $detail->update([
                    'kondisi_kembali' => $kondisi
                ]);

                // Return book stock if condition is good or damaged (not lost)
                if ($kondisi !== 'hilang') {
                    $detail->buku->increment('stok_tersedia', $detail->jumlah ?? 1);
                }
            }

            // Update total denda jika ada denda buku
            if ($totalDendaBuku > 0) {
                $pengembalian->update([
                    'total_denda' => $totalDenda + $totalDendaBuku
                ]);
            }

            // Update peminjaman status
            $peminjaman->update([
                'tanggal_kembali' => $tanggalKembali,
                'jam_kembali' => $request->jam_kembali ?? now()->format('H:i'),
                'status' => 'dikembalikan',
                'catatan' => $peminjaman->catatan . ($request->catatan_pengembalian ? "\n\nCatatan Pengembalian: " . $request->catatan_pengembalian : '')
            ]);

            // Create denda record if late
            if ($isLate && $totalDenda > 0) {
                Denda::create([
                    'peminjaman_id' => $peminjaman->id,
                    'anggota_id' => $peminjaman->anggota_id,
                    'jumlah_hari_terlambat' => $daysLate,
                    'jumlah_denda' => $totalDenda + $totalDendaBuku,
                    'status_pembayaran' => 'belum_dibayar',
                    'catatan' => "Keterlambatan pengembalian {$daysLate} hari"
                ]);
            }

            DB::commit();
            
            $message = 'Pengembalian berhasil diproses.';
            if ($isLate) {
                $message .= " Anggota terlambat {$daysLate} hari dan dikenakan denda Rp " . number_format($totalDenda + $totalDendaBuku, 0, ',', '.');
            }
            
            return redirect()->route('pengembalian.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get catatan buku berdasarkan kondisi
     */
    private function getCatatanBuku(string $kondisi): ?string
    {
        return match($kondisi) {
            'baik' => 'Buku dalam kondisi baik',
            'sedikit_rusak' => 'Buku sedikit rusak pada bagian cover',
            'rusak' => 'Buku rusak pada beberapa halaman',
            'hilang' => 'Buku tidak ditemukan',
            default => null
        };
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
