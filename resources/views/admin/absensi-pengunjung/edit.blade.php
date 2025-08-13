@extends('layouts.admin')

@section('title', 'Edit Absensi Pengunjung')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-yellow-600 to-orange-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">✏️ Edit Absensi Pengunjung</h1>
                <p class="text-yellow-100 mt-1">Perbarui informasi absensi pengunjung perpustakaan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.absensi-pengunjung.show', $absensi->id) }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Lihat Detail
                </a>
                <a href="{{ route('admin.absensi-pengunjung.index') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Current Data Display -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
            Data Saat Ini
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-sm font-medium text-gray-700 mb-1">Anggota</div>
                <div class="text-lg font-semibold text-blue-600">{{ $absensi->anggota->nama_lengkap }}</div>
                <div class="text-sm text-gray-500">{{ $absensi->anggota->nomor_anggota }}</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-sm font-medium text-gray-700 mb-1">Waktu Masuk</div>
                <div class="text-lg font-semibold text-green-600">{{ $absensi->waktu_masuk->format('H:i:s') }}</div>
                <div class="text-sm text-gray-500">{{ $absensi->waktu_masuk->format('d/m/Y') }}</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-sm font-medium text-gray-700 mb-1">Keterangan</div>
                <div class="text-lg font-semibold text-purple-600">{{ $absensi->keterangan ?: 'Tidak ada' }}</div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-edit mr-2 text-yellow-600"></i>
            Form Edit Absensi
        </h2>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <div>
                    <h4 class="font-medium">Terjadi kesalahan:</h4>
                    <ul class="list-disc list-inside mt-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.absensi-pengunjung.update', $absensi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Member Selection -->
                <div>
                    <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>
                        Pilih Anggota <span class="text-red-500">*</span>
                    </label>
                    <select id="anggota_id" name="anggota_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('anggota_id') border-red-500 @enderror">
                        <option value="">Pilih anggota...</option>
                        @foreach($anggota as $member)
                            <option value="{{ $member->id }}" 
                                    {{ old('anggota_id', $absensi->anggota_id) == $member->id ? 'selected' : '' }}
                                    data-kelas="{{ $member->kelas ? $member->kelas->nama_kelas : '-' }}"
                                    data-jurusan="{{ $member->jurusan ? $member->jurusan->nama_jurusan : '-' }}">
                                {{ $member->nama_lengkap }} - {{ $member->nomor_anggota }}
                                @if($member->kelas)
                                    ({{ $member->kelas->nama_kelas }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('anggota_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Input -->
                <div>
                    <label for="waktu_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-1"></i>
                        Waktu Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="waktu_masuk" name="waktu_masuk" 
                           value="{{ old('waktu_masuk', $absensi->waktu_masuk->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('waktu_masuk') border-red-500 @enderror">
                    @error('waktu_masuk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="lg:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Keterangan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3" 
                              placeholder="Masukkan keterangan tambahan (opsional)..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Selected Member Info -->
            <div id="selected-member-info" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
                <h3 class="font-medium text-gray-900 mb-2">Informasi Anggota Terpilih:</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Nama:</span>
                        <span id="selected-name" class="text-gray-900"></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Kelas:</span>
                        <span id="selected-kelas" class="text-gray-900"></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Jurusan:</span>
                        <span id="selected-jurusan" class="text-gray-900"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.absensi-pengunjung.show', $absensi->id) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const anggotaSelect = document.getElementById('anggota_id');
    const selectedMemberInfo = document.getElementById('selected-member-info');
    const selectedName = document.getElementById('selected-name');
    const selectedKelas = document.getElementById('selected-kelas');
    const selectedJurusan = document.getElementById('selected-jurusan');

    function updateSelectedMemberInfo() {
        const selectedOption = anggotaSelect.options[anggotaSelect.selectedIndex];
        
        if (anggotaSelect.value) {
            selectedName.textContent = selectedOption.text.split(' - ')[0];
            selectedKelas.textContent = selectedOption.dataset.kelas;
            selectedJurusan.textContent = selectedOption.dataset.jurusan;
            selectedMemberInfo.classList.remove('hidden');
        } else {
            selectedMemberInfo.classList.add('hidden');
        }
    }

    anggotaSelect.addEventListener('change', updateSelectedMemberInfo);
    
    // Initialize on page load
    updateSelectedMemberInfo();
});
</script>
@endsection
