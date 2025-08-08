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

    <!-- Header with Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <label class="flex items-center">
                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                </label>
                <span id="selectedCount" class="text-sm text-gray-500">0 kategori dipilih</span>
            </div>
            <div id="bulkActionButtons" class="flex flex-wrap gap-2 opacity-0 pointer-events-none transition-all duration-200">
                <button onclick="deleteSelected()" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>
        </div>
    </div>

    <!-- Kategori Buku Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Buku
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kategoris as $kategori)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 kategori-row" data-id="{{ $kategori->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="kategori-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" value="{{ $kategori->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tags text-purple-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $kategori->nama_kategori }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}">
                                {{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-book mr-1"></i>
                                {{ $kategori->buku_count }} Buku
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $kategori->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $kategori->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('kategori-buku.show', $kategori->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kategori-buku.edit', $kategori->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteKategori({{ $kategori->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
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
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
        updateRowSelection();
    });

    // Individual checkbox change
    kategoriCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllState();
            updateRowSelection();
        });
    });

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.kategori-checkbox:checked');
        const bulkActionButtons = document.getElementById('bulkActionButtons');
        selectedCount.textContent = `${checkedBoxes.length} kategori dipilih`;
        
        // Update selected count styling and bulk action buttons visibility
        if (checkedBoxes.length > 0) {
            selectedCount.classList.remove('text-gray-500');
            selectedCount.classList.add('bg-blue-100', 'px-2', 'py-1', 'rounded-full', 'text-blue-800', 'font-medium');
            bulkActionButtons.classList.remove('opacity-0', 'pointer-events-none');
            bulkActionButtons.classList.add('opacity-100', 'pointer-events-auto');
        } else {
            selectedCount.classList.remove('bg-blue-100', 'px-2', 'py-1', 'rounded-full', 'text-blue-800', 'font-medium');
            selectedCount.classList.add('text-gray-500');
            bulkActionButtons.classList.remove('opacity-100', 'pointer-events-auto');
            bulkActionButtons.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.kategori-checkbox:checked');
        const totalBoxes = kategoriCheckboxes.length;
        selectAll.checked = checkedBoxes.length === totalBoxes;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }

    function updateRowSelection() {
        const rows = document.querySelectorAll('.kategori-row');
        rows.forEach(row => {
            const checkbox = row.querySelector('.kategori-checkbox');
            if (checkbox.checked) {
                row.classList.add('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            } else {
                row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            }
        });
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
            showWarningAlert('Pilih kategori yang akan dihapus');
            return;
        }

        showConfirmDialog(
            `Yakin ingin menghapus ${selectedIds.length} kategori yang dipilih?`,
            'Konfirmasi Hapus Kategori',
            function() {
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
                        showSuccessAlert(data.message);
                        location.reload();
                    } else {
                        showErrorAlert('Gagal menghapus kategori: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showErrorAlert('Terjadi kesalahan saat menghapus kategori');
                });
            }
        );
    };

    // Delete single category
    window.deleteKategori = function(id) {
        showConfirmDialog(
            'Yakin ingin menghapus kategori ini?',
            'Konfirmasi Hapus Kategori',
            function() {
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
                        showSuccessAlert('Kategori berhasil dihapus');
                        location.reload();
                    } else {
                        showErrorAlert('Gagal menghapus kategori: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showErrorAlert('Terjadi kesalahan saat menghapus kategori');
                });
            }
        );
    };
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.kategori-row {
    transition: all 0.2s ease-in-out;
}

.kategori-row:hover {
    background-color: #f9fafb;
}

.kategori-row.bg-blue-50 {
    background-color: #eff6ff;
    border-left: 4px solid #3b82f6;
}
</style>
@endsection 