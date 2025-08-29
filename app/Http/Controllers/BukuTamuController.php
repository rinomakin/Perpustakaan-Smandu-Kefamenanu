<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuTamu;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuTamuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN,PETUGAS']);
    }

    public function index()
    {
        $kunjunganHariIni = BukuTamu::with(['anggota.kelas', 'anggota.jurusan'])
            ->whereDate('waktu_datang', today())
            ->orderBy('waktu_datang', 'desc')
            ->get();

        $totalTamuHariIni = $kunjunganHariIni->count();
        $totalTamuBulanIni = BukuTamu::whereMonth('waktu_datang', now()->month)->whereYear('waktu_datang', now()->year)->count();
        $sedangBerkunjung = BukuTamu::whereDate('waktu_datang', today())->whereNull('waktu_pulang')->count();
        $totalKunjungan = BukuTamu::count();

        return view('admin.buku-tamu.index', compact(
            'kunjunganHariIni', 'totalTamuHariIni', 'totalTamuBulanIni', 'sedangBerkunjung', 'totalKunjungan'
        ));
    }

    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        
        if (auth()->user()->hasRole('ADMIN')) {
            return view('admin.buku-tamu.create', compact('anggota'));
        } else {
            return view('petugas.buku-tamu.create', compact('anggota'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'nullable|exists:anggota,id',
            'nama_tamu' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'keperluan' => 'required|string|max:255',
            'waktu_datang' => 'required|date',
            'no_telepon' => 'nullable|string|max:20',
            'status_kunjungan' => 'nullable|in:datang,pulang',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($request->anggota_id) {
            $sudahBerkunjung = BukuTamu::where('anggota_id', $request->anggota_id)
                ->whereDate('waktu_datang', $request->waktu_datang)->exists();

            if ($sudahBerkunjung) {
                return back()->with('error', 'Anggota sudah tercatat berkunjung pada tanggal yang sama.')->withInput();
            }
            
            $anggota = Anggota::find($request->anggota_id);
            $namaTamu = $request->nama_tamu ?: $anggota->nama_lengkap;
        } else {
            $namaTamu = $request->nama_tamu;
        }

        BukuTamu::create([
            'anggota_id' => $request->anggota_id,
            'nama_tamu' => $namaTamu,
            'instansi' => $request->instansi,
            'keperluan' => $request->keperluan,
            'waktu_datang' => $request->waktu_datang,
            'no_telepon' => $request->no_telepon,
            'status_kunjungan' => $request->status_kunjungan ?: 'datang',
            'keterangan' => $request->keterangan,
            'petugas_id' => Auth::id(),
        ]);

        if (auth()->user()->hasRole('ADMIN')) {
            return redirect()->route('admin.buku-tamu.index')->with('success', 'Kunjungan berhasil dicatat di buku tamu.');
        } else {
            return redirect()->route('petugas.buku-tamu.index')->with('success', 'Kunjungan berhasil dicatat di buku tamu.');
        }
    }

    public function destroy($id)
    {
        $kunjungan = BukuTamu::findOrFail($id);
        $kunjungan->delete();

        if (auth()->user()->hasRole('ADMIN')) {
            return redirect()->route('admin.buku-tamu.index')->with('success', 'Data kunjungan berhasil dihapus.');
        } else {
            return redirect()->route('petugas.buku-tamu.index')->with('success', 'Data kunjungan berhasil dihapus.');
        }
    }

    public function show($id)
    {
        $kunjungan = BukuTamu::with(['anggota.kelas', 'anggota.jurusan', 'petugas'])->findOrFail($id);
        return view('admin.buku-tamu.show', compact('kunjungan'));
    }

    public function edit($id)
    {
        $kunjungan = BukuTamu::with(['anggota.kelas', 'anggota.jurusan'])->findOrFail($id);
        $anggota = Anggota::where('status', 'aktif')->get();
        return view('admin.buku-tamu.edit', compact('kunjungan', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'anggota_id' => 'nullable|exists:anggota,id',
            'nama_tamu' => 'required_without:anggota_id|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'keperluan' => 'required|string|max:255',
            'waktu_datang' => 'required|date',
            'no_telepon' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $kunjungan = BukuTamu::findOrFail($id);

        if ($request->anggota_id) {
            $existingVisit = BukuTamu::where('anggota_id', $request->anggota_id)
                ->whereDate('waktu_datang', $request->waktu_datang)
                ->where('id', '!=', $id)->exists();

            if ($existingVisit) {
                return back()->with('error', 'Anggota sudah memiliki catatan kunjungan pada tanggal yang sama.')->withInput();
            }
            
            $anggota = Anggota::find($request->anggota_id);
            $namaTamu = $request->nama_tamu ?: $anggota->nama_lengkap;
        } else {
            $namaTamu = $request->nama_tamu;
        }

        $kunjungan->update([
            'anggota_id' => $request->anggota_id,
            'nama_tamu' => $namaTamu,
            'instansi' => $request->instansi,
            'keperluan' => $request->keperluan,
            'waktu_datang' => $request->waktu_datang,
            'no_telepon' => $request->no_telepon,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.buku-tamu.index')->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    public function recordExit(Request $request)
    {
        $request->validate(['kunjungan_id' => 'required|exists:buku_tamu,id']);
        
        $kunjungan = BukuTamu::findOrFail($request->kunjungan_id);

        if ($kunjungan->waktu_pulang) {
            return response()->json(['success' => false, 'message' => 'Tamu sudah mencatat waktu pulang.']);
        }

        $kunjungan->update(['waktu_pulang' => now(), 'status_kunjungan' => 'pulang']);

        return response()->json([
            'success' => true,
            'message' => 'Waktu pulang berhasil dicatat.',
            'data' => ['waktu_pulang' => $kunjungan->waktu_pulang->format('H:i:s')]
        ]);
    }

    public function history()
    {
        $totalKunjungan = BukuTamu::count();
        $kunjunganHariIni = BukuTamu::whereDate('waktu_datang', today())->count();
        $kunjunganMingguIni = BukuTamu::whereBetween('waktu_datang', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $kunjunganBulanIni = BukuTamu::whereMonth('waktu_datang', now()->month)->whereYear('waktu_datang', now()->year)->count();

        return view('admin.buku-tamu.history', compact('totalKunjungan', 'kunjunganHariIni', 'kunjunganMingguIni', 'kunjunganBulanIni'));
    }

    public function searchMembers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['success' => false, 'message' => 'Minimal 2 karakter untuk pencarian']);
        }

        $members = Anggota::where('status', 'aktif')
            ->where(function($q) use ($query) {
                $q->where('nama_lengkap', 'like', '%' . $query . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $query . '%')
                  ->orWhere('barcode_anggota', 'like', '%' . $query . '%');
            })
            ->with(['kelas', 'jurusan'])->limit(10)->get()
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

        return response()->json(['success' => true, 'data' => $members]);
    }

    public function scanBarcode(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);
        
        $member = Anggota::where('status', 'aktif')
            ->where(function($q) use ($request) {
                $q->where('barcode_anggota', $request->barcode)
                  ->orWhere('nomor_anggota', $request->barcode);
            })
            ->with(['kelas', 'jurusan'])->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Anggota tidak ditemukan']);
        }

        // Check if member already visited today
        $existingVisit = BukuTamu::where('anggota_id', $member->id)
            ->whereDate('waktu_datang', today())->exists();

        if ($existingVisit) {
            return response()->json(['success' => false, 'message' => 'Anggota sudah tercatat berkunjung hari ini']);
        }

        $memberData = [
            'id' => $member->id,
            'nama_lengkap' => $member->nama_lengkap,
            'nomor_anggota' => $member->nomor_anggota,
            'barcode_anggota' => $member->barcode_anggota,
            'kelas' => $member->kelas ? $member->kelas->nama_kelas : '-',
            'jurusan' => $member->jurusan ? $member->jurusan->nama_jurusan : '-',
            'status' => $member->status,
            'foto' => $member->foto ? asset('storage/' . $member->foto) : null
        ];

        return response()->json(['success' => true, 'data' => $memberData, 'message' => 'Anggota ditemukan']);
    }

    public function searchHistory(Request $request)
    {
        $query = BukuTamu::with(['anggota.kelas', 'anggota.jurusan']);

        // Apply filters
        if ($request->startDate) {
            $query->whereDate('waktu_datang', '>=', $request->startDate);
        }

        if ($request->endDate) {
            $query->whereDate('waktu_datang', '<=', $request->endDate);
        }

        if ($request->member) {
            $query->whereHas('anggota', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $request->member . '%');
            });
        }

        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = $query->count();
        $data = $query->orderBy('waktu_datang', 'desc')
                     ->offset($offset)
                     ->limit($perPage)
                     ->get()
                     ->map(function($item) {
                         return [
                             'id' => $item->id,
                             'anggota' => [
                                 'nama_lengkap' => $item->anggota->nama_lengkap ?? $item->nama_tamu,
                                 'nomor_anggota' => $item->anggota->nomor_anggota ?? '-',
                                 'foto' => $item->anggota->foto ? asset('storage/' . $item->anggota->foto) : null,
                                 'kelas' => $item->anggota->kelas->nama_kelas ?? '-',
                             ],
                             'waktu_masuk' => $item->waktu_datang->format('d/m/Y H:i'),
                             'waktu_keluar' => $item->waktu_pulang ? $item->waktu_pulang->format('d/m/Y H:i') : null,
                             'durasi' => $item->waktu_pulang ? $item->waktu_datang->diff($item->waktu_pulang)->format('%H:%I:%S') : null,
                             'status' => $item->waktu_pulang ? 'Selesai' : 'Berkunjung',
                             'keterangan' => $item->keterangan,
                         ];
                     });

        $lastPage = ceil($total / $perPage);

        $statistics = [
            'total' => BukuTamu::count(),
            'today' => BukuTamu::whereDate('waktu_datang', today())->count(),
            'week' => BukuTamu::whereBetween('waktu_datang', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => BukuTamu::whereMonth('waktu_datang', now()->month)->whereYear('waktu_datang', now()->year)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'total' => $total
            ],
            'statistics' => $statistics
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = BukuTamu::with(['anggota.kelas', 'anggota.jurusan']);

        // Apply same filters as search
        if ($request->startDate) {
            $query->whereDate('waktu_datang', '>=', $request->startDate);
        }
        if ($request->endDate) {
            $query->whereDate('waktu_datang', '<=', $request->endDate);
        }
        if ($request->member) {
            $query->whereHas('anggota', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%');
            });
        }

        $data = $query->orderBy('waktu_datang', 'desc')->get();

        return Excel::download(new class($data) implements FromCollection, WithHeadings, WithMapping {
            private $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function collection() {
                return $this->data;
            }
            
            public function headings(): array {
                return ['Tanggal', 'Nama', 'Nomor Anggota', 'Kelas', 'Keperluan', 'Waktu Datang', 'Waktu Pulang', 'Status'];
            }
            
            public function map($row): array {
                return [
                    $row->waktu_datang->format('d/m/Y'),
                    $row->anggota->nama_lengkap ?? $row->nama_tamu,
                    $row->anggota->nomor_anggota ?? '-',
                    $row->anggota->kelas->nama_kelas ?? '-',
                    $row->keperluan,
                    $row->waktu_datang->format('H:i'),
                    $row->waktu_pulang ? $row->waktu_pulang->format('H:i') : '-',
                    $row->waktu_pulang ? 'Selesai' : 'Berkunjung'
                ];
            }
        }, 'riwayat-buku-tamu-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = BukuTamu::with(['anggota.kelas', 'anggota.jurusan']);

        // Apply same filters as search
        if ($request->startDate) {
            $query->whereDate('waktu_datang', '>=', $request->startDate);
        }
        if ($request->endDate) {
            $query->whereDate('waktu_datang', '<=', $request->endDate);
        }
        if ($request->member) {
            $query->whereHas('anggota', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->member . '%');
            });
        }

        $data = $query->orderBy('waktu_datang', 'desc')->get();
        
        $pdf = Pdf::loadView('admin.buku-tamu.pdf.history', compact('data'));
        
        return $pdf->download('riwayat-buku-tamu-' . now()->format('Y-m-d') . '.pdf');
    }
}