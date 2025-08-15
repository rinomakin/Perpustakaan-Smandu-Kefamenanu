<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiPengunjung;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiPengunjungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,PETUGAS']);
    }

    public function index()
    {
        // Debug: Log tanggal hari ini
        \Log::info('Tanggal hari ini:', ['today' => today()->format('Y-m-d')]);
        
        // Debug: Cek apakah ada data anggota
        $totalAnggota = \App\Models\Anggota::count();
        \Log::info('Total anggota di database:', ['total_anggota' => $totalAnggota]);
        
        // Ambil semua data absensi hari ini dengan relasi
        $absensiHariIni = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan'])
            ->whereDate('waktu_masuk', today())
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Debug: Log jumlah data yang ditemukan
        \Log::info('Data absensi hari ini:', [
            'total_found' => $absensiHariIni->count(),
            'data' => $absensiHariIni->toArray()
        ]);

        // Filter out absensi yang tidak memiliki anggota (jika ada)
        $absensiHariIni = $absensiHariIni->filter(function ($absensi) {
            return $absensi->anggota !== null;
        });

        // Statistik
        $totalPengunjungHariIni = $absensiHariIni->count();
        $totalPengunjungBulanIni = AbsensiPengunjung::whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->count();
        
        // Statistik tambahan
        $sedangBerkunjung = AbsensiPengunjung::whereDate('waktu_masuk', today())
            ->whereNull('waktu_keluar')
            ->count();
        
        $totalKunjungan = AbsensiPengunjung::count();

        return view('admin.absensi-pengunjung.index', compact(
            'absensiHariIni',
            'totalPengunjungHariIni',
            'totalPengunjungBulanIni',
            'sedangBerkunjung',
            'totalKunjungan'
        ));
    }

    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        
        // Determine the correct view based on user role
        if (auth()->user()->hasRole('ADMIN')) {
            return view('admin.absensi-pengunjung.create', compact('anggota'));
        } else {
            return view('petugas.absensi-pengunjung.create', compact('anggota'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'nama_pengunjung' => 'required|string|max:255',
            'tujuan_kunjungan' => 'required|string|max:255',
            'waktu_masuk' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Cek apakah sudah absen pada tanggal yang sama
        $sudahAbsen = AbsensiPengunjung::where('anggota_id', $request->anggota_id)
            ->whereDate('waktu_masuk', $request->waktu_masuk)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anggota sudah melakukan absensi pada tanggal yang sama.')
                ->withInput();
        }

        // Ambil data anggota untuk nama pengunjung jika tidak diisi
        $anggota = Anggota::find($request->anggota_id);
        $namaPengunjung = $request->nama_pengunjung ?: $anggota->nama_lengkap;

        AbsensiPengunjung::create([
            'anggota_id' => $request->anggota_id,
            'nama_pengunjung' => $namaPengunjung,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'waktu_masuk' => $request->waktu_masuk,
            'catatan' => $request->catatan,
            'petugas_id' => Auth::id(),
        ]);

        // Determine the correct redirect route based on user role
        if (auth()->user()->hasRole('ADMIN')) {
            return redirect()->route('admin.absensi-pengunjung.index')
                ->with('success', 'Absensi berhasil dicatat.');
        } else {
            return redirect()->route('petugas.absensi-pengunjung.index')
                ->with('success', 'Absensi berhasil dicatat.');
        }
    }

    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Decode QR code (biasanya berisi nomor anggota atau ID)
        $qrData = $request->qr_code;
        
        // Cari anggota berdasarkan QR code
        $anggota = Anggota::where('barcode_anggota', $qrData)
            ->orWhere('nomor_anggota', $qrData)
            ->first();

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan.'
            ]);
        }

        // Cek apakah sudah absen hari ini
        $sudahAbsen = AbsensiPengunjung::where('anggota_id', $anggota->id)
            ->whereDate('waktu_masuk', today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota sudah melakukan absensi hari ini.'
            ]);
        }

        // Catat absensi
        AbsensiPengunjung::create([
            'anggota_id' => $anggota->id,
            'nama_pengunjung' => $anggota->nama_lengkap,
            'tujuan_kunjungan' => 'Kunjungan Perpustakaan',
            'waktu_masuk' => now(),
            'petugas_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat.',
            'data' => [
                'nama' => $anggota->nama_lengkap,
                'kelas' => $anggota->kelas->nama_kelas ?? '-',
                'waktu' => now()->format('H:i:s')
            ]
        ]);
    }

    public function destroy($id)
    {
        $absensi = AbsensiPengunjung::findOrFail($id);
        $absensi->delete();

        // Determine the correct route based on user role
        if (auth()->user()->hasRole('ADMIN')) {
            return redirect()->route('admin.absensi-pengunjung.index')
                ->with('success', 'Data absensi berhasil dihapus.');
        } else {
            return redirect()->route('petugas.absensi-pengunjung.index')
                ->with('success', 'Data absensi berhasil dihapus.');
        }
    }

    /**
     * Show individual attendance record
     */
    public function show($id)
    {
        $absensi = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan', 'petugas'])
            ->findOrFail($id);

        return view('admin.absensi-pengunjung.show', compact('absensi'));
    }

    /**
     * Show edit form for attendance record
     */
    public function edit($id)
    {
        $absensi = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan'])
            ->findOrFail($id);
        
        $anggota = Anggota::where('status', 'aktif')->get();

        return view('admin.absensi-pengunjung.edit', compact('absensi', 'anggota'));
    }

    /**
     * Update attendance record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'waktu_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = AbsensiPengunjung::findOrFail($id);

        // Check if the member has another attendance record on the same date (excluding current record)
        $existingAttendance = AbsensiPengunjung::where('anggota_id', $request->anggota_id)
            ->whereDate('waktu_masuk', $request->waktu_masuk)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingAttendance) {
            return back()->with('error', 'Anggota sudah memiliki absensi pada tanggal yang sama.')
                ->withInput();
        }

        $absensi->update([
            'anggota_id' => $request->anggota_id,
            'waktu_masuk' => $request->waktu_masuk,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.absensi-pengunjung.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Scan barcode untuk absensi
     */
    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        try {
            // Cari anggota berdasarkan barcode
            $anggota = Anggota::where('barcode_anggota', $request->barcode)
                ->orWhere('nomor_anggota', $request->barcode)
                ->first();

            if (!$anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota dengan barcode tersebut tidak ditemukan'
                ]);
            }

            if ($anggota->status !== 'aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status anggota tidak aktif'
                ]);
            }

            // Cek apakah sudah absen hari ini
            $sudahAbsen = AbsensiPengunjung::where('anggota_id', $anggota->id)
                ->whereDate('waktu_masuk', today())
                ->first();

            if ($sudahAbsen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota sudah melakukan absensi hari ini pada ' . $sudahAbsen->waktu_masuk->format('H:i:s')
                ]);
            }

            // Return member data for auto-loading form (don't create attendance yet)
            return response()->json([
                'success' => true,
                'message' => 'Anggota ditemukan',
                'data' => [
                    'id' => $anggota->id,
                    'nama_lengkap' => $anggota->nama_lengkap ?? 'Nama Tidak Tersedia',
                    'nomor_anggota' => $anggota->nomor_anggota ?? 'N/A',
                    'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : '-',
                    'jurusan' => $anggota->jurusan ? $anggota->jurusan->nama_jurusan : '-',
                    'foto' => $anggota->foto ? asset('storage/' . $anggota->foto) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get pengunjung hari ini untuk AJAX
     */
    public function todayVisitors()
    {
        $visitors = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan'])
            ->whereDate('waktu_masuk', today())
            ->orderBy('waktu_masuk', 'desc')
            ->get()
            ->map(function ($item) {
                // Log untuk debug jika ada masalah dengan data
                if (!$item->anggota) {
                    \Log::warning("Absensi ID {$item->id} tidak memiliki relasi anggota");
                }
                
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->anggota ? $item->anggota->nama_lengkap : 'Nama Tidak Tersedia',
                    'nomor_anggota' => $item->anggota ? $item->anggota->nomor_anggota : 'N/A',
                    'kelas' => $item->anggota && $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                    'jurusan' => $item->anggota && $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                    'waktu_masuk' => $item->waktu_masuk->format('H:i:s'),
                    'foto' => $item->anggota && $item->anggota->foto ? asset('storage/' . $item->anggota->foto) : null
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $visitors,
            'total' => $visitors->count()
        ]);
    }

    /**
     * Search history absensi
     */
    public function searchHistory(Request $request)
    {
        $query = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan']);

        // Filter berdasarkan tanggal
        if ($request->startDate) {
            $query->whereDate('waktu_masuk', '>=', $request->startDate);
        }

        if ($request->endDate) {
            $query->whereDate('waktu_masuk', '<=', $request->endDate);
        }

        // Filter berdasarkan anggota (nama atau nomor anggota)
        if ($request->member) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->member . '%');
            });
        }

        // Filter berdasarkan barcode
        if ($request->barcode) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('barcode_anggota', $request->barcode);
            });
        }

        $perPage = $request->per_page ?? 20;
        $history = $query->orderBy('waktu_masuk', 'desc')
            ->paginate($perPage)
            ->through(function ($item) {
                // Calculate duration if exit time exists
                $durasi = null;
                if ($item->waktu_keluar) {
                    $masuk = \Carbon\Carbon::parse($item->waktu_masuk);
                    $keluar = \Carbon\Carbon::parse($item->waktu_keluar);
                    $durasi = $masuk->diffForHumans($keluar, true);
                }

                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->anggota ? $item->anggota->nama_lengkap : 'Nama Tidak Tersedia',
                    'nomor_anggota' => $item->anggota ? $item->anggota->nomor_anggota : 'N/A',
                    'kelas' => $item->anggota && $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                    'jurusan' => $item->anggota && $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                    'waktu_masuk' => $item->waktu_masuk->format('d/m/Y H:i:s'),
                    'waktu_keluar' => $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i:s') : null,
                    'durasi' => $durasi,
                    'keterangan' => $item->keterangan,
                    'foto' => $item->anggota && $item->anggota->foto ? asset('storage/' . $item->anggota->foto) : null
                ];
            });

        // Get statistics
        $statistics = [
            'total' => AbsensiPengunjung::count(),
            'today' => AbsensiPengunjung::whereDate('waktu_masuk', today())->count(),
            'week' => AbsensiPengunjung::whereBetween('waktu_masuk', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => AbsensiPengunjung::whereMonth('waktu_masuk', now()->month)->whereYear('waktu_masuk', now()->year)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $history->items(),
            'pagination' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total()
            ],
            'statistics' => $statistics
        ]);
    }

    /**
     * Search members for attendance
     */
    public function searchMembers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 2 karakter untuk pencarian'
            ]);
        }

        $members = Anggota::where('status', 'aktif')
            ->where(function($q) use ($query) {
                $q->where('nama_lengkap', 'like', '%' . $query . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $query . '%')
                  ->orWhere('barcode_anggota', 'like', '%' . $query . '%');
            })
            ->with(['kelas', 'jurusan'])
            ->limit(10)
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'nama_lengkap' => $member->nama_lengkap,
                    'nomor_anggota' => $member->nomor_anggota,
                    'barcode_anggota' => $member->barcode_anggota,
                    'kelas' => $member->kelas ? $member->kelas->nama_kelas : '-',
                    'jurusan' => $member->jurusan ? $member->jurusan->nama_jurusan : '-',
                    'status' => $member->status,
                    'foto' => $member->foto ? asset('storage/' . $member->foto) : null
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }

    /**
     * Store attendance via AJAX
     */
    public function storeAjax(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'waktu_masuk' => 'nullable|date',
            'tujuan_kunjungan' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah absen hari ini
        $sudahAbsen = AbsensiPengunjung::where('anggota_id', $request->anggota_id)
            ->whereDate('waktu_masuk', today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota sudah melakukan absensi hari ini.'
            ]);
        }

        // Konversi ID tujuan kunjungan ke text
        $tujuanKunjunganMap = [
            '1' => 'Membaca Buku',
            '2' => 'Meminjam Buku',
            '3' => 'Mengembalikan Buku',
            '4' => 'Belajar/Kerja Kelompok',
            '5' => 'Konsultasi dengan Petugas',
            '6' => 'Menggunakan Komputer/Internet',
            '7' => 'Mengikuti Kegiatan Perpustakaan',
            '8' => 'Lainnya'
        ];

        $tujuanKunjunganText = $tujuanKunjunganMap[$request->tujuan_kunjungan] ?? $request->tujuan_kunjungan;

        // Ambil data anggota
        $anggota = \App\Models\Anggota::find($request->anggota_id);

        try {
            $absensi = AbsensiPengunjung::create([
                'anggota_id' => $request->anggota_id,
                'nama_pengunjung' => $anggota->nama_lengkap,
                'tujuan_kunjungan' => $tujuanKunjunganText,
                'waktu_masuk' => $request->waktu_masuk ?? now(),
                'status' => 'masuk',
                'catatan' => $request->keterangan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat!',
                'data' => $absensi
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating absensi:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Record exit time for visitor
     */
    public function recordExit(Request $request)
    {
        $request->validate([
            'absensi_id' => 'required|exists:absensi_pengunjung,id',
        ]);

        $absensi = AbsensiPengunjung::findOrFail($request->absensi_id);

        // Check if already has exit time
        if ($absensi->waktu_keluar) {
            return response()->json([
                'success' => false,
                'message' => 'Pengunjung sudah mencatat waktu keluar.'
            ]);
        }

        $absensi->update([
            'waktu_keluar' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Waktu keluar berhasil dicatat.',
            'data' => [
                'waktu_keluar' => $absensi->waktu_keluar->format('H:i:s')
            ]
        ]);
    }

    /**
     * Show history page
     */
    public function history()
    {
        $totalKunjungan = AbsensiPengunjung::count();
        $kunjunganHariIni = AbsensiPengunjung::whereDate('waktu_masuk', today())->count();
        $kunjunganMingguIni = AbsensiPengunjung::whereBetween('waktu_masuk', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $kunjunganBulanIni = AbsensiPengunjung::whereMonth('waktu_masuk', now()->month)->whereYear('waktu_masuk', now()->year)->count();

        return view('admin.absensi-pengunjung.history', compact(
            'totalKunjungan',
            'kunjunganHariIni',
            'kunjunganMingguIni',
            'kunjunganBulanIni'
        ));
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $query = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan']);

        // Apply filters
        if ($request->startDate) {
            $query->whereDate('waktu_masuk', '>=', $request->startDate);
        }

        if ($request->endDate) {
            $query->whereDate('waktu_masuk', '<=', $request->endDate);
        }

        if ($request->member) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->member . '%');
            });
        }

        $data = $query->orderBy('waktu_masuk', 'desc')->get();

        $filename = 'riwayat_kunjungan_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new class($data) implements FromCollection, WithHeadings, WithMapping {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'No',
                    'Nama Anggota',
                    'Nomor Anggota',
                    'Kelas',
                    'Jurusan',
                    'Waktu Masuk',
                    'Waktu Keluar',
                    'Durasi',
                    'Status',
                    'Keterangan'
                ];
            }

            public function map($item): array
            {
                static $no = 1;
                
                $durasi = null;
                if ($item->waktu_keluar) {
                    $masuk = Carbon::parse($item->waktu_masuk);
                    $keluar = Carbon::parse($item->waktu_keluar);
                    $durasi = $masuk->diffForHumans($keluar, true);
                }

                $status = $item->waktu_keluar ? 'Selesai' : 'Sedang Berkunjung';

                return [
                    $no++,
                    $item->anggota ? $item->anggota->nama_lengkap : 'N/A',
                    $item->anggota ? $item->anggota->nomor_anggota : 'N/A',
                    $item->anggota && $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                    $item->anggota && $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                    $item->waktu_masuk->format('d/m/Y H:i:s'),
                    $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i:s') : '-',
                    $durasi ?? '-',
                    $status,
                    $item->keterangan ?? '-'
                ];
            }
        }, $filename);
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan']);

        // Apply filters
        if ($request->startDate) {
            $query->whereDate('waktu_masuk', '>=', $request->startDate);
        }

        if ($request->endDate) {
            $query->whereDate('waktu_masuk', '<=', $request->endDate);
        }

        if ($request->member) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->member . '%');
            });
        }

        $data = $query->orderBy('waktu_masuk', 'desc')->get()->map(function ($item) {
            $durasi = null;
            if ($item->waktu_keluar) {
                $durasi = Carbon::parse($item->waktu_masuk)->diffForHumans($item->waktu_keluar, true);
            }

            return [
                'nama_lengkap' => $item->anggota ? $item->anggota->nama_lengkap : 'N/A',
                'nomor_anggota' => $item->anggota ? $item->anggota->nomor_anggota : 'N/A',
                'kelas' => $item->anggota && $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                'jurusan' => $item->anggota && $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                'waktu_masuk' => $item->waktu_masuk->format('d/m/Y H:i:s'),
                'waktu_keluar' => $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i:s') : '-',
                'durasi' => $durasi,
                'keterangan' => $item->keterangan ?? '-'
            ];
        });

        $pdf = PDF::loadView('admin.absensi-pengunjung.pdf', [
            'data' => $data,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'member' => $request->member
        ]);
        
        return $pdf->download('riwayat_kunjungan_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Create test data for debugging
     */
    public function createTestData()
    {
        try {
            // Cek apakah ada anggota
            $anggota = \App\Models\Anggota::first();
            
            if (!$anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data anggota di database'
                ]);
            }

            // Cek apakah sudah ada absensi hari ini
            $existingAbsensi = AbsensiPengunjung::where('anggota_id', $anggota->id)
                ->whereDate('waktu_masuk', today())
                ->exists();

            if ($existingAbsensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota sudah memiliki absensi hari ini'
                ]);
            }

            // Buat data test dengan field yang sudah ada di database
            $absensi = AbsensiPengunjung::create([
                'anggota_id' => $anggota->id,
                'nama_pengunjung' => $anggota->nama_lengkap,
                'tujuan_kunjungan' => 'Membaca Buku',
                'waktu_masuk' => now(),
                'status' => 'masuk',
                'catatan' => 'Data test untuk debugging'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data test berhasil dibuat! ID: ' . $absensi->id,
                'data' => $absensi
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating test data:', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Debug method to check database data
     */
    public function debugData()
    {
        try {
            $totalAbsensi = AbsensiPengunjung::count();
            $absensiHariIni = AbsensiPengunjung::whereDate('waktu_masuk', today())->count();
            $totalAnggota = \App\Models\Anggota::count();
            
            $data = [
                'total_absensi' => $totalAbsensi,
                'absensi_hari_ini' => $absensiHariIni,
                'total_anggota' => $totalAnggota,
                'today' => today()->format('Y-m-d'),
                'sample_absensi' => AbsensiPengunjung::with('anggota')->first(),
                'sample_anggota' => \App\Models\Anggota::first()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
} 