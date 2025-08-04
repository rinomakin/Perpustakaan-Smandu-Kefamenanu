@extends('layouts.admin')

@push('styles')
<style>
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .modal-backdrop {
        transition: opacity 0.3s ease;
    }
    
    .checkbox-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .checkbox-wrapper input[type="checkbox"] {
        opacity: 0;
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .checkbox-wrapper .checkmark {
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 2px solid #d1d5db;
        border-radius: 3px;
        display: inline-block;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .checkbox-wrapper input[type="checkbox"]:checked + .checkmark {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .checkbox-wrapper input[type="checkbox"]:checked + .checkmark:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    /* Modal improvements */
    #crudModal {
        backdrop-filter: blur(4px);
    }
    
    #crudModal .relative {
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Form improvements */
    #crudForm input:focus,
    #crudForm textarea:focus,
    #crudForm select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Loading animation */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Data Jenis Buku
    </h2>

    <!-- Alert Sukses -->
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Alert Validasi Error -->
    @if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Filter dan Pencarian -->


    <!-- Tombol Aksi -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
        <div class="flex space-x-2">
            <button onclick="openModal('tambahModal')"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <i class="fas fa-plus mr-1"></i> Tambah Data
            </button>
            
           
            
            
            <button onclick="bulkDelete()" id="bulkDeleteBtn" 
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red hidden">
                <i class="fas fa-trash mr-1"></i> Hapus Terpilih
            </button>
        </div>
        
        <div class="text-sm text-gray-600">
            Total: {{ $jenis->total() }} data
        </div>
    </div>

    <!-- Tabel -->
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Jenis</th>
                        <th class="px-4 py-3">Kode Jenis</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($jenis as $index => $item)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" onchange="updateBulkDeleteButton()">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $index + $jenis->firstItem() }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->nama_jenis }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->kode_jenis }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->deskripsi ?: '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $item->status ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('jenis-buku.show', $item->id) }}" class="text-green-500 hover:text-green-700 mr-2" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama_jenis ?? '' }}" 
                                    data-kode="{{ $item->kode_jenis ?? '' }}" 
                                    data-deskripsi="{{ $item->deskripsi ?? '' }}" 
                                    data-status="{{ $item->status ?? 1 }}"
                                    onclick="openEditModalFromData(this)" 
                                    class="text-blue-500 hover:text-blue-700 mr-2" 
                                    title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('jenis-buku.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-gray-700">
                        <td colspan="7" class="px-4 py-3 text-sm text-center">
                            Tidak ada data jenis buku.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    Menampilkan {{ $jenis->firstItem() ?? 0 }} sampai {{ $jenis->lastItem() ?? 0 }} dari {{ $jenis->total() }} data
                </div>
                <div>
                    {{ $jenis->links() }}
                </div>
            </div>
        </div>
    </div>

<!-- Modal Tambah Jenis Buku -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Jenis Buku</h3>
                <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="tambahForm" method="POST" action="{{ route('jenis-buku.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="nama_jenis" class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jenis" id="nama_jenis" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama jenis buku">
                </div>
                
                <div class="mb-4">
                    <label for="kode_jenis" class="block text-sm font-medium text-gray-700 mb-2">Kode Jenis <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_jenis" id="kode_jenis" required maxlength="10"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan kode jenis (maks. 10 karakter)">
                </div>
                
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" maxlength="500"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Masukkan deskripsi jenis buku (opsional, maks. 500 karakter)"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('tambahModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jenis Buku -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Jenis Buku</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="edit_nama_jenis" class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jenis" id="edit_nama_jenis" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama jenis buku">
                </div>
                
                <div class="mb-4">
                    <label for="edit_kode_jenis" class="block text-sm font-medium text-gray-700 mb-2">Kode Jenis <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_jenis" id="edit_kode_jenis" required maxlength="10"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan kode jenis (maks. 10 karakter)">
                </div>
                
                <div class="mb-4">
                    <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" maxlength="500"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Masukkan deskripsi jenis buku (opsional, maks. 500 karakter)"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                </div>
                
                <div class="mb-4">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="edit_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('editModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script>
// Pastikan fungsi tersedia secara global
window.openModal = function(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

window.closeModal = function(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Reset form jika modal tambah
    if (modalId === 'tambahModal') {
        document.getElementById('tambahForm').reset();
    }
}

window.openEditModal = function(id, nama_jenis, kode_jenis, deskripsi, status) {
    try {
        console.log('openEditModal called with:', {id, nama_jenis, kode_jenis, deskripsi, status});
        
        // Set form action
        document.getElementById('editForm').action = `{{ route('jenis-buku.update', '') }}/${id}`;
        
        // Set form values (handle null/undefined values)
        document.getElementById('edit_nama_jenis').value = nama_jenis || '';
        document.getElementById('edit_kode_jenis').value = kode_jenis || '';
        document.getElementById('edit_deskripsi').value = deskripsi || '';
        document.getElementById('edit_status').value = status || '1';
        
        // Open modal
        window.openModal('editModal');
    } catch (error) {
        console.error('Error in openEditModal:', error);
        alert('Terjadi kesalahan saat membuka modal edit');
    }
}

window.openEditModalFromData = function(button) {
    try {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const kode = button.getAttribute('data-kode');
        const deskripsi = button.getAttribute('data-deskripsi');
        const status = button.getAttribute('data-status');
        
        console.log('openEditModalFromData called with:', {id, nama, kode, deskripsi, status});
        
        // Set form action
        document.getElementById('editForm').action = `{{ route('jenis-buku.update', '') }}/${id}`;
        
        // Set form values (handle null/undefined values)
        document.getElementById('edit_nama_jenis').value = nama || '';
        document.getElementById('edit_kode_jenis').value = kode || '';
        document.getElementById('edit_deskripsi').value = deskripsi || '';
        document.getElementById('edit_status').value = status || '1';
        
        // Open modal
        window.openModal('editModal');
    } catch (error) {
        console.error('Error in openEditModalFromData:', error);
        alert('Terjadi kesalahan saat membuka modal edit');
    }
}

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        if (selectAllCheckbox && itemCheckboxes.length > 0) {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            updateBulkDeleteButton();
        }
    }

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        
        if (bulkDeleteBtn) {
            if (checkedBoxes.length > 0) {
                bulkDeleteBtn.classList.remove('hidden');
            } else {
                bulkDeleteBtn.classList.add('hidden');
            }
        }
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('Pilih data yang akan dihapus');
            return;
        }
        
        if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} data jenis buku?`)) {
            return;
        }
        
        // Get CSRF token safely
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value ||
                         '{{ csrf_token() }}';
        
        fetch('{{ route("jenis-buku.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }

    function exportData() {
        const search = document.getElementById('search').value;
        const status = document.getElementById('status').value;
        
        let url = '{{ route("jenis-buku.export") }}?';
        if (search) url += `search=${encodeURIComponent(search)}&`;
        if (status) url += `status=${status}&`;
        
        window.location.href = url;
    }



// Close modal when clicking outside
window.onclick = function(event) {
    const tambahModal = document.getElementById('tambahModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === tambahModal) {
        closeModal('tambahModal');
    }
    if (event.target === editModal) {
        closeModal('editModal');
    }
}

// Pastikan script dimuat setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Modal functions loaded');
    console.log('openModal function:', typeof window.openModal);
    console.log('openEditModal function:', typeof window.openEditModal);
});
</script>
@endsection