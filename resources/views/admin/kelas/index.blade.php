@extends('layouts.admin')

@section('title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Data Kelas</h2>
        <button onclick="openModal('tambahModal')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Tambah Kelas</span>
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Tabel Data Kelas -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kelas as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->kode_kelas }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_kelas }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jurusan->nama_jurusan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->tahun_ajaran }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->anggota->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ $item->id }}, '{{ $item->kode_kelas }}', '{{ $item->nama_kelas }}', {{ $item->jurusan_id }}, '{{ $item->tahun_ajaran }}', {{ $item->status }})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteKelas({{ $item->id }})" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data kelas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $kelas->links() }}
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Kelas</h3>
                <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="tambahForm" method="POST" action="{{ route('kelas.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" required onchange="generateKodeKelas()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusan as $jur)
                            <option value="{{ $jur->id }}" data-kode="{{ $jur->kode_jurusan }}">{{ $jur->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" id="tahun_ajaran" required placeholder="2024/2025" onchange="generateKodeKelas()"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="kode_kelas" class="block text-sm font-medium text-gray-700 mb-2">Kode Kelas</label>
                    <input type="text" name="kode_kelas" id="kode_kelas" required readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed"
                           placeholder="Kode akan digenerate otomatis">
                </div>
                
                <div class="mb-4">
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: X TKJ 1">
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

<!-- Modal Edit Kelas -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Kelas</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="edit_jurusan_id" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan_id" id="edit_jurusan_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusan as $jur)
                            <option value="{{ $jur->id }}">{{ $jur->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="edit_kode_kelas" class="block text-sm font-medium text-gray-700 mb-2">Kode Kelas</label>
                    <input type="text" id="edit_kode_kelas" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                    <small class="text-gray-500">Kode kelas tidak dapat diubah</small>
                </div>
                
                <div class="mb-4">
                    <label for="edit_nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    // Reset form jika modal tambah
    if (modalId === 'tambahModal') {
        document.getElementById('tambahForm').reset();
        document.getElementById('kode_kelas').value = '';
    }
}

function openEditModal(id, kode, nama, jurusan_id, tahun_ajaran, status) {
    document.getElementById('editForm').action = `{{ route('kelas.index') }}/${id}`;
    document.getElementById('edit_kode_kelas').value = kode;
    document.getElementById('edit_nama_kelas').value = nama;
    document.getElementById('edit_jurusan_id').value = jurusan_id;
    document.getElementById('edit_tahun_ajaran').value = tahun_ajaran;
    document.getElementById('edit_status').value = status;
    openModal('editModal');
}

function deleteKelas(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data kelas ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ route('kelas.index') }}/${id}`;
        form.submit();
    }
}

// Generate kode kelas otomatis
function generateKodeKelas() {
    const jurusanId = document.getElementById('jurusan_id').value;
    const tahunAjaran = document.getElementById('tahun_ajaran').value;
    
    if (jurusanId && tahunAjaran) {
        // Validasi format tahun ajaran
        const tahunPattern = /^\d{4}\/\d{4}$/;
        if (!tahunPattern.test(tahunAjaran)) {
            alert('Format tahun ajaran harus YYYY/YYYY (contoh: 2024/2025)');
            document.getElementById('tahun_ajaran').focus();
            return;
        }
        
        // Ajax request untuk generate kode
        $.ajax({
            url: '{{ route("kelas.generate-kode") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                jurusan_id: jurusanId,
                tahun_ajaran: tahunAjaran
            },
            success: function(response) {
                document.getElementById('kode_kelas').value = response.kode;
            },
            error: function() {
                alert('Terjadi kesalahan saat generate kode kelas');
            }
        });
    }
}

// Validasi tahun ajaran saat input
document.getElementById('tahun_ajaran').addEventListener('input', function(e) {
    let value = e.target.value;
    // Hanya izinkan angka dan slash
    value = value.replace(/[^0-9\/]/g, '');
    
    // Auto format saat user mengetik
    if (value.length === 4 && !value.includes('/')) {
        value += '/';
    }
    
    e.target.value = value;
});

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
