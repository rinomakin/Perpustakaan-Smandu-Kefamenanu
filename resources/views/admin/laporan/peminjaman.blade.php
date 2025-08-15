@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Peminjaman</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.laporan.peminjaman') }}" class="mb-4">
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
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
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
                                        <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-secondary">
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
                            <h5><strong>LAPORAN DATA PEMINJAMAN BUKU</strong></h5>
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
                                        <th>No. Peminjaman</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Nama Anggota</th>
                                        <th>NIS</th>
                                        <th>Judul Buku</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjaman as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $p->no_peminjaman }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ $p->anggota->nama ?? '-' }}</td>
                                        <td>{{ $p->anggota->nis ?? '-' }}</td>
                                        <td>
                                            @foreach($p->detailPeminjaman as $detail)
                                                <div>{{ $detail->buku->judul ?? '-' }}</div>
                                            @endforeach
                                        </td>
                                        <td>{{ $p->jumlah_buku }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($p->status == 'dipinjam')
                                                <span class="badge badge-warning">Dipinjam</span>
                                            @elseif($p->status == 'dikembalikan')
                                                <span class="badge badge-success">Dikembalikan</span>
                                            @else
                                                <span class="badge badge-danger">Terlambat</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $denda = 0;
                                                if($p->status == 'terlambat') {
                                                    $tglKembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                                                    $tglSekarang = \Carbon\Carbon::now();
                                                    $selisihHari = $tglSekarang->diffInDays($tglKembali);
                                                    $denda = $selisihHari * 1000; // Rp 1.000 per hari
                                                }
                                            @endphp
                                            @if($denda > 0)
                                                Rp {{ number_format($denda, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data peminjaman</td>
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
                                        <td><strong>Total Peminjaman:</strong></td>
                                        <td>{{ $peminjaman->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Buku Dipinjam:</strong></td>
                                        <td>{{ $peminjaman->sum('jumlah_buku') }} buku</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Dipinjam:</strong></td>
                                        <td>{{ $peminjaman->where('status', 'dipinjam')->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Dikembalikan:</strong></td>
                                        <td>{{ $peminjaman->where('status', 'dikembalikan')->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Terlambat:</strong></td>
                                        <td>{{ $peminjaman->where('status', 'terlambat')->count() }} transaksi</td>
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
