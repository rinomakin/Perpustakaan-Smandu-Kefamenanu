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
                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Scan Barcode</h3>
                                        <button type="button" id="closeScannerBtn" class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="scannerContainer" class="w-full h-64 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-qrcode text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-gray-600">Scanner akan muncul di sini</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="button" id="startScanBtn" 
                                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                            <i class="fas fa-play mr-2"></i>
                                            Mulai Scan
                                        </button>
                                        <button type="button" id="stopScanBtn" 
                                                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 hidden">
                                            <i class="fas fa-stop mr-2"></i>
                                            Stop Scan
                                        </button>
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

    // Scan barcode button
    scanBarcodeBtn.addEventListener('click', function() {
        scannerModal.classList.remove('hidden');
    });

    // Close scanner modal
    closeScannerBtn.addEventListener('click', function() {
        scannerModal.classList.add('hidden');
        stopScanning();
    });

    // Start scanning
    startScanBtn.addEventListener('click', function() {
        startScanning();
    });

    // Stop scanning
    stopScanBtn.addEventListener('click', function() {
        stopScanning();
    });

    // Close modal when clicking outside
    scannerModal.addEventListener('click', function(e) {
        if (e.target === scannerModal) {
            scannerModal.classList.add('hidden');
            stopScanning();
        }
    });

    function startScanning() {
        startScanBtn.classList.add('hidden');
        stopScanBtn.classList.remove('hidden');
        
        // Simulate barcode scanning (in real implementation, this would use a barcode scanner library)
        scannerContainer.innerHTML = `
            <div class="text-center">
                <div class="animate-pulse">
                    <i class="fas fa-qrcode text-4xl text-blue-500 mb-2"></i>
                    <p class="text-blue-600 font-medium">Scanning...</p>
                    <p class="text-sm text-gray-500 mt-1">Arahkan scanner ke barcode</p>
                </div>
            </div>
        `;

        // Simulate scan result after 3 seconds
        setTimeout(() => {
            const scannedBarcode = 'BK' + Date.now().toString().slice(-8);
            barcodeInput.value = scannedBarcode;
            scannerModal.classList.add('hidden');
            stopScanning();
            showNotification('Barcode berhasil di-scan: ' + scannedBarcode, 'success');
        }, 3000);
    }

    function stopScanning() {
        startScanBtn.classList.remove('hidden');
        stopScanBtn.classList.add('hidden');
        scannerContainer.innerHTML = `
            <div class="text-center">
                <i class="fas fa-qrcode text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Scanner akan muncul di sini</p>
            </div>
        `;
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