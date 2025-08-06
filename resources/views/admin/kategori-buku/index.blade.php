@extends('layouts.admin')

@section('title', 'Data Kategori Buku')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Kategori Buku</h1>
                <p class="text-gray-600 mt-1">Kelola semua kategori buku perpustakaan</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('kategori-buku.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <label class="flex items-center">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                </label>
                <span id="selectedCount" class="text-sm text-gray-500">0 kategori dipilih</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="deleteSelected()" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($kategoris as $kategori)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <!-- Category Icon -->
            <div class="h-32 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-t-xl flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-tags text-4xl text-purple-500 mb-2"></i>
                    <p class="text-sm text-purple-600 font-medium">Kategori</p>
                </div>
            </div>

            <!-- Category Info -->
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 text-lg leading-tight line-clamp-2">
                        {{ $kategori->nama_kategori }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="kategori-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="{{ $kategori->id }}">
                    </div>
                </div>

                <!-- Category Details -->
                <div class="space-y-2 mb-4">
                    @if($kategori->deskripsi)
                    <div class="text-sm text-gray-600">
                        <p class="line-clamp-3">{{ $kategori->deskripsi }}</p>
                    </div>
                    @else
                    <div class="text-sm text-gray-500 italic">
                        <p>Tidak ada deskripsi</p>
                    </div>
                    @endif
                </div>

                <!-- Book Count -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-book mr-1"></i>
                                {{ $kategori->buku_count }} Buku
                            </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Dibuat</p>
                        <p class="text-xs font-medium text-gray-900">{{ $kategori->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('kategori-buku.show', $kategori->id) }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-eye mr-1"></i>
                        Detail
                    </a>
                    <a href="{{ route('kategori-buku.edit', $kategori->id) }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </a>
                    <button onclick="deleteKategori({{ $kategori->id }})" 
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
                    <i class="fas fa-tags text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada kategori ditemukan</h3>
                <p class="text-gray-600 mb-6">Belum ada data kategori buku yang ditambahkan.</p>
                <a href="{{ route('kategori-buku.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($kategoris->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $kategoris->firstItem() ?? 0 }} - {{ $kategoris->lastItem() ?? 0 }} dari {{ $kategoris->total() }} kategori
            </div>
            <div class="flex items-center space-x-2">
                {{ $kategoris->links() }}
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
    const kategoriCheckboxes = document.querySelectorAll('.kategori-checkbox');
    const selectedCount = document.getElementById('selectedCount');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        kategoriCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox change
    kategoriCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllState();
        });
    });

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.kategori-checkbox:checked');
        selectedCount.textContent = `${checkedBoxes.length} kategori dipilih`;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.kategori-checkbox:checked');
        const totalBoxes = kategoriCheckboxes.length;
        selectAll.checked = checkedBoxes.length === totalBoxes;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    // Delete selected categories
    window.deleteSelected = function() {
        const selectedIds = Array.from(document.querySelectorAll('.kategori-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert('Pilih kategori yang akan dihapus');
            return;
        }

        if (!confirm(`Yakin ingin menghapus ${selectedIds.length} kategori yang dipilih?`)) {
            return;
        }

        showLoading();
        fetch('{{ route("kategori-buku.destroy-multiple") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ kategori_ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal menghapus kategori: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kategori');
        });
    };

    // Delete single category
    window.deleteKategori = function(id) {
        if (!confirm('Yakin ingin menghapus kategori ini?')) {
            return;
        }

        showLoading();
        fetch(`/admin/kategori-buku/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                alert('Kategori berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus kategori: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kategori');
        });
    };
});
</script>
@endsection 