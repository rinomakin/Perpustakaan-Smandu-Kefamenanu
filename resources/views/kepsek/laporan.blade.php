@extends('layouts.kepsek')

@section('title', 'Laporan Perpustakaan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Laporan Perpustakaan</h1>
                    <p class="mb-0 text-muted">Laporan dan Analisis Data Perpustakaan Tahun {{ now()->year }}</p>
                </div>
                <div>
                    <a href="{{ route('kepsek.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Peminjaman Bulanan {{ now()->year }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="laporanChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Tables -->
    <div class="row">
        <!-- Monthly Report Table -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Laporan Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center">Bulan</th>
                                    <th class="text-center">Total Peminjaman</th>
                                    <th class="text-center">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPeminjaman = $laporanBulanan->sum('total');
                                    $namaBulan = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                @endphp
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $databulan = $laporanBulanan->where('bulan', $i)->first();
                                        $jumlah = $databulan ? $databulan->total : 0;
                                        $persentase = $totalPeminjaman > 0 ? ($jumlah / $totalPeminjaman) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $namaBulan[$i] }}</td>
                                        <td class="text-center">{{ number_format($jumlah) }}</td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $persentase }}%"
                                                     aria-valuenow="{{ $persentase }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($persentase, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-center">{{ number_format($totalPeminjaman) }}</th>
                                    <th class="text-center">100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Statistik</h6>
                </div>
                <div class="card-body">
                    @php
                        $rataRata = $laporanBulanan->avg('total');
                        $maxPeminjaman = $laporanBulanan->max('total');
                        $minPeminjaman = $laporanBulanan->min('total');
                        $bulanTertinggi = $laporanBulanan->where('total', $maxPeminjaman)->first();
                        $bulanTerendah = $laporanBulanan->where('total', $minPeminjaman)->first();
                    @endphp
                    
                    <div class="row no-gutters">
                        <div class="col-12 mb-3">
                            <div class="border-left-primary p-3">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Rata-rata per Bulan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($rataRata, 0) }} buku
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="border-left-success p-3">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Peminjaman Tertinggi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($maxPeminjaman) }} buku
                                </div>
                                @if($bulanTertinggi)
                                    <small class="text-muted">
                                        {{ $namaBulan[$bulanTertinggi->bulan] }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="border-left-warning p-3">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Peminjaman Terendah
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($minPeminjaman) }} buku
                                </div>
                                @if($bulanTerendah)
                                    <small class="text-muted">
                                        {{ $namaBulan[$bulanTerendah->bulan] }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-print mr-1"></i>
                            Cetak Laporan
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-file-excel mr-1"></i>
                            Export Excel
                        </button>
                        <button class="btn btn-info btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Catatan Laporan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Metodologi:</h6>
                            <ul class="small text-muted">
                                <li>Data dihitung berdasarkan tanggal peminjaman</li>
                                <li>Grafik menampilkan total peminjaman per bulan</li>
                                <li>Persentase dihitung dari total peminjaman tahun ini</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Update Terakhir:</h6>
                            <p class="small text-muted">
                                {{ now()->format('d F Y, H:i:s') }} WIB
                            </p>
                            <h6 class="font-weight-bold">Periode Data:</h6>
                            <p class="small text-muted">
                                1 Januari {{ now()->year }} - {{ now()->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data untuk Chart
const chartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
    datasets: [{
        label: 'Peminjaman Buku',
        data: [
            @for($i = 1; $i <= 12; $i++)
                {{ $laporanBulanan->where('bulan', $i)->first()->total ?? 0 }}{{ $i < 12 ? ',' : '' }}
            @endfor
        ],
        backgroundColor: 'rgba(78, 115, 223, 0.2)',
        borderColor: 'rgba(78, 115, 223, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
    }]
};

// Konfigurasi Chart
const config = {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Grafik Peminjaman Buku per Bulan'
            },
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
};

// Render Chart
const ctx = document.getElementById('laporanChart').getContext('2d');
const laporanChart = new Chart(ctx, config);

// Functions
function refreshData() {
    Swal.fire({
        title: 'Memperbarui Data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function exportToExcel() {
    Swal.fire({
        title: 'Export ke Excel',
        text: 'Fitur export akan segera tersedia',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

// Print styles
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>

<style>
@media print {
    .btn, .card-header, .no-print {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
}
</style>
@endsection
