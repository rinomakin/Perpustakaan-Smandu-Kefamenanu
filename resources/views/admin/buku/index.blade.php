@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Buku</h1>
                <p class="text-gray-600 mt-1">Kelola semua data buku perpustakaan</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Import/Export Buttons -->
                <div class="flex items-center gap-2">
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
                    <a href="{{ route('buku.export', request()->query()) }}" 
                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export
                    </a>
                </div>
                <a href="{{ route('buku.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Buku
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('buku.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Judul, ISBN, atau barcode..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('buku.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-refresh mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <label class="flex items-center">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                </label>
                <span id="selectedCount" class="text-sm text-gray-500">0 buku dipilih</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="generateBarcodeSelected()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-barcode mr-2"></i>
                    Generate Barcode
                </button>
                <button onclick="printBarcodeSelected()" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Barcode
                </button>
                <button onclick="deleteSelected()" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($buku as $bukuItem)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <!-- Book Cover Placeholder -->
            <div class="h-48 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-t-xl flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-book text-4xl text-blue-500 mb-2"></i>
                    <p class="text-sm text-blue-600 font-medium">{{ $bukuItem->kategori->nama_kategori ?? 'Kategori' }}</p>
                </div>
            </div>

            <!-- Book Info -->
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 text-lg leading-tight line-clamp-2">
                        {{ $bukuItem->judul_buku }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="book-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="{{ $bukuItem->id }}">
                    </div>
                </div>

                <!-- Book Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-edit w-4 mr-2 text-blue-500"></i>
                        <span class="truncate">{{ $bukuItem->penulis->nama_penulis ?? 'Penulis tidak diketahui' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-building w-4 mr-2 text-green-500"></i>
                        <span class="truncate">{{ $bukuItem->penerbit->nama_penerbit ?? 'Penerbit tidak diketahui' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-barcode w-4 mr-2 text-purple-500"></i>
                        <span class="font-mono text-xs">{{ $bukuItem->barcode ?? 'Belum ada barcode' }}</span>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($bukuItem->stok_tersedia > 0) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            <span class="w-2 h-2 rounded-full mr-1.5
                                @if($bukuItem->stok_tersedia > 0) bg-green-400 @else bg-red-400 @endif"></span>
                            {{ $bukuItem->stok_tersedia > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Stok</p>
                        <p class="font-semibold text-gray-900">{{ $bukuItem->stok_tersedia }}/{{ $bukuItem->jumlah_stok }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('buku.show', $bukuItem->id) }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-eye mr-1"></i>
                        Detail
                    </a>
                    <a href="{{ route('buku.edit', $bukuItem->id) }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </a>
                    <button onclick="deleteBuku({{ $bukuItem->id }})" 
                            class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-book text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada buku ditemukan</h3>
                <p class="text-gray-600 mb-6">Belum ada data buku yang ditambahkan atau tidak ada buku yang sesuai dengan filter.</p>
                <a href="{{ route('buku.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Buku Pertama
                </a>
            </div>
        </div>
        @endforelse
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

    // Select all functionality
    selectAll.addEventListener('change', function() {
        bookCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox change
    bookCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllState();
        });
    });

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        selectedCount.textContent = `${checkedBoxes.length} buku dipilih`;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        const totalBoxes = bookCheckboxes.length;
        selectAll.checked = checkedBoxes.length === totalBoxes;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
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
            alert('Pilih buku yang akan di-generate barcode');
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
                alert('Barcode berhasil di-generate untuk ' + data.count + ' buku');
                location.reload();
            } else {
                alert('Gagal generate barcode: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate barcode');
        });
    };

    // Print barcode for selected books
    window.printBarcodeSelected = function() {
        const selectedIds = Array.from(document.querySelectorAll('.book-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert('Pilih buku yang akan dicetak barcode');
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
        if (selectedIds.length === 0) {
            alert('Pilih buku yang akan dihapus');
            return;
        }

        if (!confirm(`Yakin ingin menghapus ${selectedIds.length} buku yang dipilih?`)) {
            return;
        }

        showLoading();
        fetch('{{ route("buku.destroy-multiple") }}', {
            method: 'DELETE',
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
                alert('Berhasil menghapus ' + data.count + ' buku');
                location.reload();
            } else {
                alert('Gagal menghapus buku: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus buku');
        });
    };

    // Delete single book
    window.deleteBuku = function(id) {
        if (!confirm('Yakin ingin menghapus buku ini?')) {
            return;
        }

        showLoading();
        fetch(`/admin/buku/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                alert('Buku berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus buku: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus buku');
        });
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
                alert('Gagal import data. Silakan cek file dan coba lagi.');
            } else {
                alert('Import berhasil!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat import');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            hideImportModal();
        });
    });
});
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
                                    <li>Pastikan ID master data (penulis, penerbit, dll) valid</li>
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