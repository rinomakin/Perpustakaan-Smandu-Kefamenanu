@extends('layouts.admin')

@section('title', 'Data Anggota')

@push('styles')
<style>
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
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
</style>
@endpush

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Data Anggota
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
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('anggota.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama, NIK, No. Anggota...">
            </div>
            
            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" id="kelas_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="jenis_anggota" class="block text-sm font-medium text-gray-700 mb-1">Jenis Anggota</label>
                <select name="jenis_anggota" id="jenis_anggota"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="siswa" {{ request('jenis_anggota') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('jenis_anggota') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="staff" {{ request('jenis_anggota') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="ditangguhkan" {{ request('status') == 'ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit flex items-center space-x-2" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-search mr-1"></i>Cari
                </button>
                <a href="{{ route('anggota.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-refresh mr-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tombol Aksi -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
        <div class="flex space-x-2">
            <a href="{{ route('anggota.create') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <i class="fas fa-plus mr-1"></i> Tambah Data
            </a>
            
            <button onclick="openModal('importModal')"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                <i class="fas fa-upload mr-1"></i> Import Data
            </button>
            
            <a href="{{ route('anggota.export') }}?{{ http_build_query(request()->all()) }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-lg active:bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow">
                <i class="fas fa-download mr-1"></i> Export Data
            </a>
            
            <a href="{{ route('anggota.download-template') }}"
               class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <i class="fas fa-file-download mr-1"></i> Download Template
            </a>
            
            <button onclick="bulkDelete()" id="bulkDeleteBtn" 
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red hidden">
                <i class="fas fa-trash mr-1"></i> Hapus Terpilih
            </button>
        </div>
        
        <div class="text-sm text-gray-600">
            Total: {{ $anggota->total() }} data
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
                        <!-- <th class="px-4 py-3">No. Anggota</th> -->
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">NIK</th>
                        <th class="px-4 py-3">Kelas/Jurusan</th>
                        <th class="px-4 py-3">Jenis Anggota</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($anggota as $index => $item)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" onchange="updateBulkDeleteButton()">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $index + $anggota->firstItem() }}
                        </td>
                        <!-- <td class="px-4 py-3 text-sm"> -->
                            <!-- <div class="font-medium text-gray-900">{{ $item->nomor_anggota }}</div> -->
                            <!-- <div class="text-xs text-gray-500 mb-1">{{ $item->barcode_anggota }}</div> -->
                            <!-- <img src="data:image/png;base64,{{ \App\Helpers\BarcodeHelper::generateBarcodeImage($item->barcode_anggota, 'C128') }}"  -->
                                 <!-- alt="Barcode" class="w-24 h-8 object-contain"> -->
                        <!-- </td> -->
                        <td class="px-4 py-3 text-sm">
                            <!-- <div class="flex items-center">
                                @if($item->foto)
                                    <img src="{{ asset('storage/anggota/' . $item->foto) }}" 
                                         alt="Foto" class="w-8 h-8 rounded-full mr-3">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                    </div>
                                @endif
                                <div> -->
                                    <div class="font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->email ?: '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->nik }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($item->kelas)
                                <div class="font-medium text-gray-900">{{ $item->kelas->nama_kelas }}</div>
                                <div class="text-xs text-gray-500">{{ $item->kelas->jurusan->nama_jurusan }}</div>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $item->jenis_anggota == 'siswa' ? 'bg-blue-100 text-blue-800' : 
                                   ($item->jenis_anggota == 'guru' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ ucfirst($item->jenis_anggota) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                {{ $item->status == 'aktif' ? 'text-green-700 bg-green-100' : 
                                   ($item->status == 'nonaktif' ? 'text-red-700 bg-red-100' : 'text-yellow-700 bg-yellow-100') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('anggota.show', $item->id) }}" 
                                   class="text-purple-500 hover:text-purple-700" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('anggota.edit', $item->id) }}" 
                                   class="text-blue-500 hover:text-blue-700" 
                                   title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('anggota.cetak-kartu', $item->id) }}" 
                                   target="_blank"
                                   class="text-green-500 hover:text-green-700" 
                                   title="Cetak Kartu">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-700" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-gray-700">
                        <td colspan="9" class="px-4 py-3 text-sm text-center">
                            Tidak ada data anggota.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    Menampilkan {{ $anggota->firstItem() ?? 0 }} sampai {{ $anggota->lastItem() ?? 0 }} dari {{ $anggota->total() }} data
                </div>
                <div>
                    {{ $anggota->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Data -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Import Data Anggota</h3>
                <button onclick="closeModal('importModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('anggota.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">File Excel/CSV <span class="text-red-500">*</span></label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: Excel (.xlsx, .xls) atau CSV. Maksimal 2MB</p>
                </div>
                
                <div class="mb-4 p-3 bg-blue-50 rounded-md">
                    <p class="text-sm text-blue-800">
                        <strong>Catatan:</strong><br>
                        • Download template terlebih dahulu<br>
                        • Format file: Excel (.xlsx, .xls) atau CSV<br>
                        • Pastikan format data sesuai template<br>
                        • NIK harus unik dan tidak boleh duplikat<br>
                        • Nomor anggota dan barcode akan digenerate otomatis<br>
                        • Template sudah berisi daftar kelas untuk referensi
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('importModal')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                        Import
                    </button>
                </div>
            </form>
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
    
    if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} data anggota?`)) {
        return;
    }
    
    // Get CSRF token safely
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value ||
                     '{{ csrf_token() }}';
    
    fetch('{{ route("anggota.bulk-delete") }}', {
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

// Close modal when clicking outside
window.onclick = function(event) {
    const importModal = document.getElementById('importModal');
    
    if (event.target === importModal) {
        closeModal('importModal');
    }
}

// Pastikan script dimuat setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Anggota functions loaded');
});
</script>
@endsection 