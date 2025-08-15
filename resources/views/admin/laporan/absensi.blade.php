@extends('layouts.admin')

@section('title', 'Laporan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Absensi Pengunjung</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.laporan.absensi') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                                           value="{{ request('tanggal_mulai') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal_akhir">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                                           value="{{ request('tanggal_akhir') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="jenis">Jenis Pengunjung</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="">Semua Jenis</option>
                                        <option value="siswa" {{ request('jenis') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                        <option value="guru" {{ request('jenis') == 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="umum" {{ request('jenis') == 'umum' ? 'selected' : '' }}>Umum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.laporan.absensi') }}" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                        <button type="button" class="btn btn-success" onclick="printLaporan()">
                                            <i class="fas fa-print"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Laporan Content -->
                    <div id="laporan-content">
                        <!-- Kop Laporan -->
                        <div class="text-center mb-4" id="kop-laporan">
                            <h4><strong>PERPUSTAKAAN SMAN 1 KEFAMENANU</strong></h4>
                            <p>Jl. Soekarno-Hatta No. 1, Kefamenanu, Timor Tengah Utara, NTT</p>
                            <p>Telp: (0388) 31123 | Email: perpus@sman1kefamenanu.sch.id</p>
                            <hr style="border: 2px solid #000;">
                            <h5><strong>LAPORAN DATA ABSENSI PENGUNJUNG PERPUSTAKAAN</strong></h5>
                            @if(request('tanggal_mulai') && request('tanggal_akhir'))
                                <p>Periode: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</p>
                            @endif
                            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Tabel Data -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pengunjung</th>
                                        <th>Jenis Pengunjung</th>
                                        <th>NIS/NIP</th>
                                        <th>Kelas/Jurusan</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Durasi</th>
                                        <th>Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($absensi as $index => $a)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $a->nama_pengunjung }}</td>
                                        <td>
                                            @if($a->jenis_pengunjung == 'siswa')
                                                <span class="badge badge-primary">Siswa</span>
                                            @elseif($a->jenis_pengunjung == 'guru')
                                                <span class="badge badge-success">Guru</span>
                                            @else
                                                <span class="badge badge-info">Umum</span>
                                            @endif
                                        </td>
                                        <td>{{ $a->nis_nip ?? '-' }}</td>
                                        <td>{{ $a->kelas_jurusan ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($a->waktu_masuk)->format('H:i') }}</td>
                                        <td>
                                            @if($a->waktu_keluar)
                                                {{ \Carbon\Carbon::parse($a->waktu_keluar)->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->waktu_keluar)
                                                @php
                                                    $masuk = \Carbon\Carbon::parse($a->waktu_masuk);
                                                    $keluar = \Carbon\Carbon::parse($a->waktu_keluar);
                                                    $durasi = $masuk->diffInMinutes($keluar);
                                                    $jam = floor($durasi / 60);
                                                    $menit = $durasi % 60;
                                                @endphp
                                                {{ $jam }}j {{ $menit }}m
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $a->tujuan_kunjungan ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data absensi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Ringkasan -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Total Kunjungan:</strong></td>
                                        <td>{{ $absensi->count() }} kunjungan</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Siswa:</strong></td>
                                        <td>{{ $absensi->where('jenis_pengunjung', 'siswa')->count() }} kunjungan</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Guru:</strong></td>
                                        <td>{{ $absensi->where('jenis_pengunjung', 'guru')->count() }} kunjungan</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Umum:</strong></td>
                                        <td>{{ $absensi->where('jenis_pengunjung', 'umum')->count() }} kunjungan</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rata-rata Durasi:</strong></td>
                                        <td>
                                            @php
                                                $totalDurasi = 0;
                                                $countDurasi = 0;
                                                foreach($absensi as $a) {
                                                    if($a->waktu_keluar) {
                                                        $masuk = \Carbon\Carbon::parse($a->waktu_masuk);
                                                        $keluar = \Carbon\Carbon::parse($a->waktu_keluar);
                                                        $totalDurasi += $masuk->diffInMinutes($keluar);
                                                        $countDurasi++;
                                                    }
                                                }
                                                $avgDurasi = $countDurasi > 0 ? $totalDurasi / $countDurasi : 0;
                                                $avgJam = floor($avgDurasi / 60);
                                                $avgMenit = round($avgDurasi % 60);
                                            @endphp
                                            {{ $avgJam }}j {{ $avgMenit }}m
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="row mt-5">
                            <div class="col-md-6 offset-md-6 text-center">
                                <p>Kefamenanu, {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                <p>Kepala Perpustakaan</p>
                                <br><br><br>
                                <p><strong>_________________________</strong></p>
                                <p>NIP. _________________________</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, form {
        display: none !important;
    }
    #laporan-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    .table {
        font-size: 12px;
    }
    #kop-laporan {
        margin-bottom: 20px;
    }
    .badge {
        border: 1px solid #000;
        color: #000 !important;
        background: none !important;
    }
}
</style>

<script>
function printLaporan() {
    window.print();
}
</script>
@endsection
