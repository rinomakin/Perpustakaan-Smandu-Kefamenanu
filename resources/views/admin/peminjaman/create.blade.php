@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Include HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
/* Custom styles untuk dropdown */
.dropdown-item {
    transition: all 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: #eff6ff;
    transform: translateX(2px);
}

.dropdown-item.selected {
    background-color: #dbeafe;
    border-left: 3px solid #3b82f6;
}

/* Loading spinner */
.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Notification animations */
.notification-enter {
    transform: translateX(100%);
    opacity: 0;
}

.notification-enter-active {
    transform: translateX(0);
    opacity: 1;
    transition: all 0.3s ease-out;
}

/* Focus styles */
.search-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Hover effects */
.book-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
</style>

<div class="min-h-screen bg-gradient-to-br  py-8">
    <div class=" px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white">Form Peminjaman Buku</h3>
                    <div class="text-white text-sm">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="realTimeClock">--:--:--</span>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('peminjaman.store') }}" method="POST" class="p-6" onsubmit="return validateForm()">
                @csrf
                
                <!-- Informasi Peminjaman -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Anggota dengan Auto-Deteksi -->
                    <div class="md:col-span-2">
                        <label for="anggota_search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Anggota
                        </label>
                        <div class="flex space-x-2">
                            <div class="flex-1 relative">
                                <input type="text" id="anggota_search" 
                                       placeholder="Ketik nama anggota atau nomor anggota..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 search-input">
                                <input type="hidden" name="anggota_id" id="anggota_id" required>
                                
                                <!-- Dropdown hasil pencarian -->
                                <div id="anggotaDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                    <!-- Hasil pencarian akan muncul di sini -->
                                </div>
                            </div>
                            <button type="button" id="scanAnggotaBtn" 
                                    class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition-all duration-200">
                                <i class="fas fa-barcode"></i>
                            </button>
                        </div>
                        
                        <!-- Info Anggota yang Dipilih -->
                        <div id="anggotaInfo" class="mt-3 p-4 bg-blue-50 rounded-lg hidden">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 id="anggotaNama" class="font-semibold text-gray-900"></h4>
                                    <p id="anggotaNomor" class="text-sm text-gray-600"></p>
                                    <p id="anggotaKelas" class="text-xs text-gray-500"></p>
                                </div>
                                <button type="button" id="clearAnggota" class="text-red-500 hover:text-red-700 transition-colors duration-150">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @error('anggota_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Tanggal Pinjam
                        </label>
                        <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" 
                               value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" required readonly
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi dengan tanggal hari ini</p>
                        @error('tanggal_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Peminjaman -->
                    <div>
                        <label for="jam_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2"></i>Jam Pinjam
                        </label>
                        <input type="time" name="jam_peminjaman" id="jam_peminjaman" 
                               value="{{ old('jam_peminjaman', date('H:i')) }}" required readonly
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi dengan jam saat ini</p>
                        @error('jam_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Harus Kembali -->
                    <div>
                        <label for="tanggal_harus_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-2"></i>Tanggal Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_harus_kembali" id="tanggal_harus_kembali" 
                               value="{{ old('tanggal_harus_kembali', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <p class="text-xs text-gray-500 mt-1">Minimal sama dengan tanggal pinjam</p>
                        @error('tanggal_harus_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Pengembalian -->
                    <div>
                        <label for="jam_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2"></i>Jam Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam_kembali" id="jam_kembali" 
                               value="{{ old('jam_kembali') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <p class="text-xs text-gray-500 mt-1">Wajib diisi - jam pengembalian buku</p>
                        @error('jam_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Catatan
                        </label>
                        <textarea name="catatan" id="catatan" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                  placeholder="Catatan tambahan untuk peminjaman ini...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pilihan Buku dengan Auto-Deteksi -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-book mr-2"></i>Pilih Buku
                            </h3>
                            <p class="text-sm text-gray-600">Ketik judul buku atau scan barcode untuk menambah buku</p>
                        </div>
                        <button type="button" id="scanBukuBtn" 
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold text-sm transition-all duration-200">
                            <i class="fas fa-barcode mr-2"></i>Scan Buku
                        </button>
                    </div>

                    <!-- Search Buku -->
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" id="buku_search" 
                                   placeholder="Ketik judul buku, penulis, atau ISBN..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 search-input">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            
                            <!-- Dropdown hasil pencarian buku -->
                            <div id="bukuDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                <!-- Hasil pencarian buku akan muncul di sini -->
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Buku yang Dipilih -->
                    <div id="selectedBooks" class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Buku yang Dipilih (<span id="selectedCount">0</span>)</h4>
                            <div class="text-sm text-gray-600">
                                Total Buku: <span id="totalJumlah" class="font-semibold text-blue-600">0</span>
                            </div>
                        </div>
                        <div id="selectedBooksList" class="space-y-3">
                            <!-- Buku yang dipilih akan ditampilkan di sini -->
                        </div>
                    </div>

                    <!-- Hidden inputs untuk buku yang dipilih -->
                    <div id="hiddenBookInputs">
                        <!-- Input hidden akan ditambahkan di sini oleh JavaScript -->
                    </div>
                    
                    <!-- Fallback: manual input for testing -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300" id="manualBookInput" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Debug: Manual Book Input</h4>
                        <div class="flex space-x-2">
                            <input type="number" id="manual_book_id" placeholder="Book ID" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <button type="button" onclick="addManualBook()" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm">Add</button>
                            <button type="button" onclick="toggleManualInput()" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm">Toggle Debug</button>
                        </div>
                    </div>
                    
                    @error('buku_ids')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('peminjaman.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" id="submitBtn"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 notifications are handled by layout -->

<!-- Barcode Scanner Modal -->
<div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white" id="scannerTitle">Scan Barcode</h3>
                    <button type="button" id="closeScanner" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 mb-4" id="scannerDescription">Arahkan kamera ke barcode untuk scan</p>
                    <div id="scannerContainer" class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                        <div id="scannerPlaceholder" class="text-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
                        </div>
                        <div id="scannerVideo" class="w-full h-full hidden">
                            <div id="reader" class="w-full h-full"></div>
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
                    <div class="flex space-x-3" id="scannerControls">
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

<script>
// Debounce function untuk mengoptimalkan performa pencarian
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Variabel untuk keyboard navigation
let selectedIndex = -1;
let currentDropdown = null;

// Setup CSRF token untuk AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;

console.log('CSRF Token:', csrfToken);

// HTML5-QRCode Scanner
let html5QrcodeScanner = null;
let currentScanType = null; // 'anggota' or 'buku'

// Fungsi untuk mengatur tanggal dan jam secara real-time
function updateDateTime() {
    const now = new Date();
    
    // Format tanggal untuk input date (YYYY-MM-DD)
    const dateString = now.toISOString().split('T')[0];
    
    // Format jam untuk input time (HH:MM)
    const timeString = now.toTimeString().slice(0, 5);
    
    // Format jam untuk display real-time (HH:MM:SS)
    const timeDisplayString = now.toTimeString().slice(0, 8);
    
    // Update field tanggal dan jam
    const tanggalPinjam = document.getElementById('tanggal_peminjaman');
    const jamPinjam = document.getElementById('jam_peminjaman');
    const realTimeClock = document.getElementById('realTimeClock');
    
    if (tanggalPinjam) {
        tanggalPinjam.value = dateString;
    }
    
    if (jamPinjam) {
        jamPinjam.value = timeString;
    }
    
    if (realTimeClock) {
        realTimeClock.textContent = timeDisplayString;
    }
}

// Update waktu setiap detik untuk jam yang real-time
function startRealTimeUpdate() {
    updateDateTime(); // Update sekali di awal
    setInterval(updateDateTime, 1000); // Update setiap detik
}

// Jalankan update waktu saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    startRealTimeUpdate();
    
    // Set jam kembali default ke jam saat ini + 1 jam
    const jamKembali = document.getElementById('jam_kembali');
    if (jamKembali) {
        const now = new Date();
        now.setHours(now.getHours() + 1); // Tambah 1 jam dari sekarang
        const defaultTime = now.toTimeString().slice(0, 5);
        if (!jamKembali.value) {
            jamKembali.value = defaultTime;
        }
    }
    
    // Set tanggal kembali default ke tanggal pinjam
    const tanggalPinjam = document.getElementById('tanggal_peminjaman');
    const tanggalKembali = document.getElementById('tanggal_harus_kembali');
    if (tanggalPinjam && tanggalKembali) {
        if (!tanggalKembali.value) {
            tanggalKembali.value = tanggalPinjam.value;
        }
    }
    
    // Tambahkan event listener untuk tombol "Tambah Peminjaman"
    const form = document.querySelector('form[action*="peminjaman"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update waktu terakhir sebelum submit
            updateDateTime();
        });
    }
    
    // Tambahkan event listener untuk field jam kembali
    if (jamKembali) {
        jamKembali.addEventListener('change', function() {
            // Validasi jam kembali tidak boleh kosong
            if (!this.value) {
                alert('Jam kembali wajib diisi!');
                this.focus();
            }
        });
    }
    
    // Tambahkan event listener untuk field tanggal kembali
    if (tanggalKembali) {
        tanggalKembali.addEventListener('change', function() {
            validateTanggalKembali();
        });
    }
});

// Fungsi validasi tanggal kembali
function validateTanggalKembali() {
    const tanggalPinjam = document.getElementById('tanggal_peminjaman').value;
    const tanggalKembali = document.getElementById('tanggal_harus_kembali').value;
    
    if (tanggalPinjam && tanggalKembali) {
        const tanggalPinjamDate = new Date(tanggalPinjam);
        const tanggalKembaliDate = new Date(tanggalKembali);
        
        if (tanggalKembaliDate < tanggalPinjamDate) {
            alert('Tanggal kembali tidak boleh kurang dari tanggal pinjam!');
            document.getElementById('tanggal_harus_kembali').value = tanggalPinjam;
            document.getElementById('tanggal_harus_kembali').focus();
            return false;
        }
    }
    return true;
}

// Scanner functionality dengan HTML5-QRCode
document.getElementById('scanAnggotaBtn').addEventListener('click', function() {
    currentScanType = 'anggota';
    document.getElementById('scannerTitle').textContent = 'Scan Barcode Anggota';
    document.getElementById('scannerDescription').textContent = 'Arahkan kamera ke barcode anggota';
    document.getElementById('scannerModal').classList.remove('hidden');
    initializeHTML5QRCodeScanner();
});

document.getElementById('scanBukuBtn').addEventListener('click', function() {
    currentScanType = 'buku';
    document.getElementById('scannerTitle').textContent = 'Scan Barcode Buku';
    document.getElementById('scannerDescription').textContent = 'Arahkan kamera ke barcode buku';
    document.getElementById('scannerModal').classList.remove('hidden');
    initializeHTML5QRCodeScanner();
});

document.getElementById('closeScanner').addEventListener('click', function() {
    closeScanner();
});

document.getElementById('cancelScan').addEventListener('click', function() {
    closeScanner();
});

document.getElementById('startScanBtn').addEventListener('click', function() {
    startScanning();
});

document.getElementById('stopScanBtn').addEventListener('click', function() {
    stopScanning();
});

// Close modal when clicking outside
document.getElementById('scannerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScanner();
    }
});

