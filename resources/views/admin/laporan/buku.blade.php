@extends('layouts.admin')

@section('title', 'Laporan Buku')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Buku</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.laporan.buku') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="jenis">Jenis Buku</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="">Semua Jenis</option>
                                        @foreach($jenis as $j)
                                            <option value="{{ $j->id }}" {{ request('jenis') == $j->id ? 'selected' : '' }}>
                                                {{ $j->nama_jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
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
                                        <a href="{{ route('admin.laporan.buku') }}" class="btn btn-secondary">
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
                            <h5><strong>LAPORAN DATA BUKU PERPUSTAKAAN</strong></h5>
                            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Tabel Data -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Buku</th>
                                        <th>Judul</th>
                                        <th>Pengarang</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Terbit</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Rak</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buku as $index => $b)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $b->kode_buku }}</td>
                                        <td>{{ $b->judul }}</td>
                                        <td>{{ $b->pengarang }}</td>
                                        <td>{{ $b->penerbit }}</td>
                                        <td>{{ $b->tahun_terbit }}</td>
                                        <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                        <td>{{ $b->jenisBuku->nama_jenis ?? '-' }}</td>
                                        <td>{{ $b->rakBuku->nama_rak ?? '-' }}</td>
                                        <td>{{ $b->stok }}</td>
                                        <td>
                                            @if($b->stok > 0)
                                                <span class="badge badge-success">Tersedia</span>
                                            @else
                                                <span class="badge badge-warning">Dipinjam</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data buku</td>
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
                                        <td><strong>Total Buku:</strong></td>
                                        <td>{{ $buku->count() }} judul</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Eksemplar:</strong></td>
                                        <td>{{ $buku->sum('stok') }} buku</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Buku Tersedia:</strong></td>
                                        <td>{{ $buku->where('stok', '>', 0)->count() }} judul</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Buku Dipinjam:</strong></td>
                                        <td>{{ $buku->where('stok', 0)->count() }} judul</td>
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
