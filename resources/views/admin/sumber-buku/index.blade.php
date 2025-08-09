@extends('layouts.admin')

@section('title', 'Data Sumber Buku')

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
        <form method="GET" action="{{ route('sumber-buku.index') }}" class="flex gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Cari Sumber Buku</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nama sumber, kode, atau deskripsi..."
                           class="w-full text-xs px-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full text-xs px-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 border text-white rounded-lg text-xs transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('sumber-buku.index') }}" 
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
                <button onclick="showCreateModal()" 
                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Sumber Buku
                </button>
                
                <!-- Bulk Action Buttons (Hidden by default) -->
                <div id="bulkActionButtons" class="flex items-center gap-2 opacity-0 transition-all duration-300 ease-in-out">
                    <button onclick="deleteSelected()" 
                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Terpilih
                    </button>
                    <span id="selectedCount" class="text-sm text-gray-500 transition-all duration-200 mr-2 font-medium bg-gray-100 px-2 py-1 rounded-full">0 sumber dipilih</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sumber Buku Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                <span class="ml-2 text-xs text-gray-500 transition-all duration-200">Pilih</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Sumber
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Buku
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sumber as $sumberItem)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="sumber-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200" value="{{ $sumberItem->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $sumberItem->kode_sumber ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $sumberItem->nama_sumber }}</div>
                            <div class="text-xs text-gray-500 mt-1">Dibuat: {{ $sumberItem->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 line-clamp-2">
                                {{ $sumberItem->deskripsi ?? 'Tidak ada deskripsi' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $sumberItem->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-1.5
                                    {{ $sumberItem->status === 'aktif' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ ucfirst($sumberItem->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $sumberItem->buku_count ?? 0 }} buku</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="showDetailModal({{ $sumberItem->id }})" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="showEditModal({{ $sumberItem->id }})" 
                                       class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDeleteSumber({{ $sumberItem->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book-open text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada sumber buku ditemukan</h3>
                            <p class="text-gray-600 mb-6">Belum ada data sumber buku yang ditambahkan atau tidak ada sumber yang sesuai dengan filter.</p>
                            <button onclick="showCreateModal()" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Sumber Buku Pertama
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($sumber->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $sumber->firstItem() ?? 0 }} - {{ $sumber->lastItem() ?? 0 }} dari {{ $sumber->total() }} sumber buku
            </div>
            <div class="flex items-center space-x-2">
                {{ $sumber->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Create/Edit Modal -->
<div id="sumberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Tambah Sumber Buku</h3>
                    <button type="button" onclick="hideSumberModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="sumberForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="sumberId" name="id">
                    <input type="hidden" id="formMethod" name="_method">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sumber <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_sumber" name="nama_sumber" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Masukkan nama sumber buku">
                        <div id="nama_sumber_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Sumber</label>
                        <div class="flex gap-2">
                            <input type="text" id="kode_sumber" name="kode_sumber"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Kosongkan untuk auto-generate">
                            <button type="button" onclick="generateKodeSumber()" 
                                    class="px-3 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-magic"></i>
                            </button>
                        </div>
                        <div id="kode_sumber_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <div id="status_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Masukkan deskripsi sumber buku (opsional)"></textarea>
                        <div id="deskripsi_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="hideSumberModal()" 
                                class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Sumber Buku</h3>
                    <button type="button" onclick="hideDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="detailContent" class="space-y-4">
                    <!-- Detail content will be loaded here -->
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="button" onclick="hideDetailModal()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                        Tutup
                    </button>
                </div>
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
    const selectAll = document.getElementById('selectAll');
    const sumberCheckboxes = document.querySelectorAll('.sumber-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionButtons = document.getElementById('bulkActionButtons');
    
    // Initialize bulk action buttons as hidden
    bulkActionButtons.style.opacity = '0';
    bulkActionButtons.style.pointerEvents = 'none';

    // Select all functionality
    selectAll.addEventListener('change', function() {
        sumberCheckboxes.forEach(checkbox => {
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
    sumberCheckboxes.forEach(checkbox => {
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
        const checkedBoxes = document.querySelectorAll('.sumber-checkbox:checked');
        selectedCount.textContent = `${checkedBoxes.length} sumber dipilih`;
        
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
        const checkedBoxes = document.querySelectorAll('.sumber-checkbox:checked');
        const totalBoxes = sumberCheckboxes.length;
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
});

// Modal functions
function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Sumber Buku';
    document.getElementById('sumberForm').reset();
    document.getElementById('sumberId').value = '';
    document.getElementById('formMethod').value = '';
    clearErrors();
    document.getElementById('sumberModal').classList.remove('hidden');
}

function showEditModal(id) {
    document.getElementById('modalTitle').textContent = 'Edit Sumber Buku';
    document.getElementById('sumberId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    clearErrors();
    
    showLoading();
    fetch(`/admin/sumber-buku/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            document.getElementById('nama_sumber').value = data.data.nama_sumber;
            document.getElementById('kode_sumber').value = data.data.kode_sumber || '';
            document.getElementById('status').value = data.data.status;
            document.getElementById('deskripsi').value = data.data.deskripsi || '';
            document.getElementById('sumberModal').classList.remove('hidden');
        } else {
            showErrorAlert('Gagal memuat data sumber buku');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat memuat data');
    });
}

function showDetailModal(id) {
    showLoading();
    fetch(`/admin/sumber-buku/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const sumber = data.data;
            const statusBadge = sumber.status === 'aktif' 
                ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Aktif</span>'
                : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Nonaktif</span>';
                
            document.getElementById('detailContent').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Sumber</label>
                    <p class="mt-1 text-sm text-gray-900">${sumber.nama_sumber}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Sumber</label>
                    <p class="mt-1 text-sm text-gray-900">${sumber.kode_sumber || 'Tidak ada kode'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">${statusBadge}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <p class="mt-1 text-sm text-gray-900">${sumber.deskripsi || 'Tidak ada deskripsi'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Buku</label>
                    <p class="mt-1 text-sm text-gray-900">${sumber.buku_count || 0} buku</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                    <p class="mt-1 text-sm text-gray-900">${new Date(sumber.created_at).toLocaleDateString('id-ID')}</p>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        } else {
            showErrorAlert('Gagal memuat detail sumber buku');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat memuat data');
    });
}

function hideSumberModal() {
    document.getElementById('sumberModal').classList.add('hidden');
}

function hideDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function generateKodeSumber() {
    fetch('{{ route("sumber-buku.generate-kode") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('kode_sumber').value = data.kode;
        } else {
            showErrorAlert('Gagal generate kode: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat generate kode');
    });
}

// Handle form submission
document.getElementById('sumberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    const sumberId = document.getElementById('sumberId').value;
    const method = sumberId ? 'PUT' : 'POST';
    const url = sumberId ? `/admin/sumber-buku/${sumberId}` : '/admin/sumber-buku';
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    clearErrors();
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message);
            hideSumberModal();
            location.reload();
        } else {
            if (data.errors) {
                displayErrors(data.errors);
            } else {
                showErrorAlert(data.message || 'Terjadi kesalahan');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat menyimpan data');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="_error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

function displayErrors(errors) {
    for (const field in errors) {
        const errorElement = document.getElementById(field + '_error');
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
        }
    }
}

// Delete functions
function confirmDeleteSumber(id) {
    showConfirmDialog(
        'Yakin ingin menghapus sumber buku ini?',
        'Konfirmasi Hapus Sumber Buku',
        function() {
            deleteSumber(id);
        }
    );
}

function deleteSumber(id) {
    showLoading();
    
    fetch(`/admin/sumber-buku/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showSuccessAlert(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showErrorAlert(data.message || 'Gagal menghapus sumber buku');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat menghapus sumber buku');
    });
}

// Delete selected sumber
function deleteSelected() {
    const selectedIds = Array.from(document.querySelectorAll('.sumber-checkbox:checked')).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showWarningAlert('Pilih sumber buku yang akan dihapus');
        return;
    }

    showConfirmDialog(
        `Yakin ingin menghapus ${selectedIds.length} sumber buku yang dipilih?`,
        'Konfirmasi Hapus Sumber Buku',
        function() {
            showLoading();
            
            fetch('{{ route("sumber-buku.destroy-multiple") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ sumber_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    let message = data.message;
                    if (data.errors && data.errors.length > 0) {
                        message += '\n\nBeberapa sumber tidak dapat dihapus:\n' + data.errors.slice(0, 3).join('\n');
                        if (data.errors.length > 3) {
                            message += `\n... dan ${data.errors.length - 3} error lainnya`;
                        }
                    }
                    showSuccessAlert(message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showErrorAlert(data.message || 'Gagal menghapus sumber buku');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showErrorAlert('Terjadi kesalahan saat menghapus sumber buku');
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
@endsection