// Initialize HTML5-QRCode Scanner
function initializeHTML5QRCodeScanner() {
    console.log('🚀 Initializing HTML5-QRCode scanner...');
    
    const scannerContainer = document.getElementById('scannerContainer');
    const scannerLoading = document.getElementById('scannerLoading');
    const scannerVideo = document.getElementById('scannerVideo');
    const scannerPlaceholder = document.getElementById('scannerPlaceholder');
    
    // Show loading
    scannerLoading.classList.remove('hidden');
    scannerPlaceholder.classList.add('hidden');
    scannerVideo.classList.remove('hidden');
    
    try {
        // Create HTML5-QRCode scanner
        html5QrcodeScanner = new Html5Qrcode("reader");
        
        // Configure scanner
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_CAMERA
            ]
        };
        
        // Start scanning
        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            console.log('📹 Scanner started successfully');
            scannerLoading.classList.add('hidden');
            scannerVideo.classList.remove('hidden');
            document.getElementById('scannerStatus').textContent = 'Scanner aktif';
            document.getElementById('startScanBtn').classList.add('hidden');
            document.getElementById('stopScanBtn').classList.remove('hidden');
            showNotification('Scanner HTML5-QRCode siap. Arahkan kamera ke barcode.', 'success');
        }).catch((err) => {
            console.error('❌ Scanner initialization error:', err);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            
            if (err.name === 'NotAllowedError') {
                showNotification('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
            } else if (err.name === 'NotFoundError') {
                showNotification('Tidak ada kamera yang ditemukan.', 'error');
            } else {
                showNotification('Gagal menginisialisasi scanner: ' + err.message, 'error');
            }
            
            // Fallback to manual input
            setupManualInput();
        });
        
    } catch (error) {
        console.error('❌ HTML5-QRCode initialization error:', error);
        scannerLoading.classList.add('hidden');
        scannerPlaceholder.classList.remove('hidden');
        scannerVideo.classList.add('hidden');
        showNotification('Gagal menginisialisasi scanner: ' + error.message, 'error');
        setupManualInput();
    }
}

