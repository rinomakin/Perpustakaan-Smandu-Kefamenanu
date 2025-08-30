@extends('layouts.admin')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="space-y-6">
    <!-- Header Section with Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Left side - Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Pengembalian</h1>
                <p class="text-gray-600 mt-1">Lihat dan filter riwayat pengembalian buku</p>
            </div>
            
            <!-- Right side - Search, Filter and Export Button -->
            <div class="flex items-center gap-3">
                <!-- Search Input -->
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari riwayat..." 
                               value="{{ request('search') }}"
                               class="w-64 px-4 py-2 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filter Button -->
                    <button onclick="openFilterModal()" 
                            class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>
                
                <a href="{{ route('riwayat-pengembalian.export') }}?{{ http_build_query(request()->all()) }}" 
                   class="inline-flex items-center text-xs px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </a>
                
                <a href="{{ route('pengembalian.index') }}" 
                   class="inline-flex items-center text-xs px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalPengembalian = \App\Models\Pengembalian::count();
            $totalTerlambat = \App\Models\Pengembalian::where('jumlah_hari_terlambat', '>', 0)->count();
            $totalDenda = \App\Models\Pengembalian::sum('total_denda');
            $pengembalianHariIni = \App\Models\Pengembalian::whereDate('tanggal_pengembalian', today())->count();
        @endphp
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Dikembalikan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPengembalian }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTerlambat }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Denda</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pengembalianHariIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200" style="min-width: 900px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Pengembalian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Buku
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pengembalian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Denda
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Petugas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengembalian as $index => $return)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $return->nomor_pengembalian }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $return->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $return->anggota->nomor_anggota }}</div>
                            <div class="text-xs text-gray-400">{{ $return->anggota->kelas->nama_kelas ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $return->jumlah_buku }} buku
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $return->tanggal_pengembalian ? $return->tanggal_pengembalian->format('d/m/Y') : '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $return->jam_pengembalian ? $return->jam_pengembalian->format('H:i') : '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($return->jumlah_hari_terlambat > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 rounded-full mr-1.5 bg-red-400"></span>
                                    Terlambat {{ $return->jumlah_hari_terlambat }} hari
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 rounded-full mr-1.5 bg-green-400"></span>
                                    Tepat Waktu
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($return->total_denda > 0)
                                <span class="text-sm font-medium text-red-600">Rp {{ number_format($return->total_denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $return->user->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('pengembalian.show', $return->id) }}" 
                                   class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-undo text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada riwayat pengembalian</h3>
                            <p class="text-gray-600">Belum ada data riwayat pengembalian atau tidak ada yang sesuai dengan filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($pengembalian->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $pengembalian->firstItem() ?? 0 }} - {{ $pengembalian->lastItem() ?? 0 }} dari {{ $pengembalian->total() }} riwayat
            </div>
            <div class="flex items-center space-x-2">
                {{ $pengembalian->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Filter Modal -->
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Filter Riwayat Pengembalian</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="filterForm" method="GET" action="{{ route('riwayat-pengembalian.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Status Terlambat Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Terlambat</label>
                        <select name="status_terlambat" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="terlambat" {{ request('status_terlambat') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="tepat_waktu" {{ request('status_terlambat') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        </select>
                    </div>

                    <!-- Anggota Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <select name="anggota_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Anggota</option>
                            @foreach($anggota as $member)
                            <option value="{{ $member->id }}" {{ request('anggota_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->nama_lengkap }} - {{ $member->nomor_anggota }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buku Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buku</label>
                        <select name="buku_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Buku</option>
                            @foreach($buku as $book)
                            <option value="{{ $book->id }}" {{ request('buku_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->judul_buku }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ request('jam_mulai') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Jam Akhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Akhir</label>
                        <input type="time" name="jam_akhir" value="{{ request('jam_akhir') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="resetFilters()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
        <span class="text-gray-700">Memproses...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    // Auto-reload search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = this.value;
            
            searchTimeout = setTimeout(() => {
                // Show loading state
                showLoadingOverlay();
                
                // Build URL with current filters and new search
                const currentUrl = new URL(window.location.href);
                const params = new URLSearchParams(currentUrl.search);
                
                if (searchValue.trim()) {
                    params.set('search', searchValue);
                } else {
                    params.delete('search');
                }
                
                // Reload page with new search parameter
                window.location.href = currentUrl.pathname + '?' + params.toString();
            }, 500); // 500ms debounce
        });
    }
});

// Filter Modal Functions
function openFilterModal() {
    document.getElementById('filterModal').classList.remove('hidden');
}

function closeFilterModal() {
    document.getElementById('filterModal').classList.add('hidden');
}

function resetFilters() {
    // Clear all form inputs
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
    
    // Redirect to page without filters
    window.location.href = '{{ route("riwayat-pengembalian.index") }}';
}

function showLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Hide loading overlay when page loads
window.addEventListener('load', function() {
    hideLoadingOverlay();
});
</script>
@endsection
