@extends('layouts.admin')

@section('title', 'Data Jurusan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Data Jurusan</h2>
        <button onclick="openModal('tambahModal')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Tambah Jurusan</span>
        </button>
    </div>

    <!-- Tabel Data Jurusan -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jurusan as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 + ($jurusan->currentPage() - 1) * $jurusan->perPage() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kode_jurusan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_jurusan }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->deskripsi ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ $item->id }}, '{{ $item->kode_jurusan }}', '{{ $item->nama_jurusan }}', '{{ $item->deskripsi }}', {{ $item->status }})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteJurusan({{ $item->id }})" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data jurusan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $jurusan->links() }}
    </div>
</div>

<!-- Modal Tambah Jurusan -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Jurusan</h3>
                <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="tambahForm" method="POST" action="{{ route('jurusan.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="kode_jurusan" class="block text-sm font-medium text-gray-700 mb-2">Kode Jurusan</label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="nama_jurusan" class="block text-sm font-medium text-gray-700 mb-2">Nama Jurusan</label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
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

<!-- Modal Edit Jurusan -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Jurusan</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_kode_jurusan" class="block text-sm font-medium text-gray-700 mb-2">Kode Jurusan</label>
                    <input type="text" name="kode_jurusan" id="edit_kode_jurusan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_nama_jurusan" class="block text-sm font-medium text-gray-700 mb-2">Nama Jurusan</label>
                    <input type="text" name="nama_jurusan" id="edit_nama_jurusan" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="edit_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
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

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(id, kode, nama, deskripsi, status) {
    document.getElementById('editForm').action = `{{ route('jurusan.index') }}/${id}`;
    document.getElementById('edit_kode_jurusan').value = kode;
    document.getElementById('edit_nama_jurusan').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_status').value = status;
    openModal('editModal');
}

function deleteJurusan(id) {
    showConfirmDialog(
        'Apakah Anda yakin ingin menghapus data jurusan ini?',
        'Konfirmasi Hapus Jurusan',
        function() {
            const form = document.getElementById('deleteForm');
            form.action = `{{ route('jurusan.index') }}/${id}`;
            form.submit();
        }
    );
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
</script>
@endsection 