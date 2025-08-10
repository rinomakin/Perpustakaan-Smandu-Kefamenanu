<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiPengunjung;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class AbsensiPengunjungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,PETUGAS']);
    }

    public function index()
    {
        $absensiHariIni = AbsensiPengunjung::with(['anggota.kelas', 'anggota.jurusan'])
            ->whereDate('waktu_masuk', today())
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Statistik
        $totalPengunjungHariIni = $absensiHariIni->count();
        $totalPengunjungBulanIni = AbsensiPengunjung::whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->count();

        // Data untuk chart (7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d/m'),
                'count' => AbsensiPengunjung::whereDate('waktu_masuk', $date)->count()
            ];
        }

        return view('admin.absensi-pengunjung.index', compact(
            'absensiHariIni',
            'totalPengunjungHariIni',
            'totalPengunjungBulanIni',
            'chartData'
        ));
    }

    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        return view('petugas.absensi-pengunjung.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah absen hari ini
        $sudahAbsen = AbsensiPengunjung::where('anggota_id', $request->anggota_id)
            ->whereDate('waktu_masuk', today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anggota sudah melakukan absensi hari ini.');
        }

        AbsensiPengunjung::create([
            'anggota_id' => $request->anggota_id,
            'waktu_masuk' => now(),
            'keterangan' => $request->keterangan,
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('petugas.absensi-pengunjung.index')
            ->with('success', 'Absensi berhasil dicatat.');
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

        return redirect()->route('absensi-pengunjung.index')
            ->with('success', 'Data absensi berhasil dihapus.');
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

            // Catat absensi baru
            $absensi = AbsensiPengunjung::create([
                'anggota_id' => $anggota->id,
                'waktu_masuk' => now(),
                'petugas_id' => Auth::id(),
                'keterangan' => 'Scan Barcode'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'id' => $absensi->id,
                    'nama_lengkap' => $anggota->nama_lengkap,
                    'nomor_anggota' => $anggota->nomor_anggota,
                    'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : '-',
                    'jurusan' => $anggota->jurusan ? $anggota->jurusan->nama_jurusan : '-',
                    'waktu_masuk' => $absensi->waktu_masuk->format('H:i:s'),
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
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->anggota->nama_lengkap,
                    'nomor_anggota' => $item->anggota->nomor_anggota,
                    'kelas' => $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                    'jurusan' => $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                    'waktu_masuk' => $item->waktu_masuk->format('H:i:s'),
                    'foto' => $item->anggota->foto ? asset('storage/' . $item->anggota->foto) : null
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
        if ($request->tanggal_mulai) {
            $query->whereDate('waktu_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->tanggal_selesai) {
            $query->whereDate('waktu_masuk', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan anggota (nama atau nomor anggota)
        if ($request->anggota) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->anggota . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->anggota . '%');
            });
        }

        // Filter berdasarkan barcode
        if ($request->barcode) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('barcode_anggota', $request->barcode);
            });
        }

        $history = $query->orderBy('waktu_masuk', 'desc')
            ->paginate(20)
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->anggota->nama_lengkap,
                    'nomor_anggota' => $item->anggota->nomor_anggota,
                    'kelas' => $item->anggota->kelas ? $item->anggota->kelas->nama_kelas : '-',
                    'jurusan' => $item->anggota->jurusan ? $item->anggota->jurusan->nama_jurusan : '-',
                    'waktu_masuk' => $item->waktu_masuk->format('d/m/Y H:i:s'),
                    'keterangan' => $item->keterangan
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $history->items(),
            'pagination' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total()
            ]
        ]);
    }
} 