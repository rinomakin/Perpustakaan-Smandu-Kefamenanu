@extends('layouts.admin')

@section('title', 'Laporan Anggota')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Anggota</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.laporan.anggota') }}" class="mb-4">
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
                                    <label for="jurusan">Jurusan</label>
                                    <select class="form-control" id="jurusan" name="jurusan">
                                        <option value="">Semua Jurusan</option>
                                        @foreach($jurusan as $j)
                                            <option value="{{ $j->id }}" {{ request('jurusan') == $j->id ? 'selected' : '' }}>
                                                {{ $j->nama_jurusan }}
                                            </option>
                                        @endforeach
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
                                        <a href="{{ route('admin.laporan.anggota') }}" class="btn btn-secondary">
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
                            <h5><strong>LAPORAN DATA ANGGOTA PERPUSTAKAAN</strong></h5>
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
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Jurusan</th>
                                        <th>Kelas</th>
                                        <th>Alamat</th>
                                        <th>No. Telepon</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota as $index => $a)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $a->nis }}</td>
                                        <td>{{ $a->nama }}</td>
                                        <td>{{ $a->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $a->jurusan->nama_jurusan ?? '-' }}</td>
                                        <td>{{ $a->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $a->alamat }}</td>
                                        <td>{{ $a->no_telepon }}</td>
                                        <td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data anggota</td>
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
                                        <td><strong>Total Anggota:</strong></td>
                                        <td>{{ $anggota->count() }} orang</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Laki-laki:</strong></td>
                                        <td>{{ $anggota->where('jenis_kelamin', 'L')->count() }} orang</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Perempuan:</strong></td>
                                        <td>{{ $anggota->where('jenis_kelamin', 'P')->count() }} orang</td>
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
}
</style>

<script>
function printLaporan() {
    window.print();
}
</script>
@endsection
