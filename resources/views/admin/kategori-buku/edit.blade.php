@extends('layouts.admin')

@section('title', 'Edit Kategori Buku')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Kategori Buku</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi kategori "{{ $kategoriBuku->nama_kategori }}"</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kategori-buku.show', $kategoriBuku->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('kategori-buku.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('kategori-buku.update', $kategoriBuku->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Category Name -->
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategoriBuku->nama_kategori) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Contoh: Fiksi, Non-Fiksi, Pendidikan, dll">
                    @error('nama_kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Masukkan deskripsi singkat tentang kategori ini (opsional)">{{ old('deskripsi', $kategoriBuku->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Usage Info -->
                @php
                    $bukuCount = $kategoriBuku->buku()->count();
                @endphp
                @if($bukuCount > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Kategori Sedang Digunakan</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Kategori ini sedang digunakan oleh {{ $bukuCount }} buku. 
                                Perubahan nama kategori akan mempengaruhi semua buku dalam kategori ini.
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-green-800">Kategori Belum Digunakan</h4>
                            <p class="text-sm text-green-700 mt-1">
                                Kategori ini belum digunakan oleh buku manapun, sehingga dapat diubah dengan aman.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('kategori-buku.show', $kategoriBuku->id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            showWarningAlert('Mohon lengkapi semua field yang wajib diisi');
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>
@endsection 