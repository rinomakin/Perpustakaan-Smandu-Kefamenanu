@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Data Anggota</h2>
            <div class="flex space-x-2">
                <button onclick="openModal('tambahModal')" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Data
                </button>
                <button onclick="bulkDelete()" id="bulkDeleteBtn"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors hidden">
                    <i class="fas fa-trash mr-2"></i>Hapus Terpilih
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
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
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('anggota.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Import/Export Buttons -->
    <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="flex space-x-2">
                <button onclick="openModal('importModal')" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-upload mr-2"></i>Import Data
                </button>
                <a href="{{ route('anggota.export') }}?{{ http_build_query(request()->all()) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-download mr-2"></i>Export Data
                </a>
                <a href="{{ route('anggota.download-template') }}" 
                   class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    <i class="fas fa-file-download mr-2"></i>Download Template
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No. Anggota
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Lengkap
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        NIK
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kelas/Jurusan
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jenis Anggota
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($anggota as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" onchange="updateBulkDeleteButton()">
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $item->nomor_anggota }}</div>
                        <div class="text-xs text-gray-500">{{ $item->barcode_anggota }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center">
                            @if($item->foto)
                                <img src="{{ asset('storage/anggota/' . $item->foto) }}" 
                                     alt="Foto" class="w-8 h-8 rounded-full mr-3">
                            @else
                                <div class="w-8 h-8 bg-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-xs"></i>
                                </div>
                            @endif
                            <div>
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
                            <button data-id="{{ $item->id }}" 
                                    data-nama="{{ $item->nama_lengkap ?? '' }}" 
                                    data-nik="{{ $item->nik ?? '' }}" 
                                    data-alamat="{{ $item->alamat ?? '' }}" 
                                    data-telepon="{{ $item->nomor_telepon ?? '' }}" 
                                    data-email="{{ $item->email ?? '' }}" 
                                    data-kelas="{{ $item->kelas_id ?? '' }}" 
                                    data-jabatan="{{ $item->jabatan ?? '' }}" 
                                    data-jenis="{{ $item->jenis_anggota ?? '' }}" 
                                    data-status="{{ $item->status ?? '' }}" 
                                    data-bergabung="{{ $item->tanggal_bergabung ?? '' }}"
                                    onclick="openEditModalFromData(this)" 
                                    class="text-blue-500 hover:text-blue-700" 
                                    title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </button>
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
                    <td colspan="8" class="px-4 py-3 text-sm text-center">
                        Tidak ada data anggota.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
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

<!-- Modal Tambah Anggota -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Anggota</h3>
                <button onclick="closeModal('tambahModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="tambahForm" method="POST" action="{{ route('anggota.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="mb-4">
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" required maxlength="16"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan NIK (16 digit)">
                </div>
                
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan email (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelas (opsional)</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan jabatan (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="jenis_anggota" class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota <span class="text-red-500">*</span></label>
                    <select name="jenis_anggota" id="jenis_anggota" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Anggota</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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

<!-- Modal Edit Anggota -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Anggota</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="edit_nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="mb-4">
                    <label for="edit_nik" class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="edit_nik" required maxlength="16"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan NIK (16 digit)">
                </div>
                
                <div class="mb-4">
                    <label for="edit_alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="edit_alamat" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="edit_nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_telepon" id="edit_nomor_telepon" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div class="mb-4">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="edit_email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan email (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="edit_kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="edit_kelas_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelas (opsional)</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="edit_jabatan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan jabatan (opsional)">
                </div>
                
                <div class="mb-4">
                    <label for="edit_jenis_anggota" class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota <span class="text-red-500">*</span></label>
                    <select name="jenis_anggota" id="edit_jenis_anggota" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Anggota</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                    <input type="file" name="foto" id="edit_foto" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                </div>
                
                <div class="mb-4">
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="edit_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_tanggal_bergabung" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bergabung" id="edit_tanggal_bergabung" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">File CSV <span class="text-red-500">*</span></label>
                    <input type="file" name="file" id="file" accept=".csv,.txt" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: CSV. Maksimal 2MB</p>
                </div>
                
                <div class="mb-4 p-3 bg-blue-50 rounded-md">
                    <p class="text-sm text-blue-800">
                        <strong>Catatan:</strong><br>
                        • Download template terlebih dahulu<br>
                        • Pastikan format data sesuai template<br>
                        • NIK harus unik dan tidak boleh duplikat<br>
                        • Nomor anggota dan barcode akan digenerate otomatis
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
    // Reset form jika modal tambah
    if (modalId === 'tambahModal') {
        document.getElementById('tambahForm').reset();
    }
}

window.openEditModalFromData = function(button) {
    try {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const nik = button.getAttribute('data-nik');
        const alamat = button.getAttribute('data-alamat');
        const telepon = button.getAttribute('data-telepon');
        const email = button.getAttribute('data-email');
        const kelas = button.getAttribute('data-kelas');
        const jabatan = button.getAttribute('data-jabatan');
        const jenis = button.getAttribute('data-jenis');
        const status = button.getAttribute('data-status');
        const bergabung = button.getAttribute('data-bergabung');
        
        console.log('openEditModalFromData called with:', {id, nama, nik, alamat, telepon, email, kelas, jabatan, jenis, status, bergabung});
        
        // Set form action
        document.getElementById('editForm').action = `{{ route('anggota.update', '') }}/${id}`;
        
        // Set form values (handle null/undefined values)
        document.getElementById('edit_nama_lengkap').value = nama || '';
        document.getElementById('edit_nik').value = nik || '';
        document.getElementById('edit_alamat').value = alamat || '';
        document.getElementById('edit_nomor_telepon').value = telepon || '';
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_kelas_id').value = kelas || '';
        document.getElementById('edit_jabatan').value = jabatan || '';
        document.getElementById('edit_jenis_anggota').value = jenis || '';
        document.getElementById('edit_status').value = status || '';
        document.getElementById('edit_tanggal_bergabung').value = bergabung || '';
        
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
    const tambahModal = document.getElementById('tambahModal');
    const editModal = document.getElementById('editModal');
    const importModal = document.getElementById('importModal');
    
    if (event.target === tambahModal) {
        closeModal('tambahModal');
    }
    if (event.target === editModal) {
        closeModal('editModal');
    }
    if (event.target === importModal) {
        closeModal('importModal');
    }
}

// Pastikan script dimuat setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Anggota modal functions loaded');
    console.log('openModal function:', typeof window.openModal);
    console.log('openEditModalFromData function:', typeof window.openEditModalFromData);
});
</script>
@endsection 