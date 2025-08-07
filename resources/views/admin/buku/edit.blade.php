@extends('layouts.admin')

@section('title', 'Edit Buku')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Buku</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi buku "{{ $buku->judul_buku }}"</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('buku.show', $buku->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                    <a href="{{ route('buku.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('buku.update', $buku->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Buku <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul_buku" name="judul_buku" value="{{ old('judul_buku', $buku->judul_buku) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('judul_buku') border-red-500 @enderror"
                                   placeholder="Masukkan judul buku">
                            @error('judul_buku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                            <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $buku->isbn) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('isbn') border-red-500 @enderror"
                                   placeholder="Masukkan ISBN">
                            @error('isbn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tahun_terbit') border-red-500 @enderror"
                                   placeholder="Tahun terbit">
                            @error('tahun_terbit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_halaman" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Halaman</label>
                            <input type="number" id="jumlah_halaman" name="jumlah_halaman" value="{{ old('jumlah_halaman', $buku->jumlah_halaman) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlah_halaman') border-red-500 @enderror"
                                   placeholder="Jumlah halaman">
                            @error('jumlah_halaman')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bahasa" class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                            <input type="text" id="bahasa" name="bahasa" value="{{ old('bahasa', $buku->bahasa ?? 'Indonesia') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('bahasa') border-red-500 @enderror"
                                   placeholder="Contoh: Indonesia, Inggris, Arab">
                            @error('bahasa')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_stok" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok', $buku->jumlah_stok) }}" required min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlah_stok') border-red-500 @enderror"
                                   placeholder="Jumlah stok">
                            @error('jumlah_stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lokasi_rak" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Rak</label>
                            <input type="text" id="lokasi_rak" name="lokasi_rak" value="{{ old('lokasi_rak', $buku->lokasi_rak) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('lokasi_rak') border-red-500 @enderror"
                                   placeholder="Contoh: Rak A-1">
                            @error('lokasi_rak')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rak_id" class="block text-sm font-medium text-gray-700 mb-2">Rak Buku</label>
                            <select id="rak_id" name="rak_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('rak_id') border-red-500 @enderror">
                                <option value="">Pilih Rak Buku (Opsional)</option>
                                @foreach($rakBuku as $rak)
                                    <option value="{{ $rak->id }}" {{ old('rak_id', $buku->rak_id) == $rak->id ? 'selected' : '' }}>
                                        {{ $rak->nama_rak }} ({{ $rak->kode_rak }}) - {{ $rak->lokasi ?? 'Lokasi tidak ditentukan' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Pilih rak buku untuk mengelompokkan buku berdasarkan lokasi penyimpanan
                            </p>
                            @error('rak_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Author and Publisher -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-edit text-green-500 mr-2"></i>
                        Penulis & Penerbit
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">
                                Penulis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="penulis" name="penulis" required
                                   value="{{ old('penulis', $buku->penulis) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('penulis') border-red-500 @enderror"
                                   placeholder="Masukkan nama penulis">
                            @error('penulis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">
                                Penerbit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="penerbit" name="penerbit" required
                                   value="{{ old('penerbit', $buku->penerbit) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('penerbit') border-red-500 @enderror"
                                   placeholder="Masukkan nama penerbit">
                            @error('penerbit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Category and Type -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags text-purple-500 mr-2"></i>
                        Kategori & Jenis
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori_id" name="kategori_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('kategori_id') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $buku->kategori_id) == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Buku <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_id" name="jenis_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jenis_id') border-red-500 @enderror">
                                <option value="">Pilih Jenis</option>
                                @foreach($jenis as $jen)
                                    <option value="{{ $jen->id }}" {{ old('jenis_id', $buku->jenis_id) == $jen->id ? 'selected' : '' }}>
                                        {{ $jen->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sumber_id" class="block text-sm font-medium text-gray-700 mb-2">Sumber Buku</label>
                            <select id="sumber_id" name="sumber_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('sumber_id') border-red-500 @enderror">
                                <option value="">Pilih Sumber</option>
                                @foreach($sumber as $sum)
                                    <option value="{{ $sum->id }}" {{ old('sumber_id', $buku->sumber_id) == $sum->id ? 'selected' : '' }}>
                                        {{ $sum->nama_sumber }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sumber_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-image text-purple-500 mr-2"></i>
                        Gambar Sampul
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="gambar_sampul" class="block text-sm font-medium text-gray-700 mb-2">Gambar Sampul</label>
                            @if($buku->gambar_sampul)
                                <div class="mb-3">
                                    <img src="{{ asset('uploads/' . $buku->gambar_sampul) }}" 
                                         alt="Gambar Sampul" 
                                         class="w-32 h-40 object-cover rounded-lg border border-gray-300">
                                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini: {{ $buku->gambar_sampul }}</p>
                                </div>
                            @endif
                            <input type="file" id="gambar_sampul" name="gambar_sampul" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('gambar_sampul') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">
                                Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.
                            </p>
                            @error('gambar_sampul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-toggle-on text-green-500 mr-2"></i>
                        Status Buku
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('status') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="tersedia" {{ old('status', $buku->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak_tersedia" {{ old('status', $buku->status) == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Pilih status ketersediaan buku
                            </p>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-align-left text-orange-500 mr-2"></i>
                        Deskripsi
                    </h3>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Buku</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Masukkan deskripsi singkat tentang buku">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Barcode Info -->
                @if($buku->barcode)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-barcode text-blue-500 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-blue-800">Barcode Saat Ini</h4>
                            <p class="text-sm text-blue-700 mt-1 font-mono">{{ $buku->barcode }}</p>
                            <p class="text-xs text-blue-600 mt-1">Barcode tidak dapat diubah setelah dibuat.</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('buku.print-barcode', $buku->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-all duration-200"
                               target="_blank">
                                <i class="fas fa-print mr-1"></i>
                                Cetak
                            </a>
                            <a href="{{ route('buku.show', $buku->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-all duration-200">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-yellow-800">Barcode Belum Ada</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Buku ini belum memiliki barcode. Setelah disimpan, Anda dapat generate barcode melalui halaman detail buku.
                            </p>
                        </div>
                        <div>
                            <button type="button" id="generateBarcodeBtn" 
                                    class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-all duration-200"
                                    data-buku-id="{{ $buku->id }}">
                                <i class="fas fa-magic mr-1"></i>
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('buku.show', $buku->id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Update Buku
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
            alert('Mohon lengkapi semua field yang wajib diisi');
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
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

    // Generate barcode functionality
    const generateBarcodeBtn = document.getElementById('generateBarcodeBtn');
    if (generateBarcodeBtn) {
        generateBarcodeBtn.addEventListener('click', function() {
            const bukuId = this.getAttribute('data-buku-id');
            
            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Generating...';
            this.disabled = true;
            
            // Call API to generate barcode
            fetch(`/admin/buku/generate-barcode`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    buku_id: bukuId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show updated barcode
                    window.location.reload();
                } else {
                    alert('Gagal generate barcode: ' + data.message);
                    // Reset button
                    this.innerHTML = '<i class="fas fa-magic mr-1"></i>Generate';
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate barcode');
                // Reset button
                this.innerHTML = '<i class="fas fa-magic mr-1"></i>Generate';
                this.disabled = false;
            });
        });
    }
});
</script>
@endsection 