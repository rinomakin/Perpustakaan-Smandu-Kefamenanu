@extends('layouts.admin')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Buku Baru</h1>
                    <p class="text-gray-600 mt-1">Masukkan informasi lengkap buku yang akan ditambahkan</p>
                </div>
                <a href="{{ route('buku.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Error Display -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-red-800">Terjadi Kesalahan</h4>
                            <ul class="text-sm text-red-700 mt-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
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
                            <input type="text" id="judul_buku" name="judul_buku" value="{{ old('judul_buku') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('judul_buku') border-red-500 @enderror"
                                   placeholder="Masukkan judul buku">
                            @error('judul_buku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                            <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('isbn') border-red-500 @enderror"
                                   placeholder="Masukkan ISBN">
                            @error('isbn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tahun_terbit') border-red-500 @enderror"
                                   placeholder="Tahun terbit">
                            @error('tahun_terbit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_halaman" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Halaman</label>
                            <input type="number" id="jumlah_halaman" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlah_halaman') border-red-500 @enderror"
                                   placeholder="Jumlah halaman">
                            @error('jumlah_halaman')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bahasa" class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                            <input type="text" id="bahasa" name="bahasa" value="{{ old('bahasa', 'Indonesia') }}"
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
                            <input type="number" id="jumlah_stok" name="jumlah_stok" value="{{ old('jumlah_stok', 1) }}" required min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('jumlah_stok') border-red-500 @enderror"
                                   placeholder="Jumlah stok">
                            @error('jumlah_stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rak_id" class="block text-sm font-medium text-gray-700 mb-2">Rak Buku</label>
                            <select id="rak_id" name="rak_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('rak_id') border-red-500 @enderror">
                                <option value="">Pilih Rak Buku</option>
                                @foreach($rakBuku as $rak)
                                    <option value="{{ $rak->id }}" {{ old('rak_id') == $rak->id ? 'selected' : '' }}>
                                        {{ $rak->nama_rak }}  - {{ $rak->lokasi ?? 'Lokasi tidak ditentukan' }}
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
                                   value="{{ old('penulis') }}"
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
                                   value="{{ old('penerbit') }}"
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
                                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
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
                                    <option value="{{ $jen->id }}" {{ old('jenis_id') == $jen->id ? 'selected' : '' }}>
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
                                    <option value="{{ $sum->id }}" {{ old('sumber_id') == $sum->id ? 'selected' : '' }}>
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
                                  placeholder="Masukkan deskripsi singkat tentang buku">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Barcode Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-barcode text-indigo-500 mr-2"></i>
                        Barcode
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                                Barcode (Opsional)
                            </label>
                            <div class="flex space-x-2">
                                <input type="text" id="barcode" name="barcode" value="{{ old('barcode') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('barcode') border-red-500 @enderror"
                                       placeholder="Masukkan barcode manual atau scan">
                                <button type="button" id="scanBarcodeBtn" 
                                        class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    Scan
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Kosongkan untuk generate otomatis, atau masukkan barcode manual
                            </p>
                            @error('barcode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end">
                            <button type="button" id="generateBarcodeBtn" 
                                    class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-magic mr-2"></i>
                                Generate Barcode Otomatis
                            </button>
                        </div>
                    </div>

                    <!-- Barcode Scanner Modal -->
                    <div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-white">Scan Barcode Buku</h3>
                                        <button type="button" id="closeScannerBtn" class="text-white hover:text-gray-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="mb-4">
                                        <p class="text-gray-600 mb-4">Arahkan kamera ke barcode buku untuk scan</p>
                                        <div id="scannerContainer" class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                                            <div id="scannerPlaceholder" class="text-center">
                                                <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                                                <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
                                            </div>
                                            <div id="scannerVideo" class="w-full h-full hidden">
                                                <video id="scannerVideoElement" class="w-full h-full object-cover"></video>
                                                <div id="scannerOverlay" class="absolute inset-0 flex items-center justify-center">
                                                    <div class="border-2 border-white border-dashed w-64 h-32 rounded-lg flex items-center justify-center">
                                                        <div class="text-white text-center">
                                                            <i class="fas fa-barcode text-2xl mb-2"></i>
                                                            <p class="text-sm">Arahkan barcode ke dalam kotak</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="scannerLoading" class="absolute inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
                                                <div class="text-center text-white">
                                                    <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                                                    <p>Memulai kamera...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <span id="scannerStatus">Siap untuk scan</span>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" id="startScanBtn" 
                                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold">
                                                <i class="fas fa-play mr-2"></i>Mulai Scan
                                            </button>
                                            <button type="button" id="stopScanBtn" 
                                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold hidden">
                                                <i class="fas fa-stop mr-2"></i>Stop Scan
                                            </button>
                                            <button type="button" id="cancelScan" 
                                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <input type="file" id="gambar_sampul" name="gambar_sampul" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('gambar_sampul') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">
                                Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ada gambar.
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
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak_tersedia" {{ old('status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
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

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Informasi Barcode</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Anda dapat memasukkan barcode manual, scan barcode, atau biarkan sistem generate otomatis. 
                                Barcode akan otomatis di-generate jika field dibiarkan kosong.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('buku.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill current year for tahun_terbit if empty
    const tahunTerbitInput = document.getElementById('tahun_terbit');
    if (!tahunTerbitInput.value) {
        tahunTerbitInput.value = new Date().getFullYear();
    }

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

    // Barcode functionality
    const barcodeInput = document.getElementById('barcode');
    const scanBarcodeBtn = document.getElementById('scanBarcodeBtn');
    const generateBarcodeBtn = document.getElementById('generateBarcodeBtn');
    const scannerModal = document.getElementById('scannerModal');
    const closeScannerBtn = document.getElementById('closeScannerBtn');
    const startScanBtn = document.getElementById('startScanBtn');
    const stopScanBtn = document.getElementById('stopScanBtn');
    const scannerContainer = document.getElementById('scannerContainer');

    // Generate barcode button
    generateBarcodeBtn.addEventListener('click', function() {
        // Generate a sample barcode (in real implementation, this would call the server)
        const prefix = 'BK';
        const timestamp = Date.now().toString().slice(-6);
        const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        const generatedBarcode = prefix + timestamp + randomNum;
        
        barcodeInput.value = generatedBarcode;
        
        // Show success message
        showNotification('Barcode berhasil di-generate: ' + generatedBarcode, 'success');
    });

    // Scanner functionality
    let quaggaInitialized = false;

    // Scan barcode button
    scanBarcodeBtn.addEventListener('click', function() {
        scannerModal.classList.remove('hidden');
        initializeScanner();
    });

    // Close scanner modal
    closeScannerBtn.addEventListener('click', function() {
        closeScanner();
    });

    // Start scanning
    startScanBtn.addEventListener('click', function() {
        startScanning();
    });

    // Stop scanning
    stopScanBtn.addEventListener('click', function() {
        stopScanning();
    });

    // Cancel scan
    document.getElementById('cancelScan').addEventListener('click', function() {
        closeScanner();
    });

    // Close modal when clicking outside
    scannerModal.addEventListener('click', function(e) {
        if (e.target === scannerModal) {
            closeScanner();
        }
    });

    // Load ZXing library for better barcode detection
    function loadZXingLibrary() {
        return new Promise((resolve, reject) => {
            if (window.ZXing) {
                resolve(window.ZXing);
                return;
            }
            
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/@zxing/library@latest/umd/index.min.js';
            script.onload = () => {
                console.log('ZXing library loaded successfully');
                resolve(window.ZXing);
            };
            script.onerror = () => {
                console.error('Failed to load ZXing library');
                reject(new Error('Failed to load ZXing library'));
            };
            document.head.appendChild(script);
        });
    }

    // Modern barcode scanner using ZXing
    async function setupModernScanner() {
        const videoElement = document.getElementById('scannerVideoElement');
        const scannerLoading = document.getElementById('scannerLoading');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        
        console.log('Setting up modern camera scanner...');
        
        try {
            // Load ZXing library
            const ZXing = await loadZXingLibrary();
            
            // Show loading
            scannerLoading.classList.remove('hidden');
            scannerPlaceholder.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            
            // Request camera access
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: "environment"
                }
            });
            
            console.log('Camera access granted for modern scanner!');
            
            // Set the video stream
            videoElement.srcObject = stream;
            await videoElement.play();
            
            // Hide loading and show video
            scannerLoading.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            
            // Initialize ZXing reader
            const codeReader = new ZXing.BrowserMultiFormatReader();
            
                    // Configure ZXing for better barcode detection
        const hints = new Map();
        hints.set(ZXing.DecodeHintType.TRY_HARDER, true);
        hints.set(ZXing.DecodeHintType.PURE_BARCODE, true);
        hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, [
            ZXing.BarcodeFormat.CODE_128,
            ZXing.BarcodeFormat.CODE_39,
            ZXing.BarcodeFormat.EAN_13,
            ZXing.BarcodeFormat.EAN_8,
            ZXing.BarcodeFormat.UPC_A,
            ZXing.BarcodeFormat.UPC_E,
            ZXing.BarcodeFormat.QR_CODE,
            ZXing.BarcodeFormat.CODABAR,
            ZXing.BarcodeFormat.ITF,
            ZXing.BarcodeFormat.PDF_417,
            ZXing.BarcodeFormat.AZTEC
        ]);
        
        // Set additional hints for better detection
        hints.set(ZXing.DecodeHintType.NEED_RESULT_POINT_CALLBACK, true);
        hints.set(ZXing.DecodeHintType.CHARACTER_SET, 'UTF-8');
        
        // Start continuous scanning with better configuration
        await codeReader.decodeFromVideoDevice(null, videoElement, (result, error) => {
            if (result) {
                console.log('🎉 Barcode detected successfully!');
                console.log('📋 Barcode text:', result.text);
                console.log('📊 Barcode format:', result.format);
                console.log('📍 Barcode bounds:', result.resultPoints);
                
                // Validate barcode format
                const barcodeText = result.text.trim();
                if (barcodeText && barcodeText.length > 0) {
                    console.log('✅ Valid barcode detected, processing...');
                    stopModernScanner();
                    processScannedBarcode(barcodeText);
                } else {
                    console.log('❌ Invalid barcode detected, ignoring...');
                }
            }
            if (error) {
                if (error.name !== 'NotFoundException') {
                    console.log('⚠️ Scanning error:', error.name, error.message);
                }
            }
        });
            
            // Store reference for stopping
            window.currentCodeReader = codeReader;
            
            showNotification('Scanner modern siap. Arahkan kamera ke barcode.', 'success');
        
        // Add test barcode button for debugging
        addTestBarcodeButton();
            
        } catch (error) {
            console.error('Modern scanner setup error:', error);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            
            if (error.name === 'NotAllowedError') {
                showNotification('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
            } else if (error.name === 'NotFoundError') {
                showNotification('Tidak ada kamera yang ditemukan.', 'error');
            } else {
                showNotification('Gagal mengakses kamera: ' + error.message, 'error');
            }
        }
    }

    function stopModernScanner() {
        if (window.currentCodeReader) {
            try {
                window.currentCodeReader.reset();
            } catch (error) {
                console.error('Error stopping modern scanner:', error);
            }
        }
    }

    // Alternative simple barcode scanner
    function setupSimpleScanner() {
        const videoElement = document.getElementById('scannerVideoElement');
        const scannerLoading = document.getElementById('scannerLoading');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        
        console.log('Setting up simple camera scanner...');
        
        // Show loading
        scannerLoading.classList.remove('hidden');
        scannerPlaceholder.classList.add('hidden');
        scannerVideo.classList.remove('hidden');
        
        // Request camera access
        navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "environment"
            }
        })
        .then(stream => {
            console.log('Camera access granted for simple scanner!');
            
            // Set the video stream
            videoElement.srcObject = stream;
            videoElement.play();
            
            // Hide loading and show video
            scannerLoading.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            
            // Add manual scan button
            const manualScanBtn = document.createElement('button');
            manualScanBtn.textContent = 'Scan Manual';
            manualScanBtn.className = 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2';
            manualScanBtn.onclick = () => captureAndProcess();
            
            const scannerControls = document.getElementById('scannerControls');
            if (scannerControls) {
                scannerControls.appendChild(manualScanBtn);
            }
            
            showNotification('Kamera siap. Gunakan tombol "Scan Manual" untuk mengambil gambar dan mendeteksi barcode.', 'info');
        })
        .catch(error => {
            console.error('Camera access error:', error);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            
            if (error.name === 'NotAllowedError') {
                showNotification('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
            } else if (error.name === 'NotFoundError') {
                showNotification('Tidak ada kamera yang ditemukan.', 'error');
            } else {
                showNotification('Gagal mengakses kamera: ' + error.message, 'error');
            }
        });
    }

    function captureAndProcess() {
        const videoElement = document.getElementById('scannerVideoElement');
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        
        // Set canvas size to video size
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;
        
        // Draw video frame to canvas
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
        
        // Get image data
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        
        // For now, we'll simulate barcode detection
        // In a real implementation, you would use a barcode detection library
        console.log('Captured image for barcode detection');
        
        // Simulate barcode detection (replace with actual barcode detection)
        const simulatedBarcode = prompt('Masukkan kode barcode secara manual:');
        if (simulatedBarcode) {
            processScannedBarcode(simulatedBarcode);
        }
    }

    // Simple and reliable camera scanner
    function setupReliableScanner() {
        const videoElement = document.getElementById('scannerVideoElement');
        const scannerLoading = document.getElementById('scannerLoading');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        
        console.log('Setting up reliable camera scanner...');
        
        // Show loading
        scannerLoading.classList.remove('hidden');
        scannerPlaceholder.classList.add('hidden');
        scannerVideo.classList.remove('hidden');
        
        // Check if getUserMedia is supported
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('getUserMedia not supported');
            showNotification('Browser tidak mendukung akses kamera', 'error');
            setupManualInput();
            return;
        }
        
        // Request camera access with multiple fallback options
        const constraints = {
            video: {
                width: { ideal: 1280, min: 640 },
                height: { ideal: 720, min: 480 },
                facingMode: "environment"
            }
        };
        
        navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            console.log('Camera access granted!');
            
            // Set the video stream
            videoElement.srcObject = stream;
            videoElement.play();
            
            // Hide loading and show video
            scannerLoading.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            
            // Add manual input option
            addManualInputOption();
            
            showNotification('Kamera siap. Gunakan tombol "Input Manual" untuk memasukkan kode barcode.', 'success');
            
            // Start periodic capture for barcode detection
            startPeriodicCapture();
        })
        .catch(error => {
            console.error('Camera access error:', error);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            
            if (error.name === 'NotAllowedError') {
                showNotification('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
            } else if (error.name === 'NotFoundError') {
                showNotification('Tidak ada kamera yang ditemukan.', 'error');
            } else {
                showNotification('Gagal mengakses kamera: ' + error.message, 'error');
            }
            
            // Fallback to manual input
            setupManualInput();
        });
    }

    function addManualInputOption() {
        const scannerControls = document.getElementById('scannerControls');
        if (scannerControls) {
            // Remove existing manual input button if any
            const existingBtn = scannerControls.querySelector('#manualInputBtn');
            if (existingBtn) {
                existingBtn.remove();
            }
            
            // Add manual input button
            const manualInputBtn = document.createElement('button');
            manualInputBtn.id = 'manualInputBtn';
            manualInputBtn.textContent = 'Input Manual';
            manualInputBtn.className = 'px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold';
            manualInputBtn.onclick = () => showManualInputDialog();
            
            scannerControls.appendChild(manualInputBtn);
        }
    }

    function showManualInputDialog() {
        const barcodeInput = prompt('Masukkan kode barcode:');
        if (barcodeInput && barcodeInput.trim()) {
            processScannedBarcode(barcodeInput.trim());
        }
    }

    function setupManualInput() {
        console.log('Setting up manual input fallback...');
        
        const scannerContainer = document.getElementById('scannerContainer');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerLoading = document.getElementById('scannerLoading');
        
        // Hide video and show manual input
        scannerVideo.classList.add('hidden');
        scannerLoading.classList.add('hidden');
        scannerPlaceholder.classList.remove('hidden');
        
        // Update placeholder content
        scannerPlaceholder.innerHTML = `
            <div class="text-center">
                <i class="fas fa-keyboard text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500 mb-4">Kamera tidak tersedia</p>
                <button type="button" onclick="showManualInputDialog()" 
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold">
                    Input Manual
                </button>
            </div>
        `;
        
        showNotification('Gunakan tombol "Input Manual" untuk memasukkan kode barcode.', 'info');
    }

    function startPeriodicCapture() {
        const videoElement = document.getElementById('scannerVideoElement');
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        
        // Set up periodic capture for potential barcode detection
        const captureInterval = setInterval(() => {
            if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
                try {
                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    
                    // Here you could add actual barcode detection logic
                    // For now, we'll just capture the image data
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    
                    // You could send this to a barcode detection service or use a library
                    // For now, we'll just log that we're capturing
                    console.log('Capturing frame for potential barcode detection');
                    
                } catch (error) {
                    console.log('Frame capture error (non-critical):', error);
                }
            }
        }, 1000); // Capture every second
        
        // Store interval for cleanup
        window.captureInterval = captureInterval;
    }

    function stopPeriodicCapture() {
        if (window.captureInterval) {
            clearInterval(window.captureInterval);
            window.captureInterval = null;
        }
    }

    // Try multiple scanner methods with reliable fallback
    async function initializeScanner() {
        console.log('Initializing scanner with reliable method...');
        
        try {
            // First try reliable scanner (most stable)
            console.log('Trying reliable scanner...');
            setupReliableScanner();
        } catch (error) {
            console.log('Reliable scanner failed, trying ZXing...');
            
            try {
                await setupModernScanner();
            } catch (zxingError) {
                console.log('ZXing failed, trying QuaggaJS...');
                
                // Fallback to QuaggaJS
                if (typeof Quagga !== 'undefined') {
                    try {
                        setupQuagga();
                    } catch (quaggaError) {
                        console.log('All scanners failed, using manual input...');
                        setupManualInput();
                    }
                } else {
                    console.log('QuaggaJS not available, using manual input...');
                    setupManualInput();
                }
            }
        }
    }

    function setupQuagga() {
        const scannerContainer = document.getElementById('scannerContainer');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerLoading = document.getElementById('scannerLoading');
        const videoElement = document.getElementById('scannerVideoElement');
        
        console.log('Setting up camera scanner...');
        console.log('Video element:', videoElement);
        
        // Show loading
        scannerLoading.classList.remove('hidden');
        scannerPlaceholder.classList.add('hidden');
        scannerVideo.classList.remove('hidden');
        
        // First, try to access camera directly
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('getUserMedia not supported');
            showNotification('Browser tidak mendukung akses kamera', 'error');
            return;
        }
        
        // Request camera access
        navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "environment"
            }
        })
        .then(stream => {
            console.log('Camera access granted!');
            
            // Set the video stream to our video element
            videoElement.srcObject = stream;
            videoElement.play();
            
            // Hide loading and show video
            scannerLoading.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            
            // Now initialize Quagga with the working stream
            initializeQuaggaWithStream(stream);
        })
        .catch(error => {
            console.error('Camera access error:', error);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            
            if (error.name === 'NotAllowedError') {
                showNotification('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
            } else if (error.name === 'NotFoundError') {
                showNotification('Tidak ada kamera yang ditemukan.', 'error');
            } else {
                showNotification('Gagal mengakses kamera: ' + error.message, 'error');
            }
        });
    }

    function initializeQuaggaWithStream(stream) {
        console.log('Initializing Quagga with existing stream...');
        
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.getElementById('scannerVideoElement'),
                constraints: {
                    width: { min: 640 },
                    height: { min: 480 },
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader"
                ]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error('Quagga initialization error:', err);
                showNotification('Gagal menginisialisasi scanner barcode', 'error');
                return;
            }
            
            console.log('Quagga initialized successfully');
            quaggaInitialized = true;
            
            // Start scanning automatically
            startScanning();
        });
    }

    function startScanning() {
        if (!quaggaInitialized) {
            showNotification('Scanner belum siap. Silakan tunggu.', 'warning');
            return;
        }
        
        try {
            Quagga.start();
            document.getElementById('startScanBtn').classList.add('hidden');
            document.getElementById('stopScanBtn').classList.remove('hidden');
            document.getElementById('scannerStatus').textContent = 'Scanning...';
            
            // Listen for scan events
            Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                console.log('Barcode detected:', code);
                
                // Stop scanning
                stopScanning();
                
                // Process the scanned barcode
                processScannedBarcode(code);
            });
            
            // Listen for processing events
            Quagga.onProcessed(function(result) {
                try {
                    const drawingCanvas = Quagga.canvas.ctx.overlay;
                    if (drawingCanvas && drawingCanvas.getContext) {
                        const drawingCtx = drawingCanvas.getContext('2d');
                        
                        if (result) {
                            if (result.boxes) {
                                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.style.width), parseInt(drawingCanvas.style.height));
                                result.boxes.filter(function(box) {
                                    return box !== result.box;
                                }).forEach(function(box) {
                                    Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                                });
                            }
                            
                            if (result.box) {
                                Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
                            }
                            
                            if (result.codeResult && result.codeResult.code) {
                                Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                            }
                        }
                    }
                } catch (error) {
                    console.log('Canvas processing error (non-critical):', error);
                }
            });
            
        } catch (error) {
            console.error('Error starting scanner:', error);
            showNotification('Gagal memulai scanner. Silakan coba lagi.', 'error');
        }
    }

    function stopScanning() {
        try {
            Quagga.stop();
            document.getElementById('startScanBtn').classList.remove('hidden');
            document.getElementById('stopScanBtn').classList.add('hidden');
            document.getElementById('scannerStatus').textContent = 'Scanner dihentikan';
        } catch (error) {
            console.error('Error stopping scanner:', error);
        }
    }

    function closeScanner() {
        try {
            // Stop Quagga if running
            if (typeof Quagga !== 'undefined') {
                Quagga.stop();
            }
            
            // Stop modern scanner if running
            if (window.currentCodeReader) {
                window.currentCodeReader.reset();
            }
            
            // Stop periodic capture
            stopPeriodicCapture();
            
            // Stop video stream
            const videoElement = document.getElementById('scannerVideoElement');
            if (videoElement.srcObject) {
                const tracks = videoElement.srcObject.getTracks();
                tracks.forEach(track => track.stop());
                videoElement.srcObject = null;
            }
            
        } catch (error) {
            console.error('Error stopping scanner:', error);
        }
        
        scannerModal.classList.add('hidden');
        document.getElementById('startScanBtn').classList.remove('hidden');
        document.getElementById('stopScanBtn').classList.add('hidden');
        document.getElementById('scannerStatus').textContent = 'Siap untuk scan';
        
        // Reset scanner container
        const scannerContainer = document.getElementById('scannerContainer');
        const scannerPlaceholder = document.getElementById('scannerPlaceholder');
        const scannerVideo = document.getElementById('scannerVideo');
        const scannerLoading = document.getElementById('scannerLoading');
        
        scannerLoading.classList.add('hidden');
        scannerPlaceholder.classList.remove('hidden');
        scannerVideo.classList.add('hidden');
        
        // Remove manual input button if exists
        const manualInputBtn = document.getElementById('manualInputBtn');
        if (manualInputBtn) {
            manualInputBtn.remove();
        }
    }

    // Function to add test barcode button for debugging
    function addTestBarcodeButton() {
        const scannerControls = document.getElementById('scannerControls');
        if (scannerControls) {
            const testBtn = document.createElement('button');
            testBtn.textContent = 'Test Barcode';
            testBtn.className = 'bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2';
            testBtn.onclick = () => {
                const testBarcode = prompt('Masukkan kode barcode untuk testing:');
                if (testBarcode) {
                    console.log('🧪 Testing with barcode:', testBarcode);
                    processScannedBarcode(testBarcode);
                }
            };
            scannerControls.appendChild(testBtn);
            
            // Add debug info button
            const debugBtn = document.createElement('button');
            debugBtn.textContent = 'Debug Info';
            debugBtn.className = 'bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded ml-2';
            debugBtn.onclick = () => {
                console.log('🔍 Debug Info:');
                console.log('📱 User Agent:', navigator.userAgent);
                console.log('📹 Media Devices:', navigator.mediaDevices);
                console.log('🎥 Video Element:', document.getElementById('scannerVideoElement'));
                console.log('🔧 ZXing Library:', typeof window.ZXing);
                console.log('📊 Current Scanner:', window.currentCodeReader);
            };
            scannerControls.appendChild(debugBtn);
        }
    }

    function processScannedBarcode(barcode) {
        console.log('🔍 Processing scanned barcode:', barcode);
        
        // Clean the barcode text (remove any whitespace or special characters)
        const cleanBarcode = barcode.trim();
        console.log('🧹 Cleaned barcode:', cleanBarcode);
        
        // Show loading in status
        document.getElementById('scannerStatus').textContent = 'Memproses barcode...';
        
        // Set the barcode value to the input field
        barcodeInput.value = cleanBarcode;
        
        // Close scanner
        closeScanner();
        
        // Show success notification
        showNotification('Barcode berhasil di-scan: ' + cleanBarcode, 'success');
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        if (type === 'success') {
            notification.className += ' bg-green-500 text-white';
        } else if (type === 'error') {
            notification.className += ' bg-red-500 text-white';
        } else {
            notification.className += ' bg-blue-500 text-white';
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
});
</script>
@endsection 