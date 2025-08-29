@extends('layouts.admin')

@section('title', 'Data Buku')
@section('page-title', 'Data Buku')

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
<div class="space-y-6">

    <!-- Header Section with Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Left side - Import/Export and Bulk Actions -->
            <div class="flex items-center gap-3">
                <!-- Import/Export Buttons -->
                <div class="flex items-center gap-2">
                    @if(Auth::user()->hasPermission('buku.export') || Auth::user()->isAdmin())
                    <a href="{{ route('buku.export', request()->query()) }}" 
                       class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export
                    </a>
                    @endif
                    
                    @if(Auth::user()->hasPermission('buku.import') || Auth::user()->isAdmin())
                    <a href="{{ route('buku.download-template') }}" 
                       class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Template
                    </a>
                    <button onclick="showImportModal()" 
                            class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-upload mr-2"></i>
                        Import
                    </button>
                    @endif
                </div>
                
                @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                <!-- Bulk Action Buttons (Hidden by default) -->
                <div id="bulkActionButtons" class="flex items-center gap-2 opacity-0 transition-all duration-300 ease-in-out">
                    <div class="flex items-center gap-2">
                        @if(Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <button onclick="printBarcodeSelected()" 
                                class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-print mr-2"></i>
                            Cetak
                        </button>
                        @endif
                        @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin())
                        <button onclick="deleteSelected()" 
                                class="inline-flex  items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus 
                        </button>
                        @endif
                    </div>
                    <span id="selectedCount" class=" text-gray-500 transition-all duration-200 mr-2 text-[10px] font-medium bg-gray-100 px-2 py-1 rounded-full">0 buku dipilih</span>
                </div>
                @endif
            </div>
            
            <!-- Right side - Search, Filter and Add Button -->
            <div class="flex items-center gap-3">
                <!-- Search Input -->
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari buku..." 
                               value="{{ request('search') }}"
                               class="w-64 px-4 py-2 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                
                @if(Auth::user()->hasPermission('buku.create') || Auth::user()->isAdmin())
                <a href="{{ route('buku.create') }}" 
                   class="inline-flex items-center text-xs px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah
                </a>
                @endif
            </div>
        </div>
    </div>
    

    <!-- Books Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto ">
            <table class="w-full divide-y  divide-gray-950" style="min-width: 900px;">
                <thead class="">
                    <tr class="border-b border-gray-200">
                        @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                <span class="ml-2 text-xs text-gray-500 transition-all duration-200">Pilih</span>
                            </div>
                        </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cover
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        @if(Auth::user()->hasPermission('buku.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 w-full ">
                    @forelse($buku as $bukuItem)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="book-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200" value="{{ $bukuItem->id }}">
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0 h-16 w-12 group relative" title="{{ $bukuItem->judul_buku }}">
                                @if($bukuItem->cover_buku)
                                    <img src="{{ asset('storage/' . $bukuItem->cover_buku) }}"
                                         alt="Cover {{ $bukuItem->judul_buku }}" 
                                         class="h-16 w-12 object-cover rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group-hover:scale-105">
                                @else
                                    <div class="h-16 w-12 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center shadow-sm border border-gray-200 group-hover:shadow-md transition-all duration-200">
                                        <i class="fas fa-book text-blue-500 text-lg"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900 line-clamp-2">{{ $bukuItem->judul_buku }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $bukuItem->isbn ?? 'ISBN tidak tersedia' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($bukuItem->rak)
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $bukuItem->rak->nama_rak }}</div>
                                    <div class="text-xs text-gray-500">{{ $bukuItem->rak->kode_rak }}</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">Tidak ada rak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $bukuItem->kategori->nama_kategori ?? 'Kategori tidak diketahui' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $bukuItem->jenis->nama_jenis ?? 'Jenis tidak diketahui' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $bukuItem->stok_tersedia }}/{{ $bukuItem->jumlah_stok }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($bukuItem->stok_tersedia > 0) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                <span class="w-2 h-2 rounded-full mr-1.5
                                    @if($bukuItem->stok_tersedia > 0) bg-green-400 @else bg-red-400 @endif"></span>
                                {{ $bukuItem->stok_tersedia > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </td>
                        @if(Auth::user()->hasPermission('buku.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @if(Auth::user()->hasPermission('buku.view') || Auth::user()->isAdmin())
                                <a href="{{ route('buku.show', $bukuItem->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('buku.update') || Auth::user()->isAdmin())
                                <a href="{{ route('buku.edit', $bukuItem->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                                <a href="{{ route('buku.cetak-barcode', $bukuItem->id) }}" 
                                   class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Cetak Barcode" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin())
                                <button onclick="confirmDeleteBuku({{ $bukuItem->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 
                            7 + 
                            (Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin() ? 1 : 0) + 
                            (Auth::user()->hasPermission('buku.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin() ? 1 : 0) 
                        }}" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada buku ditemukan</h3>
                            <p class="text-gray-600 mb-6">Belum ada data buku yang ditambahkan atau tidak ada buku yang sesuai dengan filter.</p>
                            @if(Auth::user()->hasPermission('buku.create') || Auth::user()->isAdmin())
                            <a href="{{ route('buku.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Buku Pertama
                            </a>
                            @else
                            <p class="text-gray-500">Tidak ada data buku tersedia.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($buku->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $buku->firstItem() ?? 0 }} - {{ $buku->lastItem() ?? 0 }} dari {{ $buku->total() }} buku
            </div>
            <div class="flex items-center space-x-2">
                {{ $buku->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Filter Modal -->
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Filter Buku</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="filterForm" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Buku</label>
                        <select name="jenis_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Jenis</option>
                            @foreach($jenis as $jenisItem)
                                <option value="{{ $jenisItem->id }}" {{ request('jenis_id') == $jenisItem->id ? 'selected' : '' }}>
                                    {{ $jenisItem->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ketersediaan</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ request('tahun_terbit') }}" placeholder="Contoh: 2023"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="resetFilters()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
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
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Memproses...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const bookCheckboxes = document.querySelectorAll('.book-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionButtons = document.getElementById('bulkActionButtons');
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    // Initialize bulk action buttons as hidden
    if (bulkActionButtons) {
        bulkActionButtons.style.opacity = '0';
        bulkActionButtons.style.pointerEvents = 'none';
    }

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

    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            bookCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                
                // Add visual feedback for all rows
                const row = checkbox.closest('tr');
                if (this.checked) {
                    row.classList.add('bg-blue-50', 'border-l-4', 'border-l-blue-500');
                } else {
                    row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
                }
            });
            updateSelectedCount();
        });
    }

    // Individual checkbox change
    bookCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllState();
            
            // Add visual feedback
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            } else {
                row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            }
        });
    });

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        if (selectedCount) {
            selectedCount.textContent = `${checkedBoxes.length} buku dipilih`;
        }
        
        // Show/hide bulk action buttons based on selection with smooth animation
        if (bulkActionButtons) {
            if (checkedBoxes.length > 0) {
                bulkActionButtons.style.opacity = '1';
                bulkActionButtons.style.pointerEvents = 'auto';
                if (selectedCount) {
                    selectedCount.classList.add('text-blue-600', 'font-medium', 'bg-blue-100');
                }
            } else {
                bulkActionButtons.style.opacity = '0';
                bulkActionButtons.style.pointerEvents = 'none';
                if (selectedCount) {
                    selectedCount.classList.remove('text-blue-600', 'font-medium', 'bg-blue-100');
                }
            }
        }
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        const totalBoxes = bookCheckboxes.length;
        
        if (selectAll) {
            selectAll.checked = checkedBoxes.length === totalBoxes;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
        }
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
    // Reset all form fields
    const form = document.getElementById('filterForm');
    form.reset();
    
    // Redirect to base URL without filters
    window.location.href = '{{ route("buku.index") }}';
}

// Handle filter form submission
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoadingOverlay();
    
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    // Add current search parameter if exists
    const searchInput = document.getElementById('searchInput');
    if (searchInput && searchInput.value.trim()) {
        params.set('search', searchInput.value.trim());
    }
    
    // Add filter parameters
    for (let [key, value] of formData.entries()) {
        if (value.trim()) {
            params.set(key, value);
        }
    }
    
    // Redirect with filters
    window.location.href = '{{ route("buku.index") }}' + '?' + params.toString();
});

// Close modal when clicking outside
document.getElementById('filterModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFilterModal();
    }
});

// Loading overlay function
function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}
</script>
@endsection