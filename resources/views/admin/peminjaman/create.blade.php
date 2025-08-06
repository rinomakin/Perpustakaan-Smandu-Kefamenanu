@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tambah Peminjaman</h1>
            <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Form Peminjaman</h3>
            </div>
            
            <form action="{{ route('peminjaman.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Anggota dengan Search dan Barcode Scanner -->
                    <div>
                        <label for="anggota_search" class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 relative">
                                <input type="text" id="anggota_search" 
                                       placeholder="Cari anggota (ketik nama atau nomor anggota)..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <input type="hidden" name="anggota_id" id="anggota_id" required>
                                
                                <!-- Dropdown hasil pencarian -->
                                <div id="anggotaDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                    <!-- Hasil pencarian akan muncul di sini -->
                                </div>
                            </div>
                            <button type="button" id="scanAnggotaBtn" 
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold">
                                <i class="fas fa-barcode"></i>
                            </button>
                        </div>
                        <div id="anggotaInfo" class="mt-2 p-3 bg-blue-50 rounded-lg hidden">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 id="anggotaNama" class="font-semibold text-gray-900"></h4>
                                    <p id="anggotaNomor" class="text-sm text-gray-600"></p>
                                    <p id="anggotaKelas" class="text-xs text-gray-500"></p>
                                </div>
                            </div>
                        </div>
                        @error('anggota_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" 
                               value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                                         <!-- Jam Peminjaman -->
                     <div>
                         <label for="jam_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Jam Peminjaman</label>
                         <input type="time" name="jam_peminjaman" id="jam_peminjaman" 
                                value="{{ old('jam_peminjaman', date('H:i')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         @error('jam_peminjaman')
                         <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                     <!-- Tanggal Harus Kembali -->
                     <div>
                         <label for="tanggal_harus_kembali" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Harus Kembali</label>
                         <input type="date" name="tanggal_harus_kembali" id="tanggal_harus_kembali" 
                                value="{{ old('tanggal_harus_kembali') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         @error('tanggal_harus_kembali')
                         <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                     <!-- Jam Pengembalian -->
                     <div>
                         <label for="jam_kembali" class="block text-sm font-medium text-gray-700 mb-2">Jam Pengembalian</label>
                         <input type="time" name="jam_kembali" id="jam_kembali" 
                                value="{{ old('jam_kembali') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         <p class="text-xs text-gray-500 mt-1">Jam pengembalian tidak boleh sama dengan jam peminjaman</p>
                         @error('jam_kembali')
                         <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buku Selection dengan Barcode Scanner -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700">Pilih Buku</label>
                        <button type="button" id="scanBukuBtn" 
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold text-sm">
                            <i class="fas fa-barcode mr-2"></i>Scan Buku
                        </button>
                    </div>
                    

                    
                    <!-- Selected Books Display -->
                    <div id="selectedBooks" class="mb-4 space-y-2">
                        <!-- Selected books will be displayed here -->
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                        @foreach($buku as $book)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 book-item" data-book-id="{{ $book->id }}">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" name="buku_ids[]" value="{{ $book->id }}" 
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded book-checkbox"
                                       {{ in_array($book->id, old('buku_ids', [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">{{ $book->judul_buku }}</h4>
                                    <p class="text-xs text-gray-500">{{ $book->penulis->nama_penulis ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Stok: {{ $book->stok_tersedia }}</p>
                                    <p class="text-xs text-gray-500">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Barcode: {{ $book->barcode_buku ?? 'N/A' }}</p>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('buku_ids')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('peminjaman.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-save mr-2"></i>Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center">
    <i class="fas fa-exclamation-circle mr-2"></i>
    {{ session('error') }}
</div>
@endif

<!-- Barcode Scanner Modal -->
<div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-white" id="scannerTitle">Scan Barcode</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 mb-4" id="scannerDescription">Arahkan kamera ke barcode untuk scan</p>
                    <div id="scannerContainer" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <div id="scannerPlaceholder" class="text-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 mb-2">Kamera akan aktif di sini</p>
                            <p class="text-xs text-gray-400">Klik "Mulai Scan" untuk mengaktifkan kamera</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="button" id="closeScannerBtn" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Tutup
                    </button>
                    <button type="button" id="startScanBtn" 
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-camera mr-2"></i>Mulai Scan
                    </button>
                    <button type="button" id="manualInputBtn" 
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Input Manual
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Include Barcode Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>

<script>
// Auto hide messages after 5 seconds
setTimeout(function() {
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    if (successMessage) {
        successMessage.style.opacity = '0';
        setTimeout(() => successMessage.remove(), 500);
    }
    
    if (errorMessage) {
        errorMessage.style.opacity = '0';
        setTimeout(() => errorMessage.remove(), 500);
    }
}, 5000);

// Barcode Scanner Functionality
let currentScanType = 'anggota'; // 'anggota' or 'buku'
let selectedBooks = [];
let codeReader = null;
let videoStream = null;

// Initialize scanner buttons
document.addEventListener('DOMContentLoaded', function() {
    const scanAnggotaBtn = document.getElementById('scanAnggotaBtn');
    const scanBukuBtn = document.getElementById('scanBukuBtn');
    const scannerModal = document.getElementById('scannerModal');
    const closeScannerBtn = document.getElementById('closeScannerBtn');
    const manualInputBtn = document.getElementById('manualInputBtn');
    const startScanBtn = document.getElementById('startScanBtn');
    const scannerTitle = document.getElementById('scannerTitle');
    const scannerDescription = document.getElementById('scannerDescription');
    
    // Anggota Search Functionality
    const anggotaSearch = document.getElementById('anggota_search');
    const anggotaDropdown = document.getElementById('anggotaDropdown');
    const anggotaIdInput = document.getElementById('anggota_id');
    
    // Search anggota functionality
    anggotaSearch.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            anggotaDropdown.classList.add('hidden');
            return;
        }
        
        // Search anggota via AJAX
        fetch('{{ route("peminjaman.search-anggota") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                showAnggotaDropdown(data.data);
            } else {
                anggotaDropdown.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error searching anggota:', error);
        });
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!anggotaSearch.contains(e.target) && !anggotaDropdown.contains(e.target)) {
            anggotaDropdown.classList.add('hidden');
        }
    });
    
    // Scan Anggota Button
    scanAnggotaBtn.addEventListener('click', function() {
        currentScanType = 'anggota';
        scannerTitle.textContent = 'Scan Barcode Anggota';
        scannerDescription.textContent = 'Arahkan kamera ke barcode kartu anggota';
        scannerModal.classList.remove('hidden');
        resetScanner();
    });
    
    // Scan Buku Button
    scanBukuBtn.addEventListener('click', function() {
        currentScanType = 'buku';
        scannerTitle.textContent = 'Scan Barcode Buku';
        scannerDescription.textContent = 'Arahkan kamera ke barcode buku';
        scannerModal.classList.remove('hidden');
        resetScanner();
    });
    
    // Close Scanner
    closeScannerBtn.addEventListener('click', function() {
        scannerModal.classList.add('hidden');
        stopScanner();
    });
    
    // Start Scan Button
    startScanBtn.addEventListener('click', function() {
        startScanner();
    });
    
    // Manual Input
    manualInputBtn.addEventListener('click', function() {
        let promptText = 'Masukkan kode barcode secara manual:';
        
        if (currentScanType === 'anggota') {
            promptText = 'Masukkan barcode anggota (contoh: ANG001, ANG002, dll):';
        } else if (currentScanType === 'buku') {
            promptText = 'Masukkan barcode buku (contoh: BK001, BK002, dll):';
        }
        
        const barcode = prompt(promptText);
        if (barcode) {
            handleScannedCode(barcode);
        }
    });
    
    // Handle checkbox changes for books
    const bookCheckboxes = document.querySelectorAll('.book-checkbox');
    bookCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedBooks();
        });
    });
});

