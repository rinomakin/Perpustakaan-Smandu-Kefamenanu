@extends('layouts.admin')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

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
                    @if(Auth::user()->hasPermission('anggota.export') || Auth::user()->isAdmin())
                    <a href="{{ route('anggota.export', request()->query()) }}" 
                       class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export
                    </a>
                    @endif
                    
                    @if(Auth::user()->hasPermission('anggota.import') || Auth::user()->isAdmin())
                    <a href="{{ route('anggota.download-template') }}" 
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
                
                @if(Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                <!-- Bulk Action Buttons (Hidden by default) -->
                <div id="bulkActionButtons" class="flex items-center gap-2 opacity-0 transition-all duration-300 ease-in-out">
                    <div class="flex items-center gap-2">
                        @if(Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                        <button onclick="bulkPrintKartu()" 
                                class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-print mr-2"></i>
                            Cetak
                        </button>
                        @endif
                        @if(Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin())
                        <button onclick="bulkDelete()" 
                                class="inline-flex  items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus 
                        </button>
                        @endif
                    </div>
                    <span id="selectedCount" class=" text-gray-500 transition-all duration-200 mr-2 text-[10px] font-medium bg-gray-100 px-2 py-1 rounded-full">0 anggota dipilih</span>
                </div>
                @endif
            </div>
            
            <!-- Right side - Search, Filter and Add Button -->
            <div class="flex items-center gap-3">
                <!-- Search Input -->
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari anggota..." 
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
                
                @if(Auth::user()->hasPermission('anggota.create') || Auth::user()->isAdmin())
                <a href="{{ route('anggota.create') }}" 
                   class="inline-flex items-center text-xs px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah
                </a>
                @endif
            </div>
        </div>
    </div>
    

    <!-- Members Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto ">
            <table class="w-full divide-y  divide-gray-950" style="min-width: 900px;">
                <thead class="">
                    <tr class="border-b border-gray-200">
                        @if(Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                <span class="ml-2 text-xs text-gray-500 transition-all duration-200">Pilih</span>
                            </div>
                        </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kelamin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas/Jurusan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        @if(Auth::user()->hasPermission('anggota.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 w-full ">
                    @forelse($anggota as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        @if(Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="member-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200" value="{{ $item->id }}">
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + $anggota->firstItem() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item->foto)
                                    <img src="{{ asset('storage/anggota/' . $item->foto) }}" 
                                         alt="Foto" class="w-8 h-8 rounded-full mr-3 object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->email ?: '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $item->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                <i class="fas {{ $item->jenis_kelamin == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                {{ $item->jenis_kelamin ?: '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->nik }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->kelas)
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $item->kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->kelas->jurusan->nama_jurusan }}</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">Tidak ada kelas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $item->jenis_anggota == 'siswa' ? 'bg-blue-100 text-blue-800' : 
                                   ($item->jenis_anggota == 'guru' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ ucfirst($item->jenis_anggota) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800' : 
                                   ($item->status == 'nonaktif' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                <span class="w-2 h-2 rounded-full mr-1.5
                                    {{ $item->status == 'aktif' ? 'bg-green-400' : 
                                       ($item->status == 'nonaktif' ? 'bg-red-400' : 'bg-yellow-400') }}"></span>
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        @if(Auth::user()->hasPermission('anggota.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @if(Auth::user()->hasPermission('anggota.view') || Auth::user()->isAdmin())
                                <a href="{{ route('anggota.show', $item->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('anggota.update') || Auth::user()->isAdmin())
                                <a href="{{ route('anggota.edit', $item->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin())
                                <a href="{{ route('anggota.cetak-kartu', $item->id) }}" 
                                   class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Cetak Kartu" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                @endif
                                @if(Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin())
                                <button onclick="confirmDeleteAnggota({{ $item->id }})" 
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
                            8 + 
                            (Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin() ? 1 : 0) + 
                            (Auth::user()->hasPermission('anggota.update') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.delete') || Auth::user()->isAdmin() || Auth::user()->hasPermission('anggota.cetak-kartu') || Auth::user()->isAdmin() ? 1 : 0) 
                        }}" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada anggota ditemukan</h3>
                            <p class="text-gray-600 mb-6">Belum ada data anggota yang ditambahkan atau tidak ada anggota yang sesuai dengan filter.</p>
                            @if(Auth::user()->hasPermission('anggota.create') || Auth::user()->isAdmin())
                            <a href="{{ route('anggota.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Anggota Pertama
                            </a>
                            @else
                            <p class="text-gray-500">Tidak ada data anggota tersedia.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($anggota->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $anggota->firstItem() ?? 0 }} - {{ $anggota->lastItem() ?? 0 }} dari {{ $anggota->total() }} anggota
            </div>
            <div class="flex items-center space-x-2">
                {{ $anggota->appends(request()->query())->links() }}
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
                    <h3 class="text-lg font-semibold text-white">Filter Anggota</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="filterForm" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kelas Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select name="kelas_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jurusan Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                        <select name="jurusan_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Anggota Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota</label>
                        <select name="jenis_anggota" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Jenis</option>
                            <option value="siswa" {{ request('jenis_anggota') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ request('jenis_anggota') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="staff" {{ request('jenis_anggota') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="ditangguhkan" {{ request('status') == 'ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                        </select>
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

@if(Auth::user()->hasPermission('anggota.import') || Auth::user()->isAdmin())
<!-- Modal Import Data -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Import Data Anggota</h3>
                    <button onclick="closeImportModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('anggota.import') }}" enctype="multipart/form-data" class="p-6">
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
                    <button type="button" onclick="closeImportModal()"
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
@endif

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
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');
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
                showLoadingOverlay();
                const currentUrl = new URL(window.location.href);
                const params = new URLSearchParams(currentUrl.search);
                
                if (searchValue.trim()) {
                    params.set('search', searchValue);
                } else {
                    params.delete('search');
                }
                
                window.location.href = currentUrl.pathname + '?' + params.toString();
            }, 500);
        });
    }

    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            memberCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
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
    memberCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAllState();
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            } else {
                row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            }
        });
    });

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
        if (selectedCount) {
            selectedCount.textContent = `${checkedBoxes.length} anggota dipilih`;
        }
        
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
        const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
        const totalBoxes = memberCheckboxes.length;
        
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
    const form = document.getElementById('filterForm');
    form.reset();
    window.location.href = '{{ route("anggota.index") }}';
}

// Import Modal Functions
function showImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

// Handle filter form submission
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showLoadingOverlay();
    
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput && searchInput.value.trim()) {
        params.set('search', searchInput.value.trim());
    }
    
    for (let [key, value] of formData.entries()) {
        if (value.trim()) {
            params.set(key, value);
        }
    }
    
    window.location.href = '{{ route("anggota.index") }}' + '?' + params.toString();
});

// Close modal when clicking outside
document.getElementById('filterModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFilterModal();
    }
});

// Bulk operations
function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Pilih data yang akan dihapus');
        return;
    }
    
    if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} data anggota?`)) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
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
}

function bulkPrintKartu() {
    const checkedBoxes = document.querySelectorAll('.member-checkbox:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Pilih data yang akan dicetak kartunya');
        return;
    }
    
    const url = '{{ route("anggota.bulk-print-kartu") }}?ids=' + ids.join(',');
    window.open(url, '_blank');
}

function confirmDeleteAnggota(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("anggota.index") }}/' + id;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}
</script>
@endsection