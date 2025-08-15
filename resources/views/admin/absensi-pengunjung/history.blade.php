@extends('layouts.admin')

@section('title', 'Riwayat Kunjungan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Riwayat Kunjungan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.absensi-pengunjung.index') }}">Absensi Pengunjung</a></li>
                    <li class="breadcrumb-item active">Riwayat Kunjungan</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Total Kunjungan</h6>
                            <h4 class="mb-0" id="total-kunjungan">{{ $totalKunjungan }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Hari Ini</h6>
                            <h4 class="mb-0" id="kunjungan-hari-ini">{{ $kunjunganHariIni }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Minggu Ini</h6>
                            <h4 class="mb-0" id="kunjungan-minggu-ini">{{ $kunjunganMingguIni }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-calendar-week fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-2">Bulan Ini</h6>
                            <h4 class="mb-0" id="kunjungan-bulan-ini">{{ $kunjunganBulanIni }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Pencarian</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" id="filter-start-date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" id="filter-end-date" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cari Anggota</label>
                        <input type="text" id="filter-member" class="form-control" placeholder="Nama atau nomor anggota...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="button" id="search-btn" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <button type="button" id="reset-filter" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" id="export-excel" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button type="button" id="export-pdf" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Kunjungan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Anggota</th>
                            <th>Kelas</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body">
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="d-flex justify-content-center mt-3">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
class HistoryManager {
    constructor() {
        this.currentPage = 1;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCSRF();
        this.loadHistory();
    }

    setupCSRF() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    setupEventListeners() {
        $('#search-btn').on('click', () => this.loadHistory());
        $('#reset-filter').on('click', () => this.resetFilters());
        $('#export-excel').on('click', () => this.exportExcel());
        $('#export-pdf').on('click', () => this.exportPdf());
        
        // Enter key on filter inputs
        $('#filter-start-date, #filter-end-date, #filter-member').on('keypress', (e) => {
            if (e.which === 13) this.loadHistory();
        });
    }

    getFilters() {
        return {
            startDate: $('#filter-start-date').val(),
            endDate: $('#filter-end-date').val(),
            member: $('#filter-member').val(),
            page: this.currentPage
        };
    }

    loadHistory() {
        const filters = this.getFilters();
        
        $('#history-table-body').html(`
            <tr>
                <td colspan="9" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </td>
            </tr>
        `);

        $.ajax({
            url: '{{ route("admin.absensi-pengunjung.history.search") }}',
            method: 'GET',
            data: filters,
            success: (response) => {
                if (response.success) {
                    this.displayHistory(response.data);
                    this.displayPagination(response.pagination);
                    this.updateStatistics(response.statistics);
                } else {
                    this.showMessage(response.message, 'error');
                }
            },
            error: (xhr) => {
                this.showMessage('Terjadi kesalahan saat memuat data', 'error');
            }
        });
    }

    displayHistory(data) {
        const tbody = $('#history-table-body');
        tbody.empty();

        if (data.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Tidak ada data kunjungan ditemukan</p>
                    </td>
                </tr>
            `);
            return;
        }

        data.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${(this.currentPage - 1) * 10 + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.anggota.foto || '/images/default-avatar.png'}" 
                                 alt="Foto" class="rounded-circle mr-2" 
                                 style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <div class="font-weight-bold">${item.anggota.nama_lengkap}</div>
                                <small class="text-muted">${item.anggota.nomor_anggota}</small>
                            </div>
                        </div>
                    </td>
                    <td>${item.anggota.kelas}</td>
                    <td>${item.waktu_masuk}</td>
                    <td>${item.waktu_keluar || '-'}</td>
                    <td>${item.durasi || '-'}</td>
                    <td>
                        <span class="badge badge-${item.status === 'Selesai' ? 'success' : 'warning'}">
                            ${item.status}
                        </span>
                    </td>
                    <td>${item.keterangan || '-'}</td>
                    <td>
                        <a href="{{ route('admin.absensi-pengunjung.show', '') }}/${item.id}" 
                           class="btn btn-sm btn-info" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.absensi-pengunjung.edit', '') }}/${item.id}" 
                           class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    displayPagination(pagination) {
        const container = $('#pagination-container');
        container.empty();

        if (pagination.last_page <= 1) return;

        let paginationHtml = '<ul class="pagination">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        }

        // Page numbers
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        // Next button
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${pagination.current_page + 1}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        }

        paginationHtml += '</ul>';
        container.html(paginationHtml);

        // Add click events
        container.find('.page-link').on('click', (e) => {
            e.preventDefault();
            const page = $(e.target).data('page');
            if (page) {
                this.currentPage = page;
                this.loadHistory();
            }
        });
    }

    updateStatistics(statistics) {
        $('#total-kunjungan').text(statistics.total);
        $('#kunjungan-hari-ini').text(statistics.today);
        $('#kunjungan-minggu-ini').text(statistics.week);
        $('#kunjungan-bulan-ini').text(statistics.month);
    }

    resetFilters() {
        $('#filter-start-date').val('');
        $('#filter-end-date').val('');
        $('#filter-member').val('');
        this.currentPage = 1;
        this.loadHistory();
    }

    exportExcel() {
        const filters = this.getFilters();
        const params = new URLSearchParams(filters);
        window.open(`{{ route('admin.absensi-pengunjung.export-excel') }}?${params.toString()}`, '_blank');
    }

    exportPdf() {
        const filters = this.getFilters();
        const params = new URLSearchParams(filters);
        window.open(`{{ route('admin.absensi-pengunjung.export-pdf') }}?${params.toString()}`, '_blank');
    }

    showMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        $('.alert').remove();
        $('.container-fluid').prepend(alert);
        
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
}

$(document).ready(function() {
    new HistoryManager();
});
</script>
@endpush