// Show Anggota Dropdown
function showAnggotaDropdown(anggotaList) {
    const dropdown = document.getElementById('anggotaDropdown');
    dropdown.innerHTML = '';
    
    anggotaList.forEach(anggota => {
        const item = document.createElement('div');
        item.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
        item.innerHTML = `
            <div class="font-semibold text-gray-900">${anggota.nama_lengkap}</div>
            <div class="text-sm text-gray-600">${anggota.nomor_anggota}</div>
            <div class="text-xs text-gray-500">${anggota.barcode_anggota || 'N/A'}</div>
        `;
        
        item.addEventListener('click', function() {
            selectAnggota(anggota);
        });
        
        dropdown.appendChild(item);
    });
    
    dropdown.classList.remove('hidden');
}

// Select Anggota
function selectAnggota(anggota) {
    document.getElementById('anggota_search').value = `${anggota.nama_lengkap} - ${anggota.nomor_anggota}`;
    document.getElementById('anggota_id').value = anggota.id;
    document.getElementById('anggotaDropdown').classList.add('hidden');
    showAnggotaInfo(anggota.id, anggota);
}

// Reset Scanner
function resetScanner() {
    const startBtn = document.getElementById('startScanBtn');
    const container = document.getElementById('scannerContainer');
    
    if (container) {
        // Clear any existing scanner content
        container.innerHTML = `
            <div id="scannerPlaceholder" class="text-center">
                <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500 mb-2">Kamera akan aktif di sini</p>
                <p class="text-xs text-gray-400">Klik "Mulai Scan" untuk mengaktifkan kamera</p>
            </div>
        `;
    }
    
    if (startBtn) {
        startBtn.innerHTML = '<i class="fas fa-camera mr-2"></i>Mulai Scan';
    }
    
    stopScanner();
}

