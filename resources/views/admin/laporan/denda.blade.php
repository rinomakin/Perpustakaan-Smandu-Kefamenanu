@extends('layouts.admin')

@section('title', 'Laporan Denda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Denda</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.laporan.denda') }}" class="mb-4">
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
                                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
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
                                        <a href="{{ route('admin.laporan.denda') }}" class="btn btn-secondary">
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
                            <h5><strong>LAPORAN DATA DENDA PERPUSTAKAAN</strong></h5>
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
                                        <th>Nama Anggota</th>
                                        <th>NIS</th>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Jumlah Hari</th>
                                        <th>Jumlah Denda</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($denda as $index => $d)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $d->peminjaman->anggota->nama ?? '-' }}</td>
                                        <td>{{ $d->peminjaman->anggota->nis ?? '-' }}</td>
                                        <td>
                                            @foreach($d->peminjaman->detailPeminjaman as $detail)
                                                <div>{{ $detail->buku->judul ?? '-' }}</div>
                                            @endforeach
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($d->peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->peminjaman->tanggal_kembali)->format('d/m/Y') }}</td>
                                        <td>{{ $d->jumlah_hari }} hari</td>
                                        <td>Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}</td>
                                        <td>
                                            @if($d->status == 'belum_bayar')
                                                <span class="badge badge-danger">Belum Bayar</span>
                                            @else
                                                <span class="badge badge-success">Sudah Bayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($d->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($d->tanggal_bayar)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data denda</td>
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
                                        <td><strong>Total Denda:</strong></td>
                                        <td>{{ $denda->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Jumlah Denda:</strong></td>
                                        <td>Rp {{ number_format($denda->sum('jumlah_denda'), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Belum Bayar:</strong></td>
                                        <td>{{ $denda->where('status', 'belum_bayar')->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sudah Bayar:</strong></td>
                                        <td>{{ $denda->where('status', 'sudah_bayar')->count() }} transaksi</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Denda Belum Bayar:</strong></td>
                                        <td>Rp {{ number_format($denda->where('status', 'belum_bayar')->sum('jumlah_denda'), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Denda Sudah Bayar:</strong></td>
                                        <td>Rp {{ number_format($denda->where('status', 'sudah_bayar')->sum('jumlah_denda'), 0, ',', '.') }}</td>
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