// Scan success callback
function onScanSuccess(decodedText, decodedResult) {
    console.log('🎉 Barcode detected:', decodedText);
    processScannedBarcode(decodedText);
}

// Scan failure callback
function onScanFailure(error) {
    // Handle scan failure silently
    console.log('⚠️ Scan failure:', error);
}

function startScanning() {
    if (!html5QrcodeScanner) {
        showNotification('Scanner belum siap. Silakan tunggu.', 'warning');
        return;
    }
    
    try {
        document.getElementById('scannerStatus').textContent = 'Scanning...';
    } catch (error) {
        console.error('Error starting scanner:', error);
        showNotification('Gagal memulai scanner. Silakan coba lagi.', 'error');
    }
}

function stopScanning() {
    if (html5QrcodeScanner) {
        try {
            html5QrcodeScanner.stop();
            document.getElementById('scannerStatus').textContent = 'Scanner dihentikan';
        } catch (error) {
            console.error('Error stopping scanner:', error);
        }
    }
}

function closeScanner() {
    try {
        // Stop scanner if running
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                console.log('Scanner stopped successfully');
            }).catch((error) => {
                console.error('Error stopping scanner:', error);
            });
        }
        
    } catch (error) {
        console.error('Error stopping scanner:', error);
    }
    
    document.getElementById('scannerModal').classList.add('hidden');
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