// Start Scanner
async function startScanner() {
    try {
        const container = document.getElementById('scannerContainer');
        const startBtn = document.getElementById('startScanBtn');
        
        if (!container || !startBtn) {
            throw new Error('Scanner elements not found');
        }
        
        // Clear container and create video element
        container.innerHTML = `
            <div class="relative w-full h-full">
                <video id="scannerVideo" class="w-full h-full object-cover rounded-lg" autoplay playsinline></video>
                <div id="scannerOverlay" class="absolute inset-0 flex items-center justify-center">
                    <div class="border-2 border-white rounded-lg p-2">
                        <div class="w-48 h-48 border-2 border-green-400 rounded-lg"></div>
                    </div>
                </div>
            </div>
        `;
        
        startBtn.innerHTML = '<i class="fas fa-stop mr-2"></i>Stop Scan';
        
        // Get camera stream
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            } 
        });
        
        const video = document.getElementById('scannerVideo');
        video.srcObject = stream;
        videoStream = stream;
        
        // Initialize Quagga scanner
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: video,
                constraints: {
                    width: 640,
                    height: 480,
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
            }
        }, function(err) {
            if (err) {
                console.log('Quagga init error:', err);
                return;
            }
            console.log("Quagga initialization succeeded");
            Quagga.start();
        });
        
        // Listen for scan results
        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log('Barcode detected:', code);
            showNotification('Barcode terdeteksi: ' + code, 'success');
            handleScannedCode(code);
            stopScanner();
        });
        
        // Store scanner reference
        codeReader = Quagga;
        
        // Add visual feedback
        showNotification('Kamera aktif. Arahkan ke barcode...', 'info');
        
    } catch (error) {
        console.error('Error starting scanner:', error);
        
        // Reset to original state
        resetScanner();
        
        // Show user-friendly error
        if (error.name === 'NotAllowedError') {
            alert('Izin kamera ditolak. Silakan izinkan akses kamera dan coba lagi.');
        } else if (error.name === 'NotFoundError') {
            alert('Kamera tidak ditemukan. Pastikan perangkat memiliki kamera.');
        } else {
            alert('Tidak dapat mengaktifkan scanner. Gunakan input manual sebagai alternatif.');
        }
    }
}



// Stop Scanner
function stopScanner() {
    // Stop Quagga scanner
    if (codeReader && typeof codeReader.stop === 'function') {
        try {
            codeReader.stop();
        } catch (e) {
            console.log('Quagga stop error:', e);
        }
        codeReader = null;
    }
    
    // Stop HTML5-QRCode scanner (fallback)
    if (codeReader && typeof codeReader.clear === 'function') {
        try {
            codeReader.clear();
        } catch (e) {
            console.log('HTML5-QRCode clear error:', e);
        }
        codeReader = null;
    }
    
    // Reset container to original state
    const container = document.getElementById('scannerContainer');
    const startBtn = document.getElementById('startScanBtn');
    
    if (container) {
        container.innerHTML = `
            <div id="scannerPlaceholder" class="text-center">
                <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500 mb-2">Kamera akan aktif di sini</p>
                <p class="text-xs text-gray-400">Klik "Mulai Scan" untuk mengaktifkan kamera</p>
            </div>
        `;
    }
    
    if (startBtn) {
        startBtn.innerHTML = '<i class="fas fa-camera mr-2"></i>Mulai Scan';
    }
    
    // Stop video stream if exists
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
}

// Handle Scanned Code
function handleScannedCode(barcode) {
    console.log('Scanned barcode:', barcode);
    
    if (currentScanType === 'anggota') {
        // Call API to scan anggota barcode
        fetch('{{ route("peminjaman.scan-anggota") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const anggotaSelect = document.getElementById('anggota_id');
                anggotaSelect.value = data.data.id;
                anggotaSelect.dispatchEvent(new Event('change'));
                showAnggotaInfo(data.data.id, data.data);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat scan barcode anggota');
        });
    } else if (currentScanType === 'buku') {
        // Call API to scan buku barcode
        fetch('{{ route("peminjaman.scan-buku") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Find and check the book checkbox
                const bookItems = document.querySelectorAll('.book-item');
                bookItems.forEach(item => {
                    const bookId = item.dataset.bookId;
                    if (bookId == data.data.id) {
                        const checkbox = item.querySelector('.book-checkbox');
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                        
                        // Show success message
                        showNotification('Buku berhasil ditambahkan: ' + data.data.judul_buku, 'success');
                        return;
                    }
                });
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat scan barcode buku');
        });
    }
    
    // Close scanner modal
    document.getElementById('scannerModal').classList.add('hidden');
    stopScanner();
}

