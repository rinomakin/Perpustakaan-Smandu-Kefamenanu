@extends('layouts.admin')

@section('title', 'Data Buku')

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

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('buku.index') }}" class=" flex gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Cari Buku</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Judul, ISBN, atau barcode..."
                           class="w-full text-xs px-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id" class="w-full text-xs px-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                    <label class="block text-xs font-medium text-gray-700 mb-2">Jenis</label>
                    <select name="jenis_id" class="w-full text-xs px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full text-xs px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 border  text-white  rounded-lg text-xs transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('buku.index') }}" 
                   class="inline-flex text-xs items-center justify-center px-2 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-refresh mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Header Section with Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <!-- Import/Export Buttons -->
                <div class="flex items-center gap-2">
                    @if(Auth::user()->hasPermission('buku.export') || Auth::user()->isAdmin())
                    <a href="{{ route('buku.export', request()->query()) }}" 
                       class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export
                    </a>
                    @endif
                    
                    @if(Auth::user()->hasPermission('buku.import') || Auth::user()->isAdmin())
                    <a href="{{ route('buku.download-template') }}" 
                       class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Template
                    </a>
                    <button onclick="showImportModal()" 
                            class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-upload mr-2"></i>
                        Import
                    </button>
                    @endif
                    
                    @if(Auth::user()->hasPermission('buku.create') || Auth::user()->isAdmin())
                    <a href="{{ route('buku.create') }}" 
                   class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform ">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah
                </a>
                @endif
                </div>
                
                @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                <!-- Bulk Action Buttons (Hidden by default) -->
                <div id="bulkActionButtons" class="flex items-center gap-2 opacity-0 transition-all duration-300 ease-in-out">
                    <div class="flex items-center gap-2">
                        @if(Auth::user()->hasPermission('buku.cetak-barcode') || Auth::user()->isAdmin())
                        <button onclick="printBarcodeSelected()" 
                                class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-print mr-2"></i>
                            Cetak Barcode
                        </button>
                        @endif
                        @if(Auth::user()->hasPermission('buku.delete') || Auth::user()->isAdmin())
                        <button onclick="deleteSelected()" 
                                class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Terpilih
                        </button>
                        @endif
                    </div>
                    <span id="selectedCount" class="text-sm text-gray-500 transition-all duration-200 mr-2 font-medium bg-gray-100 px-2 py-1 rounded-full">0 buku dipilih</span>

                </div>
                @endif
                
                
            </div>
        </div>
    </div>
    

    <!-- Books Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" style="min-width: 900px;">
                <thead class="bg-gray-50">
                    <tr>
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
                <tbody class="bg-white divide-y divide-gray-200">
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
    
    // Initialize bulk action buttons as hidden
    bulkActionButtons.style.opacity = '0';
    bulkActionButtons.style.pointerEvents = 'none';

    // Select all functionality
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
        selectedCount.textContent = `${checkedBoxes.length} buku dipilih`;
        
        // Show/hide bulk action buttons based on selection with smooth animation
        if (checkedBoxes.length > 0) {
            bulkActionButtons.style.opacity = '1';
            bulkActionButtons.style.pointerEvents = 'auto';
            selectedCount.classList.add('text-blue-600', 'font-medium', 'bg-blue-100');
        } else {
            bulkActionButtons.style.opacity = '0';
            bulkActionButtons.style.pointerEvents = 'none';
            selectedCount.classList.remove('text-blue-600', 'font-medium', 'bg-blue-100');
        }
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        const totalBoxes = bookCheckboxes.length;
        const selectAllLabel = selectAll.nextElementSibling;
        
        selectAll.checked = checkedBoxes.length === totalBoxes;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
        
        // Update label color based on selection
        if (checkedBoxes.length > 0) {
            selectAllLabel.classList.add('text-blue-600', 'font-medium');
        } else {
            selectAllLabel.classList.remove('text-blue-600', 'font-medium');
        }
    }

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    // Generate barcode for selected books
    window.generateBarcodeSelected = function() {
        const selectedIds = Array.from(document.querySelectorAll('.book-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            showWarningAlert('Pilih buku yang akan di-generate barcode');
            return;
        }

        showLoading();
        fetch('{{ route("buku.generate-multiple-barcode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ buku_ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccessAlert('Barcode berhasil di-generate untuk ' + data.count + ' buku');
                location.reload();
            } else {
                showErrorAlert('Gagal generate barcode: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showErrorAlert('Terjadi kesalahan saat generate barcode');
        });
    };

    // Print barcode for selected books
    window.printBarcodeSelected = function() {
        const selectedIds = Array.from(document.querySelectorAll('.book-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            showWarningAlert('Pilih buku yang akan dicetak barcode');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("buku.print-multiple-barcode") }}';
        form.target = '_blank';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'buku_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    };

    // Delete selected books
    window.deleteSelected = function() {
        const selectedIds = Array.from(document.querySelectorAll('.book-checkbox:checked')).map(cb => cb.value);
        console.log('Selected IDs for deletion:', selectedIds);
        
        if (selectedIds.length === 0) {
            showWarningAlert('Pilih buku yang akan dihapus');
            return;
        }

        showConfirmDialog(
            `Yakin ingin menghapus ${selectedIds.length} buku yang dipilih?`,
            'Konfirmasi Hapus Buku',
            function() {
                console.log('Bulk delete confirmed for IDs:', selectedIds);
                showLoading();
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    hideLoading();
                    showErrorAlert('CSRF token tidak ditemukan');
                    return;
                }
                
                fetch('{{ route("buku.destroy-multiple") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ buku_ids: selectedIds })
                })
                .then(response => {
                    console.log('Bulk delete response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    console.log('Bulk delete response:', data);
                    
                    if (data.success) {
                        let message = `Berhasil menghapus ${data.count} buku`;
                        if (data.errors && data.errors.length > 0) {
                            message += `\n\nBeberapa buku tidak dapat dihapus:\n${data.errors.slice(0, 3).join('\n')}`;
                            if (data.errors.length > 3) {
                                message += `\n... dan ${data.errors.length - 3} error lainnya`;
                            }
                        }
                        showSuccessAlert(message);
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showErrorAlert('Gagal menghapus buku: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Bulk delete error:', error);
                    showErrorAlert('Terjadi kesalahan saat menghapus buku: ' + error.message);
                });
            }
        );
    };

    // Import modal functionality
    window.showImportModal = function() {
        document.getElementById('importModal').classList.remove('hidden');
    };

    window.hideImportModal = function() {
        document.getElementById('importModal').classList.add('hidden');
        document.getElementById('importForm').reset();
    };

    // Handle import form submission
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';
        submitBtn.disabled = true;
        
        fetch('{{ route("buku.import") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Check if response contains error
            if (html.includes('error')) {
                showErrorAlert('Gagal import data. Silakan cek file dan coba lagi.');
            } else {
                showSuccessAlert('Import berhasil!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Terjadi kesalahan saat import');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            hideImportModal();
        });
    });
});

