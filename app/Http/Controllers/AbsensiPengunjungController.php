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
        $this->middleware(['auth', 'role:petugas']);
    }

    public function index()
    {
        $absensiHariIni = AbsensiPengunjung::with('anggota')
            ->whereDate('waktu_masuk', today())
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('petugas.absensi-pengunjung.index', compact('absensiHariIni'));
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

        return redirect()->route('petugas.absensi-pengunjung.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
} 