// Show Anggota Info
function showAnggotaInfo(anggotaId, anggotaData = null) {
    const anggotaInfo = document.getElementById('anggotaInfo');
    const anggotaNama = document.getElementById('anggotaNama');
    const anggotaNomor = document.getElementById('anggotaNomor');
    const anggotaKelas = document.getElementById('anggotaKelas');
    
    if (anggotaData) {
        // Use data from API response
        anggotaNama.textContent = anggotaData.nama_lengkap;
        anggotaNomor.textContent = anggotaData.nomor_anggota;
        anggotaKelas.textContent = 'Kelas: ' + anggotaData.kelas;
        
        anggotaInfo.classList.remove('hidden');
        
        // Show success notification
        showNotification('Anggota berhasil ditemukan: ' + anggotaData.nama_lengkap, 'success');
    } else {
        // Fallback to select option data
        const anggotaSelect = document.getElementById('anggota_id');
        const selectedOption = anggotaSelect.options[anggotaSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const text = selectedOption.textContent;
            const parts = text.split(' - ');
            
            anggotaNama.textContent = parts[0] || 'N/A';
            anggotaNomor.textContent = parts[1] || 'N/A';
            anggotaKelas.textContent = 'Kelas: ' + (parts[2] || 'N/A');
            
            anggotaInfo.classList.remove('hidden');
        }
    }
}

// Show Notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 flex items-center ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                 type === 'error' ? 'fas fa-exclamation-circle' : 
                 'fas fa-info-circle';
    
    notification.innerHTML = `
        <i class="${icon} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Update Selected Books Display
function updateSelectedBooks() {
    const selectedBooksContainer = document.getElementById('selectedBooks');
    const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
    
    selectedBooksContainer.innerHTML = '';
    
    checkedBoxes.forEach(checkbox => {
        const bookItem = checkbox.closest('.book-item');
        const bookTitle = bookItem.querySelector('h4').textContent;
        const bookId = checkbox.value;
        
        const bookCard = document.createElement('div');
        bookCard.className = 'flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg';
        bookCard.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas fa-book text-green-600"></i>
                <div>
                    <h5 class="font-semibold text-sm text-gray-900">${bookTitle}</h5>
                    <p class="text-xs text-gray-600">ID: ${bookId}</p>
                </div>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeBook('${bookId}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        selectedBooksContainer.appendChild(bookCard);
    });
}

// Remove Book from Selection
function removeBook(bookId) {
    const checkbox = document.querySelector(`input[value="${bookId}"]`);
    if (checkbox) {
        checkbox.checked = false;
        checkbox.dispatchEvent(new Event('change'));
    }
}

// Handle anggota select change
document.getElementById('anggota_id').addEventListener('change', function() {
    if (this.value) {
        showAnggotaInfo(this.value);
    } else {
        document.getElementById('anggotaInfo').classList.add('hidden');
    }
});

// Validate jam pengembalian
document.getElementById('jam_kembali').addEventListener('change', function() {
    const jamPeminjaman = document.getElementById('jam_peminjaman').value;
    const jamKembali = this.value;
    
    if (jamPeminjaman && jamKembali && jamPeminjaman === jamKembali) {
        alert('Jam pengembalian tidak boleh sama dengan jam peminjaman!');
        this.value = '';
        this.focus();
    }
});

// Auto-set jam pengembalian to next hour if same as peminjaman
document.getElementById('jam_peminjaman').addEventListener('change', function() {
    const jamPeminjaman = this.value;
    const jamKembali = document.getElementById('jam_kembali').value;
    
    if (jamPeminjaman && jamKembali && jamPeminjaman === jamKembali) {
        // Calculate next hour
        const [hours, minutes] = jamPeminjaman.split(':');
        const nextHour = (parseInt(hours) + 1) % 24;
        const nextHourStr = nextHour.toString().padStart(2, '0');
        document.getElementById('jam_kembali').value = `${nextHourStr}:${minutes}`;
    }
});
</script>
@endsection 