// SweetAlert2 Functions for Book Management
function confirmDeleteBuku(id) {
    console.log('Attempting to delete book with ID:', id);
    
    if (!id) {
        showErrorAlert('ID buku tidak valid');
        return;
    }
    
    showConfirmDialog(
        'Yakin ingin menghapus buku ini?',
        'Konfirmasi Hapus Buku',
        function() {
            console.log('Delete confirmed for book ID:', id);
            showLoading();
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                hideLoading();
                showErrorAlert('CSRF token tidak ditemukan');
                return;
            }
            
            fetch(`/admin/buku/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                console.log('Delete response:', data);
                if (data.success) {
                    showSuccessAlert('Buku berhasil dihapus');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showErrorAlert('Gagal menghapus buku: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Delete error:', error);
                showErrorAlert('Terjadi kesalahan saat menghapus buku: ' + error.message);
            });
        }
    );
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}
</script>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Import Data Buku</h3>
                    <button type="button" onclick="hideImportModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="importForm" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Excel/CSV</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, atau .csv (maks. 2MB)</p>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800">Petunjuk Import</h4>
                                <ul class="text-sm text-blue-700 mt-1 list-disc list-inside space-y-1">
                                    <li>Download template terlebih dahulu</li>
                                    <li>Isi data sesuai format template</li>
                                    <li>Pastikan ID master data (kategori, jenis, dll) valid</li>
                                    <li>Barcode kosong akan di-generate otomatis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="hideImportModal()" 
                                class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 