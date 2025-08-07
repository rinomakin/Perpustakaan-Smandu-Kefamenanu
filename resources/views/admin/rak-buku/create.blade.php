@extends('layouts.admin')

@section('title', 'Tambah Rak Buku')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Rak Buku</h1>
                <p class="text-gray-600 mt-1">Tambah rak buku baru ke sistem perpustakaan</p>
            </div>
            <a href="{{ route('rak-buku.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('rak-buku.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Rak -->
                <div>
                    <label for="nama_rak" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Rak <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_rak" 
                           name="nama_rak" 
                           value="{{ old('nama_rak') }}"
                           placeholder="Contoh: Rak Fiksi, Rak Non-Fiksi, dll"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nama_rak') border-red-500 @enderror">
                    @error('nama_rak')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Rak -->
                <div>
                    <label for="kode_rak" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Rak <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_rak" 
                           name="kode_rak" 
                           value="{{ old('kode_rak') }}"
                           placeholder="Contoh: RK001, RK002, dll"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('kode_rak') border-red-500 @enderror">
                    @error('kode_rak')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi
                    </label>
                    <input type="text" 
                           id="lokasi" 
                           name="lokasi" 
                           value="{{ old('lokasi') }}"
                           placeholder="Contoh: Lantai 1, Ruang Baca, dll"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('lokasi') border-red-500 @enderror">
                    @error('lokasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kapasitas <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="kapasitas" 
                           name="kapasitas" 
                           value="{{ old('kapasitas') }}"
                           min="1"
                           placeholder="Jumlah maksimal buku yang dapat disimpan"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('kapasitas') border-red-500 @enderror">
                    @error('kapasitas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('status') border-red-500 @enderror">
                        <option value="">Pilih Status</option>
                        <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="4"
                          placeholder="Deskripsi atau catatan tambahan tentang rak buku ini..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('rak-buku.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Rak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate kode rak jika kosong
    const namaRakInput = document.getElementById('nama_rak');
    const kodeRakInput = document.getElementById('kode_rak');
    
    namaRakInput.addEventListener('input', function() {
        if (kodeRakInput.value === '') {
            const nama = this.value.trim();
            if (nama) {
                // Generate kode dari nama rak
                const kode = 'RK' + nama.replace(/[^A-Za-z0-9]/g, '').substring(0, 3).toUpperCase() + 
                           Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                kodeRakInput.value = kode;
            }
        }
    });
});
</script>
@endsection
