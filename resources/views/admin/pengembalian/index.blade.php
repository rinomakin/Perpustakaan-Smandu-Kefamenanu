@extends('layouts.admin')

@section('title', 'Data Pengembalian')
@section('page-title', 'Data Pengembalian')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<div class="min-h-screen bg-gradient-to-br py-8">
    <div class="px-4 sm:px-6 lg:px-8">
       

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalPengembalianHariIni = $pengembalian->count();
                $totalTerlambatHariIni = $pengembalian->where('jumlah_hari_terlambat', '>', 0)->count();
                $totalDendaHariIni = $pengembalian->sum('total_denda');
                $totalBukuDikembalikan = $pengembalian->sum(function($item) {
                    return $item->detailPengembalian->count();
                });
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                        <i class="fas fa-check-circle  text-green-600 text-xs"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Dikembalikan</p>
                        <p class="text-xs font-bold text-gray-900">{{ $totalPengembalianHariIni }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                        <i class="fas fa-clock text-red-600 text-xs"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Terlambat</p>
                        <p class="text-xs font-bold text-gray-900">{{ $totalTerlambatHariIni }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 rounded-full">
                        <i class="fas fa-money-bill-wave text-yellow-600 text-xs"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Denda</p>
                        <p class="text-xs font-bold text-gray-900">Rp {{ number_format($totalDendaHariIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                        <i class="fas fa-book text-blue-600 text-xs"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Buku</p>
                        <p class="text-xs font-bold text-gray-900">{{ $totalBukuDikembalikan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r flex justify-between items-center from-blue-500 to-indigo-600 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">Data Pengembalian Hari Ini</h3>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Search Input for Active Loans -->
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari peminjaman aktif..." 
                               class="w-64 px-4 py-2 pl-10 text-sm border border-white/20 bg-white/10 text-white placeholder-white/70 rounded-lg focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-white/70"></i>
                        </div>
                    </div>
                    
                    @if(Auth::user()->hasPermission('riwayat-transaksi.view') || Auth::user()->isAdmin())
                    <a href="{{ route('riwayat-pengembalian.index') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-xs">
                        <i class="fas fa-history mr-2 text-xs"></i>Riwayat
                    </a>
                    @endif
                    @if(Auth::user()->hasPermission('pengembalian.create') || Auth::user()->isAdmin())
                    <a href="{{ route('pengembalian.create') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold text-xs">
                        <i class="fas fa-plus "></i>
                        <span>
                        Tambah
                        </span>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Search Results Section for Active Loans -->
            <div id="searchResultsSection" class="hidden border-b border-gray-200">
                <div class="p-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Hasil Pencarian Peminjaman Aktif</h4>
                    <div id="searchResults" class="space-y-3">
                        <!-- Search results will be populated here -->
                    </div>
                </div>
            </div>
             
            
            <div class="p-6">
                @if($pengembalian->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pengembalian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Denda</th>
                                    @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pengembalian as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $item->nomor_pengembalian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $item->detailPengembalian->count() }} Buku
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d/m/Y') : 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->jam_pengembalian ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->jumlah_hari_terlambat > 0)
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat {{ $item->jumlah_hari_terlambat }} hari</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Tepat Waktu</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->total_denda > 0)
                                            <span class="text-red-600 font-medium">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-green-600 font-medium">Rp 0</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin())
                                            <a href="{{ route('pengembalian.show', $item->id) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin())
                                            <a href="{{ route('pengembalian.edit', $item->id) }}" 
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                            <button type="button" onclick="confirmDelete({{ $item->id }})" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($pengembalian->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $pengembalian->firstItem() ?? 0 }} - {{ $pengembalian->lastItem() ?? 0 }} dari {{ $pengembalian->total() }} pengembalian
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $pengembalian->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-undo-alt text-xl text-gray-400"></i>
                        </div>
                        @if(request('search'))
                            <h3 class="text-xs font-medium text-gray-900 mb-2">Tidak ada data pengembalian ditemukan</h3>
                            <p class="text-gray-600 text-xs mb-4">Tidak ada pengembalian yang sesuai dengan pencarian "{{ request('search') }}".</p>
                            <a href="{{ route('pengembalian.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke semua data
                            </a>
                        @else
                            <h3 class="text-xs font-medium text-gray-900 mb-2">Tidak ada data pengembalian hari ini</h3>
                            <p class="text-gray-600 text-xs">Belum ada buku yang dikembalikan hari ini.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Memproses...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResultsSection = document.getElementById('searchResultsSection');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;
    let currentSearchType = 'loans'; // 'loans' for active loans, 'returns' for completed returns
    
    // Auto-reload search functionality for active loans
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = this.value.trim();
            
            searchTimeout = setTimeout(() => {
                if (searchValue.length >= 2) {
                    searchActiveLoan(searchValue);
                } else if (searchValue.length === 0) {
                    hideSearchResults();
                    // If empty, reload page to show all today's returns
                    if (new URLSearchParams(window.location.search).get('search')) {
                        window.location.href = window.location.pathname;
                    }
                } else {
                    hideSearchResults();
                }
            }, 500); // 500ms debounce
        });
        
        // Handle Enter key to search returns
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchValue = this.value.trim();
                if (searchValue.length >= 2) {
                    searchReturns(searchValue);
                }
            }
        });
    }
    
    // Search for active loans (real-time search)
    function searchActiveLoan(query) {
        showLoadingOverlay();
        currentSearchType = 'loans';
        
        fetch(`{{ route('pengembalian.search-anggota') }}?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            hideLoadingOverlay();
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayActiveLoanResults(data.data, query);
            } else {
                showSearchError(data.message || 'Pencarian gagal');
            }
        })
        .catch(error => {
            hideLoadingOverlay();
            console.error('Search error:', error);
            showSearchError('Terjadi kesalahan saat mencari data: ' + error.message);
        });
    }
    
    // Search for completed returns (page reload with search parameter)
    function searchReturns(query) {
        showLoadingOverlay();
        const currentUrl = new URL(window.location.href);
        const params = new URLSearchParams(currentUrl.search);
        
        if (query.trim()) {
            params.set('search', query);
        } else {
            params.delete('search');
        }
        
        window.location.href = currentUrl.pathname + '?' + params.toString();
    }
    
    // Display active loan search results
    function displayActiveLoanResults(data, query) {
        if (!data || data.length === 0) {
            searchResults.innerHTML = `
                <div class="text-center py-8">
                    <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Tidak ada peminjaman aktif ditemukan untuk "${query}"</p>
                    <p class="text-gray-400 text-xs mt-1">Tekan Enter untuk mencari data pengembalian</p>
                </div>
            `;
        } else {
            searchResults.innerHTML = data.map(anggota => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h5 class="font-medium text-gray-900">${anggota.nama_lengkap}</h5>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span>${anggota.nomor_anggota}</span>
                                <span>•</span>
                                <span>${anggota.kelas}</span>
                                <span>•</span>
                                <span class="font-medium text-blue-600">${anggota.jumlah_peminjaman_aktif} peminjaman aktif</span>
                            </div>
                        </div>
                        <a href="{{ route('pengembalian.create') }}?anggota_id=${anggota.id}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                            <i class="fas fa-plus mr-1"></i>Kembalikan
                        </a>
                    </div>
                    
                    ${anggota.detail_peminjaman && anggota.detail_peminjaman.length > 0 ? `
                        <div class="space-y-2">
                            ${anggota.detail_peminjaman.map(peminjaman => `
                                <div class="bg-white rounded p-3 border border-gray-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-blue-600">${peminjaman.nomor_peminjaman}</span>
                                        <span class="text-xs text-gray-500">${peminjaman.tanggal_harus_kembali}</span>
                                    </div>
                                    <div class="space-y-1">
                                        ${peminjaman.buku && peminjaman.buku.length > 0 ? peminjaman.buku.map(buku => `
                                            <div class="text-xs text-gray-700">
                                                <span class="font-medium">${buku.judul}</span>
                                                ${buku.pengarang ? ` - ${buku.pengarang}` : ''}
                                                ${buku.jumlah > 1 ? ` (${buku.jumlah} eks)` : ''}
                                            </div>
                                        `).join('') : '<div class="text-xs text-gray-500 italic">Detail buku tidak tersedia</div>'}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    ` : '<div class="text-xs text-gray-500 italic">Detail peminjaman tidak tersedia</div>'}
                </div>
            `).join('');
        }
        
        // Add instruction for searching returns
        const instructionDiv = document.createElement('div');
        instructionDiv.className = 'text-center py-3 border-t border-gray-200 mt-4';
        instructionDiv.innerHTML = `
            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Menampilkan peminjaman aktif. Tekan <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Enter</kbd> untuk mencari data pengembalian hari ini.
            </p>
        `;
        searchResults.appendChild(instructionDiv);
        
        showSearchResults();
    }
    
    // Show search error
    function showSearchError(message) {
        searchResults.innerHTML = `
            <div class="text-center py-8">
                <div class="mx-auto w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <p class="text-red-600 text-sm">${message}</p>
            </div>
        `;
        showSearchResults();
    }
    
    // Show/hide search results
    function showSearchResults() {
        searchResultsSection.classList.remove('hidden');
    }
    
    function hideSearchResults() {
        searchResultsSection.classList.add('hidden');
    }
    
    // Show/hide loading overlay
    function showLoadingOverlay() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
        }
    }
    
    function hideLoadingOverlay() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }
    
    // Initialize search if there's a search parameter
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam && searchInput) {
        searchInput.value = searchParam;
    }
});

function confirmDelete(pengembalianId) {
    if (confirm('Apakah Anda yakin ingin menghapus data pengembalian ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pengembalian/${pengembalianId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
