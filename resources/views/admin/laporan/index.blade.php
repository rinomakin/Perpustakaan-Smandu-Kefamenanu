@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .report-card {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .report-card.anggota {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .report-card.buku {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .report-card.peminjaman {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .report-card.pengembalian {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .report-card.denda {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .report-card.kas {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    .report-card.absensi {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    }
</style>

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Perpustakaan</h1>
                <!-- <p class="text-gray-600 mt-1">Kelola dan unduh berbagai laporan perpustakaan dengan filter tanggal yang fleksibel</p> -->
            </div>
            
            <!-- Date Range Filter -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <input type="date" id="globalStartDate" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" id="globalEndDate" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button onclick="applyGlobalDateFilter()" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Laporan Anggota -->
        @if(Auth::user()->hasPermission('laporan.anggota') || Auth::user()->isAdmin())
        <div class="report-card anggota rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('anggota')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Anggota</h3>
                    <p class="text-white/80 text-sm mt-1">Data anggota perpustakaan</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif

        <!-- Laporan Buku -->
        @if(Auth::user()->hasPermission('laporan.buku') || Auth::user()->isAdmin())
        <div class="report-card buku rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('buku')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Buku</h3>
                    <p class="text-white/80 text-sm mt-1">Data koleksi buku</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif

        <!-- Laporan Peminjaman -->
        @if(Auth::user()->hasPermission('laporan.peminjaman') || Auth::user()->isAdmin())
        <div class="report-card peminjaman rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('peminjaman')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Peminjaman</h3>
                    <p class="text-white/80 text-sm mt-1">Data transaksi peminjaman</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-book-reader text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif

        <!-- Laporan Pengembalian -->
        @if(Auth::user()->hasPermission('laporan.pengembalian') || Auth::user()->isAdmin())
        <div class="report-card pengembalian rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('pengembalian')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Pengembalian</h3>
                    <p class="text-white/80 text-sm mt-1">Data transaksi pengembalian</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-undo text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif

        <!-- Laporan Denda -->
        @if(Auth::user()->hasPermission('laporan.denda') || Auth::user()->isAdmin())
        <div class="report-card denda rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('denda')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Denda</h3>
                    <p class="text-white/80 text-sm mt-1">Data denda keterlambatan</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif

        <!-- Laporan Kas -->
        <!-- @if(Auth::user()->hasPermission('laporan.kas') || Auth::user()->isAdmin())
        <div class="report-card kas rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('kas')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Kas</h3>
                    <p class="text-white/80 text-sm mt-1">Data pemasukan kas perpustakaan</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif -->

        <!-- Laporan Absensi -->
        @if(Auth::user()->hasPermission('laporan.absensi') || Auth::user()->isAdmin())
        <div class="report-card absensi rounded-xl p-6 text-white cursor-pointer" onclick="openReportModal('absensi')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Laporan Absensi</h3>
                    <p class="text-white/80 text-sm mt-1">Data kunjungan pengunjung</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Klik untuk mengunduh</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full">
            <div id="modalHeader" class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-lg font-semibold text-white">Unduh Laporan</h3>
                    <button onclick="closeReportModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="reportForm">
                    <!-- Date Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="date" id="modalStartDate" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <label class="text-xs text-gray-500 mt-1">Tanggal Mulai</label>
                            </div>
                            <div>
                                <input type="date" id="modalEndDate" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <label class="text-xs text-gray-500 mt-1">Tanggal Akhir</label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Filters -->
                    <div id="additionalFilters" class="space-y-4 mb-4">
                        <!-- Filters will be dynamically added based on report type -->
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeReportModal()" 
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="button" onclick="viewReport()" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>Lihat
                        </button>
                        <button type="button" onclick="downloadReport()" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i>Unduh
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Memproses laporan...</span>
    </div>
</div>

<script>
let currentReportType = '';

document.addEventListener('DOMContentLoaded', function() {
    // Set default dates (current month)
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    document.getElementById('globalStartDate').value = formatDate(firstDay);
    document.getElementById('globalEndDate').value = formatDate(lastDay);
});

function formatDate(date) {
    return date.toISOString().split('T')[0];
}

function openReportModal(reportType) {
    currentReportType = reportType;
    const modal = document.getElementById('reportModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalHeader = document.getElementById('modalHeader');
    const additionalFilters = document.getElementById('additionalFilters');
    
    // Set title and header color based on report type
    const reportTitles = {
        anggota: 'Laporan Anggota',
        buku: 'Laporan Buku',
        peminjaman: 'Laporan Peminjaman',
        pengembalian: 'Laporan Pengembalian',
        denda: 'Laporan Denda',
        kas: 'Laporan Kas',
        absensi: 'Laporan Absensi'
    };
    
    modalTitle.textContent = reportTitles[reportType];
    
    // Copy global dates to modal
    document.getElementById('modalStartDate').value = document.getElementById('globalStartDate').value;
    document.getElementById('modalEndDate').value = document.getElementById('globalEndDate').value;
    
    // Clear and set additional filters based on report type
    additionalFilters.innerHTML = '';
    setupAdditionalFilters(reportType, additionalFilters);
    
    modal.classList.remove('hidden');
}

function setupAdditionalFilters(reportType, container) {
    let filtersHTML = '';
    
    switch(reportType) {
        case 'anggota':
            filtersHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota</label>
                    <select id="filterJenisAnggota" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filterStatus" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
            `;
            break;
        case 'buku':
            filtersHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Buku</label>
                    <select id="filterStatusBuku" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Sedang Dipinjam</option>
                    </select>
                </div>
            `;
            break;
        case 'peminjaman':
            filtersHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Peminjaman</label>
                    <select id="filterStatusPeminjaman" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="dipinjam">Sedang Dipinjam</option>
                        <option value="dikembalikan">Sudah Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
            `;
            break;
        case 'denda':
            filtersHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select id="filterStatusDenda" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="sudah_bayar">Sudah Bayar</option>
                    </select>
                </div>
            `;
            break;
        case 'absensi':
            filtersHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengunjung</label>
                    <select id="filterJenisPengunjung" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="anggota">Anggota</option>
                        <option value="tamu">Tamu</option>
                    </select>
                </div>
            `;
            break;
    }
    
    container.innerHTML = filtersHTML;
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

function applyGlobalDateFilter() {
    // This function can be used to apply global date filter to all reports
    const startDate = document.getElementById('globalStartDate').value;
    const endDate = document.getElementById('globalEndDate').value;
    
    if (!startDate || !endDate) {
        alert('Silakan pilih rentang tanggal');
        return;
    }
    
    if (startDate > endDate) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    // Visual feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menerapkan...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Filter tanggal berhasil diterapkan');
    }, 1000);
}

function viewReport() {
    const params = buildReportParams();
    if (!validateReportParams(params)) return;
    
    showLoadingOverlay();
    const url = `{{ url('/admin/laporan') }}/${currentReportType}?${params}`;
    window.open(url, '_blank');
    hideLoadingOverlay();
    closeReportModal();
}

function downloadReport() {
    const params = buildReportParams();
    if (!validateReportParams(params)) return;
    
    showLoadingOverlay();
    const url = `{{ url('/admin/laporan') }}/${currentReportType}?${params}&export=excel`;
    window.location.href = url;
    
    setTimeout(() => {
        hideLoadingOverlay();
        closeReportModal();
    }, 2000);
}

function buildReportParams() {
    const startDate = document.getElementById('modalStartDate').value;
    const endDate = document.getElementById('modalEndDate').value;
    
    let params = new URLSearchParams();
    if (startDate) params.append('tanggal_mulai', startDate);
    if (endDate) params.append('tanggal_akhir', endDate);
    
    // Add specific filters based on report type
    switch(currentReportType) {
        case 'anggota':
            const jenisAnggota = document.getElementById('filterJenisAnggota')?.value;
            const status = document.getElementById('filterStatus')?.value;
            if (jenisAnggota) params.append('jenis_anggota', jenisAnggota);
            if (status) params.append('status', status);
            break;
        case 'buku':
            const statusBuku = document.getElementById('filterStatusBuku')?.value;
            if (statusBuku) params.append('status', statusBuku);
            break;
        case 'peminjaman':
            const statusPeminjaman = document.getElementById('filterStatusPeminjaman')?.value;
            if (statusPeminjaman) params.append('status', statusPeminjaman);
            break;
        case 'denda':
            const statusDenda = document.getElementById('filterStatusDenda')?.value;
            if (statusDenda) params.append('status', statusDenda);
            break;
        case 'absensi':
            const jenisPengunjung = document.getElementById('filterJenisPengunjung')?.value;
            if (jenisPengunjung) params.append('jenis', jenisPengunjung);
            break;
    }
    
    return params.toString();
}

function validateReportParams(params) {
    const startDate = document.getElementById('modalStartDate').value;
    const endDate = document.getElementById('modalEndDate').value;
    
    if (!startDate || !endDate) {
        alert('Silakan pilih rentang tanggal');
        return false;
    }
    
    if (startDate > endDate) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return false;
    }
    
    return true;
}

function showLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('reportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReportModal();
    }
});
</script>
@endsection