// Manual input fallback
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

function showManualInputDialog() {
    const barcodeInput = prompt('Masukkan kode barcode:');
    if (barcodeInput && barcodeInput.trim()) {
        processScannedBarcode(barcodeInput.trim());
    }
}

// Process scanned barcode
function processScannedBarcode(barcode) {
    if (!currentScanType) {
        showNotification('Tipe scan tidak valid.', 'error');
        return;
    }
    
    console.log('🎯 Processing barcode:', barcode, 'for type:', currentScanType);
    
    // Show loading in status
    document.getElementById('scannerStatus').textContent = 'Memproses barcode...';
    
    if (currentScanType === 'anggota') {
        console.log('👤 Scanning anggota barcode:', barcode);
        // Search for anggota by barcode
        fetch(`{{ route('anggota.scan-barcode') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => {
            console.log('📡 Anggota scan response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📦 Anggota scan response data:', data);
            if (data.success) {
                const anggota = data.data;
                selectAnggota({
                    id: anggota.id,
                    nama_lengkap: anggota.nama_lengkap,
                    nomor_anggota: anggota.nomor_anggota,
                    barcode_anggota: anggota.barcode_anggota,
                    kelas: anggota.kelas ? anggota.kelas.nama_kelas : 'N/A',
                    jenis_anggota: anggota.jenis_anggota
                });
                closeScanner();
                showNotification(`Anggota ditemukan: ${anggota.nama_lengkap}`, 'success');
            } else {
                showNotification(data.message || 'Anggota tidak ditemukan', 'error');
                document.getElementById('scannerStatus').textContent = 'Scan gagal - coba lagi';
            }
        })
        .catch(error => {
            console.error('❌ Error scanning anggota:', error);
            showNotification('Terjadi kesalahan saat scan anggota: ' + error.message, 'error');
            document.getElementById('scannerStatus').textContent = 'Error - coba lagi';
        });
        
    } else if (currentScanType === 'buku') {
        console.log('📚 Scanning buku barcode:', barcode);
        // Search for buku by barcode
        fetch(`{{ route('buku.scan-barcode') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => {
            console.log('📡 Buku scan response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📦 Buku scan response data:', data);
            if (data.success) {
                const buku = data.data;
                selectBook({
                    id: buku.id,
                    judul_buku: buku.judul_buku,
                    penulis: buku.penulis,
                    isbn: buku.isbn,
                    stok_tersedia: buku.stok_tersedia,
                    kategori: buku.kategori
                });
                closeScanner();
                showNotification(`Buku ditemukan: ${buku.judul_buku}`, 'success');
            } else {
                showNotification(data.message || 'Buku tidak ditemukan', 'error');
                document.getElementById('scannerStatus').textContent = 'Scan gagal - coba lagi';
            }
        })
        .catch(error => {
            console.error('❌ Error scanning buku:', error);
            showNotification('Terjadi kesalahan saat scan buku: ' + error.message, 'error');
            document.getElementById('scannerStatus').textContent = 'Error - coba lagi';
        });
    }
}

// Fungsi untuk pencarian anggota dengan debounce
const searchAnggota = debounce(function(query) {
    const dropdown = document.getElementById('anggotaDropdown');
    currentDropdown = dropdown;
    selectedIndex = -1;
    
    if (query.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    console.log('🔍 Searching anggota with query:', query);
    
    // Tampilkan loading
    dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Mencari...</div>';
    dropdown.classList.remove('hidden');
    
    // Fetch anggota dari server
    fetch(`{{ route('peminjaman.search-anggota') }}?query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        console.log('📡 Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📦 Response data:', data);
        if (data.success && data.data.length > 0) {
            dropdown.innerHTML = '';
            data.data.forEach((anggota, index) => {
                const item = document.createElement('div');
                item.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition-colors duration-150 dropdown-item';
                item.setAttribute('data-index', index);
                item.innerHTML = `
                    <div class="font-medium text-gray-900">${anggota.nama_lengkap}</div>
                    <div class="text-sm text-gray-600">${anggota.nomor_anggota} - ${anggota.kelas}</div>
                    <div class="text-xs text-gray-500">Barcode: ${anggota.barcode_anggota || 'N/A'}</div>
                `;
                item.addEventListener('click', () => selectAnggota(anggota));
                item.addEventListener('mouseenter', () => {
                    selectedIndex = index;
                    updateSelectedItem();
                });
                dropdown.appendChild(item);
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500">Tidak ada anggota ditemukan</div>';
            dropdown.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('❌ Error searching anggota:', error);
        dropdown.innerHTML = `<div class="px-4 py-3 text-center text-red-500">Terjadi kesalahan: ${error.message}</div>`;
        dropdown.classList.remove('hidden');
    });
});

// Fungsi untuk update selected item
function updateSelectedItem() {
    if (!currentDropdown) return;
    
    const items = currentDropdown.querySelectorAll('[data-index]');
    items.forEach((item, index) => {
        if (index === selectedIndex) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
}

// Event listener untuk pencarian anggota
document.getElementById('anggota_search').addEventListener('input', function() {
    const query = this.value.trim();
    searchAnggota(query);
});

// Fungsi untuk memilih anggota
function selectAnggota(anggota) {
    document.getElementById('anggota_id').value = anggota.id;
    document.getElementById('anggotaNama').textContent = anggota.nama_lengkap;
    document.getElementById('anggotaNomor').textContent = anggota.nomor_anggota;
    document.getElementById('anggotaKelas').textContent = anggota.kelas;
    document.getElementById('anggotaInfo').classList.remove('hidden');
    document.getElementById('anggota_search').value = anggota.nama_lengkap;
    document.getElementById('anggotaDropdown').classList.add('hidden');
    selectedIndex = -1;
    
    // Update submit button
    updateSubmitButton();
    
    // Tampilkan notifikasi
    showNotification(`Anggota ${anggota.nama_lengkap} dipilih!`, 'success');
}

// Fungsi untuk clear anggota
document.getElementById('clearAnggota').addEventListener('click', function() {
    document.getElementById('anggota_id').value = '';
    document.getElementById('anggotaInfo').classList.add('hidden');
    document.getElementById('anggota_search').value = '';
    document.getElementById('anggotaDropdown').classList.add('hidden');
    selectedIndex = -1;
    updateSubmitButton();
});

// Fungsi untuk pencarian buku dengan debounce
const searchBuku = debounce(function(query) {
    const dropdown = document.getElementById('bukuDropdown');
    currentDropdown = dropdown;
    selectedIndex = -1;
    
    if (query.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    console.log('🔍 Searching buku with query:', query);
    
    // Tampilkan loading
    dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Mencari...</div>';
    dropdown.classList.remove('hidden');
    
    // Fetch buku dari server
    fetch(`{{ route('peminjaman.search-buku') }}?query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        console.log('📡 Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📦 Response data:', data);
        if (data.success && data.data.length > 0) {
            dropdown.innerHTML = '';
            data.data.forEach((book, index) => {
                const item = document.createElement('div');
                item.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition-colors duration-150 dropdown-item';
                item.setAttribute('data-index', index);
                item.innerHTML = `
                    <div class="font-medium text-gray-900">${book.judul_buku}</div>
                    <div class="text-sm text-gray-600">${book.penulis || 'N/A'} - Stok: ${book.stok_tersedia}</div>
                    <div class="text-xs text-gray-500">ISBN: ${book.isbn || 'N/A'} | Kategori: ${book.kategori}</div>
                `;
                item.addEventListener('click', () => selectBook(book));
                item.addEventListener('mouseenter', () => {
                    selectedIndex = index;
                    updateSelectedItem();
                });
                dropdown.appendChild(item);
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500">Tidak ada buku ditemukan</div>';
            dropdown.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('❌ Error searching buku:', error);
        dropdown.innerHTML = `<div class="px-4 py-3 text-center text-red-500">Terjadi kesalahan: ${error.message}</div>`;
        dropdown.classList.remove('hidden');
    });
});

// Event listener untuk pencarian buku
document.getElementById('buku_search').addEventListener('input', function() {
    const query = this.value.trim();
    searchBuku(query);
});

// Fungsi untuk memilih buku
function selectBook(book) {
    const selectedBooksList = document.getElementById('selectedBooksList');
    const selectedCount = document.getElementById('selectedCount');
    
    // Cek apakah buku sudah dipilih
    const existingBook = document.querySelector(`[data-book-id="${book.id}"]`);
    if (existingBook) {
        showNotification('Buku ini sudah dipilih!', 'warning');
        return;
    }
    
    // Cek stok
    if (book.stok_tersedia <= 0) {
        showNotification('Buku tidak tersedia untuk dipinjam!', 'error');
        return;
    }
    
    // Tambah buku ke daftar yang dipilih dengan field jumlah
    const bookItem = document.createElement('div');
    bookItem.className = 'flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors duration-150 book-item';
    bookItem.setAttribute('data-book-id', book.id);
    bookItem.innerHTML = `
        <div class="flex-1">
            <h5 class="font-semibold text-sm text-gray-900">${book.judul_buku}</h5>
            <p class="text-xs text-gray-600">${book.penulis || 'N/A'} - Stok Tersedia: ${book.stok_tersedia}</p>
            <p class="text-xs text-gray-500">ISBN: ${book.isbn || 'N/A'} | Kategori: ${book.kategori}</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2">
                <label class="text-xs font-medium text-gray-700">Jumlah:</label>
                <input type="number" 
                       name="jumlah_buku[${book.id}]" 
                       value="1" 
                       min="1" 
                       max="${book.stok_tersedia}"
                       class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onchange="updateTotalJumlah()">
            </div>
            <button type="button" class="text-red-500 hover:text-red-700 ml-2 transition-colors duration-150" onclick="removeBook(${book.id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    selectedBooksList.appendChild(bookItem);
    
    // No need to remove default input since it doesn't exist anymore
    
    // Update input hidden
    const bookInput = document.createElement('input');
    bookInput.type = 'hidden';
    bookInput.name = 'buku_ids[]';
    bookInput.value = book.id;
    bookInput.className = 'book-input';
    bookInput.setAttribute('data-book-id', book.id);
    
    // Add to specific container
    const hiddenContainer = document.getElementById('hiddenBookInputs');
    hiddenContainer.appendChild(bookInput);
    
    console.log('Book input created:', {
        id: book.id,
        name: bookInput.name,
        value: bookInput.value,
        totalInputs: document.querySelectorAll('input[name="buku_ids[]"]').length
    });
    
    // Update counter
    const currentCount = parseInt(selectedCount.textContent);
    selectedCount.textContent = currentCount + 1;
    
    // Clear search
    document.getElementById('buku_search').value = '';
    document.getElementById('bukuDropdown').classList.add('hidden');
    selectedIndex = -1;
    
    // Update submit button
    updateSubmitButton();
    updateTotalJumlah();
    
    // Tampilkan notifikasi sukses
    showNotification('Buku berhasil ditambahkan!', 'success');
}

// Fungsi untuk menghapus buku
function removeBook(bookId) {
    const bookItem = document.querySelector(`[data-book-id="${bookId}"]`);
    if (bookItem) {
        bookItem.remove();
        
        // Remove input hidden
        const bookInput = document.querySelector(`input[data-book-id="${bookId}"]`);
        if (bookInput) {
            bookInput.remove();
            console.log('Book input removed:', {
                bookId: bookId,
                remainingInputs: document.querySelectorAll('input[name="buku_ids[]"]').length
            });
        } else {
            console.warn('Book input not found for removal:', bookId);
        }
        
        // Remove jumlah input
        const jumlahInput = document.querySelector(`input[name="jumlah_buku[${bookId}]"]`);
        if (jumlahInput) {
            jumlahInput.remove();
        }
        
        // Update counter
        const selectedCount = document.getElementById('selectedCount');
        const currentCount = parseInt(selectedCount.textContent);
        selectedCount.textContent = currentCount - 1;
        
        // No need to add back default input
        
        // Update submit button and total
        updateSubmitButton();
        updateTotalJumlah();
        
        // Tampilkan notifikasi
        showNotification('Buku berhasil dihapus!', 'success');
    }
}

// Fungsi untuk update total jumlah buku
function updateTotalJumlah() {
    const jumlahInputs = document.querySelectorAll('input[name^="jumlah_buku["]');
    let total = 0;
    
    jumlahInputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });
    
    document.getElementById('totalJumlah').textContent = total;
}

// Fungsi untuk update submit button
function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const selectedCount = parseInt(document.getElementById('selectedCount').textContent);
    const anggotaId = document.getElementById('anggota_id').value;
    
    if (selectedCount > 0 && anggotaId) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Fungsi untuk menampilkan notifikasi dengan SweetAlert2
function showNotification(message, type = 'info') {
    switch(type) {
        case 'success':
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
            break;
        case 'error':
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                timer: 4000,
                showConfirmButton: true
            });
            break;
        case 'warning':
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
            break;
        case 'info':
        default:
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
            break;
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const anggotaSearch = document.getElementById('anggota_search');
    const anggotaDropdown = document.getElementById('anggotaDropdown');
    const bukuSearch = document.getElementById('buku_search');
    const bukuDropdown = document.getElementById('bukuDropdown');
    
    if (!anggotaSearch.contains(event.target) && !anggotaDropdown.contains(event.target)) {
        anggotaDropdown.classList.add('hidden');
        selectedIndex = -1;
    }
    
    if (!bukuSearch.contains(event.target) && !bukuDropdown.contains(event.target)) {
        bukuDropdown.classList.add('hidden');
        selectedIndex = -1;
    }
});

// Auto hide messages
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

// Initialize submit button state
updateSubmitButton();

// Form validation before submit
function validateForm() {
    const anggotaId = document.getElementById('anggota_id').value;
    const selectedCount = parseInt(document.getElementById('selectedCount').textContent);
    const bookInputs = document.querySelectorAll('#hiddenBookInputs input[name="buku_ids[]"]');
    const jamKembali = document.getElementById('jam_kembali').value;
    const tanggalKembali = document.getElementById('tanggal_harus_kembali').value;
    
    console.log('Form Validation:', {
        anggotaId: anggotaId,
        selectedCount: selectedCount,
        bookInputsLength: bookInputs.length,
        bookInputsValues: Array.from(bookInputs).map(input => input.value),
        jamKembali: jamKembali,
        tanggalKembali: tanggalKembali
    });
    
    // Validate anggota
    if (!anggotaId || anggotaId === '') {
        alert('Pilih anggota terlebih dahulu!');
        return false;
    }
    
    // Validate books - check actual hidden inputs
    if (bookInputs.length === 0) {
        alert('Pilih minimal 1 buku untuk dipinjam!');
        return false;
    }
    
    // Check if all book inputs have valid values
    const validBookInputs = Array.from(bookInputs).filter(input => 
        input.value && input.value !== '' && input.value !== null && !isNaN(input.value)
    );
    
    if (validBookInputs.length === 0) {
        alert('Tidak ada buku yang valid dipilih!');
        return false;
    }
    
    // Validate jam kembali (wajib diisi)
    if (!jamKembali || jamKembali === '') {
        alert('Jam kembali wajib diisi!');
        document.getElementById('jam_kembali').focus();
        return false;
    }
    
    // Validate tanggal kembali
    if (!validateTanggalKembali()) {
        return false;
    }
    
    console.log('Form validation passed!', validBookInputs.length, 'valid books');
    return true;
}

// Debug functions
function toggleManualInput() {
    const manualInput = document.getElementById('manualBookInput');
    if (manualInput.style.display === 'none') {
        manualInput.style.display = 'block';
    } else {
        manualInput.style.display = 'none';
    }
}

function addManualBook() {
    const bookId = document.getElementById('manual_book_id').value;
    if (bookId && !isNaN(bookId)) {
        const hiddenContainer = document.getElementById('hiddenBookInputs');
        
        // Check if already exists
        const existing = document.querySelector(`input[data-book-id="${bookId}"]`);
        if (existing) {
            alert('Book ID ' + bookId + ' already added');
            return;
        }
        
        // Create hidden input
        const bookInput = document.createElement('input');
        bookInput.type = 'hidden';
        bookInput.name = 'buku_ids[]';
        bookInput.value = bookId;
        bookInput.setAttribute('data-book-id', bookId);
        hiddenContainer.appendChild(bookInput);
        
        // Create quantity input
        const qtyInput = document.createElement('input');
        qtyInput.type = 'hidden';
        qtyInput.name = 'jumlah_buku[' + bookId + ']';
        qtyInput.value = '1';
        hiddenContainer.appendChild(qtyInput);
        
        // Update display
        const selectedCount = document.getElementById('selectedCount');
        selectedCount.textContent = parseInt(selectedCount.textContent) + 1;
        
        // Clear input
        document.getElementById('manual_book_id').value = '';
        
        console.log('Manual book added:', bookId);
        alert('Book ID ' + bookId + ' added successfully');
    } else {
        alert('Please enter a valid book ID');
    }
}
</script>
@endsection 