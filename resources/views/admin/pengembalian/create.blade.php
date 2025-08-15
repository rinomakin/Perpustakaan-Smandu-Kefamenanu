@extends('layouts.admin')

@section('title', 'Proses Pengembalian')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Include HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
/* Custom styles */
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

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.late-warning {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #ef4444;
}

.book-item {
    transition: all 0.2s ease-in-out;
}

.book-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
</style>

<div class="min-h-screen bg-gradient-to-br py-8">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <!-- <h1 class="text-3xl font-bold text-gray-900">Proses Pengembalian</h1> -->
                <!-- <p class="text-gray-600 mt-2">Scan kartu anggota untuk melihat peminjaman aktif</p> -->
            </div>
            <a href="{{ route('pengembalian.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <h3 class="text-[14px] font-semibold text-white">Form Pengembalian Buku</h3>
            </div>
            
            <!-- Step 1: Scan/Search Anggota -->
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-xs font-semibold text-gray-900 mb-4 ">
                    <i class="fas fa-user-check mr-2"></i>Langkah 1: Identifikasi Anggota
                </h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 border ">
                    <!-- Scan Barcode -->
                    <div class="">
                        <div class="bg-blue-50 p-4 text-xs rounded-lg border ">
                            <h5 class="font-semibold text-blue-900 mb-2">Cari Anggota</h5>
                            <p class="text-sm text-blue-700 mb-4">Arahkan kamera ke barcode kartu anggota untuk identifikasi otomatis</p>
                            
                            <div class="flex gap-4">

                            <div class=" w-96">
                                <div class="relative">
                                    <input type="text" id="anggota_search" 
                                        placeholder="Cari nama anggota atau nomor anggota (hanya yang sedang meminjam)..." 
                                        class="w-full px-4 py-3 border outline-none border-gray-300 rounded-lg focus:ring-1 focus:ring-green-300 focus:border-green-300 transition-all duration-200">
                                    
                                    <!-- Dropdown hasil pencarian -->
                                    <div id="anggotaDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                        <!-- Hasil pencarian akan muncul di sini -->
                                    </div>
                                </div>
                            </div>

                                <div class="">
                                    <button type="button" id="scanAnggotaBtn" 
                                    class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition-all duration-200">
                                    <i class="fas fa-qrcode mr-2"></i>scan
                                </button>
                                </div>
                            </div>

                        </div>
                    </div>


                   
                </div>

                <!-- Info Anggota yang Dipilih -->
                <div id="anggotaInfo" class="mt-6 p-6 bg-green-50 rounded-lg border border-green-200 hidden">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 id="anggotaNama" class="text-sm font-semibold text-gray-900"></h4>
                            <p id="anggotaNomor" class="text-xs text-gray-600"></p>
                            <p id="anggotaKelas" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" id="clearAnggota" class="text-red-500 hover:text-red-700 transition-colors duration-150">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Peminjaman Aktif -->
            <div id="peminjamanSection" class="p-6 hidden">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">
                    <i class="fas fa-books mr-2"></i>Langkah 2: Peminjaman Aktif
                </h4>
                
                <div id="peminjamanList" class="space-y-4 text-sm">
                    <!-- Peminjaman aktif akan ditampilkan di sini -->
                </div>

                <div id="noPeminjaman" class="text-center text-xs py-8 hidden">
                    <i class="fas fa-check-circle text-xs text-green-300 mb-4"></i>
                    <h3 class="text-xs font-medium text-gray-900 mb-2">Tidak Ada Peminjaman Aktif</h3>
                    <p class="text-gray-600">Anggota ini tidak memiliki peminjaman yang perlu dikembalikan.</p>
                </div>
            </div>

            <!-- Step 3: Form Pengembalian -->
            <form id="pengembalianForm" action="{{ route('pengembalian.store') }}" method="POST" class="hidden" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="peminjaman_id" id="selectedPeminjamanId">
                <input type="hidden" name="status_pembayaran_denda" id="hiddenStatusPembayaranDenda" value="belum_dibayar">
                <input type="hidden" name="tanggal_pembayaran_denda" id="hiddenTanggalPembayaranDenda" value="">
                <input type="hidden" name="catatan_pembayaran_denda" id="hiddenCatatanPembayaranDenda" value="">
                
                <div class="p-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clipboard-check mr-2"></i>Langkah 3: Detail Pengembalian
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Tanggal Pengembalian -->
                        <div>
                            <label for="tanggal_kembali" class="block text-xs font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-2"></i>Tanggal Pengembalian
                            </label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" 
                                   value="{{ date('Y-m-d') }}" required
                                   class="w-full text-xs px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>

                        <!-- Jam Pengembalian -->
                        <div>
                            <label for="jam_kembali" class="block text-xs font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2"></i>Jam Pengembalian
                            </label>
                            <input type="time" name="jam_kembali" id="jam_kembali" 
                                   value="{{ date('H:i') }}"
                                   class="w-full text-xs px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Kondisi Buku -->
                    <div class="mb-6">
                        <h5 class="text-xs font-medium text-gray-700 mb-3">
                            <i class="fas fa-book-open mr-2"></i>Kondisi Buku Saat Dikembalikan
                        </h5>
                        <div id="kondisiBukuList" class="space-y-3 text-xs">
                            <!-- Kondisi buku akan ditampilkan di sini -->
                        </div>
                    </div>

                    <!-- Catatan Pengembalian -->
                    <div class="mb-6">
                        <label for="catatan_pengembalian" class="block text-xs font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Catatan Pengembalian
                        </label>
                        <textarea name="catatan_pengembalian" id="catatan_pengembalian" rows="3" 
                                  class="w-full px-4 py-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                  placeholder="Catatan tambahan untuk pengembalian ini..."></textarea>
                    </div>

                    <!-- Informasi Denda -->
                    <div id="dendaInfo" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                        <h5 class="font-semibold text-red-900 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Informasi Denda
                        </h5>
                        <div id="dendaDetail" class="text-xs text-red-700">
                            <!-- Detail denda akan ditampilkan di sini -->
                        </div>
                    </div>

                    <!-- Form Pembayaran Denda -->
                    <div id="formPembayaranDenda" class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                        <h5 class="font-semibold text-yellow-900 mb-4">
                            <i class="fas fa-credit-card mr-2"></i>Form Pembayaran Denda
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status Pembayaran -->
                            <div>
                                <label for="status_pembayaran_denda" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>Status Pembayaran
                                </label>
                                <select name="status_pembayaran_denda" id="status_pembayaran_denda" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                                    <option value="belum_dibayar">Belum Dibayar</option>
                                    <option value="sudah_dibayar">Sudah Dibayar</option>
                                </select>
                            </div>

                            <!-- Tanggal Pembayaran -->
                            <div>
                                <label for="tanggal_pembayaran_denda" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-2"></i>Tanggal Pembayaran
                                </label>
                                                            <input type="date" name="tanggal_pembayaran_denda" id="tanggal_pembayaran_denda" 
                                   value="{{ date('Y-m-d') }}" disabled
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 bg-gray-100">
                            </div>
                        </div>

                        <!-- Catatan Pembayaran -->
                        <div class="mt-4">
                            <label for="catatan_pembayaran_denda" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note mr-2"></i>Catatan Pembayaran
                            </label>
                            <textarea name="catatan_pembayaran_denda" id="catatan_pembayaran_denda" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                                      placeholder="Catatan tambahan untuk pembayaran denda..."></textarea>
                        </div>

                        <!-- Informasi Total Denda -->
                        <div class="mt-4 p-3 bg-white border border-yellow-300 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Denda:</span>
                                <span id="totalDendaDisplay" class="text-lg font-bold text-red-600">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="resetForm()" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </button>
                        <button type="submit" id="submitBtn"
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                            <i class="fas fa-check mr-2"></i>Proses Pengembalian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Scan Kartu Anggota</h3>
                    <button type="button" id="closeScanner" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 mb-4">Arahkan kamera ke barcode kartu anggota</p>
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
                    <div class="flex space-x-3">
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
// Setup CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Variables
let html5QrcodeScanner = null;
let selectedAnggota = null;
let selectedPeminjaman = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    startRealTimeUpdate();
});

// Fungsi untuk mengatur tanggal dan jam secara real-time
function updateDateTime() {
    const now = new Date();
    
    // Format tanggal untuk input date (YYYY-MM-DD)
    const dateString = now.toISOString().split('T')[0];
    
    // Format jam untuk input time (HH:MM)
    const timeString = now.toTimeString().slice(0, 5);
    
    // Update field tanggal dan jam
    const tanggalKembali = document.getElementById('tanggal_kembali');
    const jamKembali = document.getElementById('jam_kembali');
    
    if (tanggalKembali) {
        tanggalKembali.value = dateString;
    }
    
    if (jamKembali) {
        jamKembali.value = timeString;
    }
}

// Update waktu setiap detik untuk jam yang real-time
function startRealTimeUpdate() {
    updateDateTime(); // Update sekali di awal
    setInterval(updateDateTime, 1000); // Update setiap detik
}

function setupEventListeners() {
    // Scan button
    document.getElementById('scanAnggotaBtn').addEventListener('click', function() {
        document.getElementById('scannerModal').classList.remove('hidden');
        initializeScanner();
    });

    // Close scanner
    document.getElementById('closeScanner').addEventListener('click', closeScanner);
    document.getElementById('cancelScan').addEventListener('click', closeScanner);

    // Clear anggota
    document.getElementById('clearAnggota').addEventListener('click', clearAnggota);

    // Search anggota
    document.getElementById('anggota_search').addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length >= 2) {
            searchAnggota(query);
        } else {
            document.getElementById('anggotaDropdown').classList.add('hidden');
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const search = document.getElementById('anggota_search');
        const dropdown = document.getElementById('anggotaDropdown');
        if (!search.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Form submit event
    const form = document.querySelector('form[action*="pengembalian"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update waktu terakhir sebelum submit
            updateDateTime();
        });
    }
    
    // Status pembayaran denda event listener
    document.getElementById('status_pembayaran_denda').addEventListener('change', function() {
        const tanggalPembayaran = document.getElementById('tanggal_pembayaran_denda');
        if (this.value === 'sudah_dibayar') {
            tanggalPembayaran.disabled = false;
            tanggalPembayaran.classList.remove('bg-gray-100');
        } else {
            tanggalPembayaran.disabled = true;
            tanggalPembayaran.classList.add('bg-gray-100');
            // Clear tanggal pembayaran jika status bukan sudah_dibayar
            tanggalPembayaran.value = '';
            document.getElementById('hiddenTanggalPembayaranDenda').value = '';
        }
        
        // Update hidden input
        document.getElementById('hiddenStatusPembayaranDenda').value = this.value;
    });
    
    // Tanggal pembayaran denda event listener
    document.getElementById('tanggal_pembayaran_denda').addEventListener('change', function() {
        document.getElementById('hiddenTanggalPembayaranDenda').value = this.value;
    });
    
    // Catatan pembayaran denda event listener
    document.getElementById('catatan_pembayaran_denda').addEventListener('input', function() {
        document.getElementById('hiddenCatatanPembayaranDenda').value = this.value;
    });
}

// Scanner functions
function initializeScanner() {
    const scannerLoading = document.getElementById('scannerLoading');
    const scannerVideo = document.getElementById('scannerVideo');
    const scannerPlaceholder = document.getElementById('scannerPlaceholder');
    
    scannerLoading.classList.remove('hidden');
    scannerPlaceholder.classList.add('hidden');
    scannerVideo.classList.remove('hidden');
    
    try {
        html5QrcodeScanner = new Html5Qrcode("reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            scannerLoading.classList.add('hidden');
            document.getElementById('scannerStatus').textContent = 'Scanner aktif - arahkan ke barcode';
        }).catch((err) => {
            console.error('Scanner initialization error:', err);
            scannerLoading.classList.add('hidden');
            scannerPlaceholder.classList.remove('hidden');
            scannerVideo.classList.add('hidden');
            showNotification('Gagal menginisialisasi scanner: ' + err.message, 'error');
        });
        
    } catch (error) {
        console.error('Scanner error:', error);
        scannerLoading.classList.add('hidden');
        scannerPlaceholder.classList.remove('hidden');
        scannerVideo.classList.add('hidden');
        showNotification('Gagal menginisialisasi scanner', 'error');
    }
}

function onScanSuccess(decodedText, decodedResult) {
    console.log('Barcode scanned:', decodedText);
    processScannedBarcode(decodedText);
}

function onScanFailure(error) {
    // Silent failure handling
}

function processScannedBarcode(barcode) {
    document.getElementById('scannerStatus').textContent = 'Memproses barcode...';
    
    fetch(`/admin/pengembalian/scan-barcode-anggota`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ barcode: barcode })
    })
    .then(response => {
        console.log('📡 Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            selectAnggota(data.data.anggota);
            loadPeminjamanAktif(data.data.peminjaman);
            closeScanner();
            showNotification(`Anggota ditemukan: ${data.data.anggota.nama_lengkap}`, 'success');
        } else {
            showNotification(data.message || 'Anggota tidak ditemukan', 'error');
            document.getElementById('scannerStatus').textContent = 'Scan gagal - coba lagi';
        }
    })
    .catch(error => {
        console.error('❌ Error scanning:', error);
        showNotification('Terjadi kesalahan saat scan: ' + error.message, 'error');
        document.getElementById('scannerStatus').textContent = 'Error - coba lagi';
    });
}

function closeScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().catch(err => console.error('Error stopping scanner:', err));
    }
    
    document.getElementById('scannerModal').classList.add('hidden');
    document.getElementById('scannerStatus').textContent = 'Siap untuk scan';
    
    // Reset scanner container
    const scannerLoading = document.getElementById('scannerLoading');
    const scannerPlaceholder = document.getElementById('scannerPlaceholder');
    const scannerVideo = document.getElementById('scannerVideo');
    
    scannerLoading.classList.add('hidden');
    scannerPlaceholder.classList.remove('hidden');
    scannerVideo.classList.add('hidden');
}

// Search functions
function searchAnggota(query) {
    const dropdown = document.getElementById('anggotaDropdown');
    
    console.log('🔍 Searching for:', query);
    
    dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Mencari...</div>';
    dropdown.classList.remove('hidden');
    
    // Sementara gunakan route test untuk debugging
    const url = `/test-pengembalian-search?query=${encodeURIComponent(query)}`;
    console.log('🌐 Search URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
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
        console.log('📦 Search response:', data);
        
        if (data.success && data.data && data.data.length > 0) {
            dropdown.innerHTML = '';
            data.data.forEach((anggota) => {
                console.log('👤 Processing anggota:', anggota);
                
                const item = document.createElement('div');
                item.className = 'px-4 py-3 hover:bg-green-50 cursor-pointer border-b border-gray-100 transition-colors duration-150 dropdown-item';
                
                // Langsung ambil dari structure anggota
                const jumlahPeminjaman = anggota.jumlah_peminjaman_aktif || 0;
                const detailPeminjaman = anggota.detail_peminjaman || [];
                
                item.innerHTML = `
                    <div class="font-medium text-gray-900">${anggota.nama_lengkap || 'N/A'}</div>
                    <div class="text-sm text-gray-600">${anggota.nis || anggota.nomor_anggota || 'N/A'} - ${anggota.kelas || 'N/A'} (${anggota.jenis_anggota || anggota.jurusan || 'N/A'})</div>
                    <div class="text-xs text-gray-500">Barcode: ${anggota.barcode_anggota || 'Belum ada barcode'}</div>
                    <div class="text-xs text-blue-600 font-medium mt-1">
                        <i class="fas fa-book mr-1"></i>${jumlahPeminjaman} peminjaman aktif
                    </div>
                    ${detailPeminjaman.length > 0 ? `
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>Buku: ${detailPeminjaman[0].nomor_peminjaman || 'N/A'}
                        </div>
                    ` : ''}
                `;
                
                item.addEventListener('click', () => {
                    console.log('🎯 Selected anggota:', anggota);
                    selectAnggota(anggota);
                    if (detailPeminjaman.length > 0) {
                        // Convert detail_peminjaman ke format yang diharapkan loadPeminjamanAktif
                        const formattedPeminjaman = detailPeminjaman.map(detail => ({
                            id: detail.id,
                            nomor_peminjaman: detail.nomor_peminjaman,
                            tanggal_peminjaman: detail.tanggal_peminjaman,
                            tanggal_harus_kembali: detail.tanggal_harus_kembali,
                            is_late: false, // Will be calculated server side
                            days_late: 0,
                            detail_peminjaman: detail.buku || []
                        }));
                        loadPeminjamanAktif(formattedPeminjaman);
                    } else {
                        getPeminjamanAktif(anggota.id);
                    }
                    dropdown.classList.add('hidden');
                });
                dropdown.appendChild(item);
            });
        } else {
            dropdown.innerHTML = `
                <div class="px-4 py-3 text-center text-gray-500">
                    <i class="fas fa-search mr-2"></i>Tidak ada anggota dengan peminjaman aktif ditemukan
                    <div class="text-xs text-gray-400 mt-1">Coba cari dengan nama, NIS, atau nomor anggota yang sedang meminjam buku</div>
                    <div class="text-xs text-red-400 mt-1">Query: "${query}"</div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('❌ Error searching anggota:', error);
        dropdown.innerHTML = `
            <div class="px-4 py-3 text-center text-red-500">
                <i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan saat mencari
                <div class="text-xs text-red-400 mt-1">${error.message}</div>
                <div class="text-xs text-gray-500 mt-1">Coba refresh halaman atau hubungi admin</div>
            </div>
        `;
    });
}

function selectAnggota(anggota) {
    selectedAnggota = anggota;
    
    document.getElementById('anggotaNama').textContent = anggota.nama_lengkap;
    document.getElementById('anggotaNomor').textContent = anggota.nomor_anggota;
    document.getElementById('anggotaKelas').textContent = anggota.kelas + ' - ' + anggota.jenis_anggota;
    document.getElementById('anggotaInfo').classList.remove('hidden');
    document.getElementById('anggota_search').value = anggota.nama_lengkap;
    document.getElementById('anggotaDropdown').classList.add('hidden');
}

function clearAnggota() {
    selectedAnggota = null;
    selectedPeminjaman = null;
    
    document.getElementById('anggotaInfo').classList.add('hidden');
    document.getElementById('peminjamanSection').classList.add('hidden');
    document.getElementById('pengembalianForm').classList.add('hidden');
    document.getElementById('anggota_search').value = '';
    document.getElementById('selectedPeminjamanId').value = '';
}

function getPeminjamanAktif(anggotaId) {
    fetch(`/admin/pengembalian/get-peminjaman-aktif?anggota_id=${anggotaId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPeminjamanAktif(data.data);
        } else {
            showNotification(data.message, 'warning');
            document.getElementById('peminjamanSection').classList.remove('hidden');
            document.getElementById('noPeminjaman').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error getting peminjaman aktif:', error);
        showNotification('Terjadi kesalahan saat mengambil data peminjaman', 'error');
    });
}

function loadPeminjamanAktif(peminjamanData) {
    const peminjamanList = document.getElementById('peminjamanList');
    const noPeminjaman = document.getElementById('noPeminjaman');
    const peminjamanSection = document.getElementById('peminjamanSection');
    
    peminjamanSection.classList.remove('hidden');
    
    if (peminjamanData.length === 0) {
        noPeminjaman.classList.remove('hidden');
        peminjamanList.innerHTML = '';
        showNotification('Tidak ada peminjaman aktif untuk anggota ini', 'info');
        return;
    }
    
    noPeminjaman.classList.add('hidden');
    peminjamanList.innerHTML = '';
    
    peminjamanData.forEach(peminjaman => {
        const peminjamanItem = createPeminjamanItem(peminjaman);
        peminjamanList.appendChild(peminjamanItem);
    });
    
    // Auto-select first peminjaman if only one
    if (peminjamanData.length === 1) {
        selectPeminjaman(peminjamanData[0]);
    }
    
    // Show success message with peminjaman count
    showNotification(`Ditemukan ${peminjamanData.length} peminjaman aktif untuk anggota ini`, 'success');
}

function createPeminjamanItem(peminjaman) {
    const div = document.createElement('div');
    div.className = `p-4 border text-xs rounded-lg cursor-pointer transition-all duration-200 ${
        peminjaman.is_late ? 'late-warning border-red-300' : 'border-gray-200 hover:border-green-300'
    }`;
    div.setAttribute('data-peminjaman-id', peminjaman.id);
    
    let lateWarning = '';
    if (peminjaman.is_late) {
        lateWarning = `
            <div class="flex items-center text-red-600 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="text-xs font-semibold">Terlambat ${peminjaman.days_late} hari - Denda: Rp ${(peminjaman.days_late * 1000).toLocaleString()}</span>
            </div>
        `;
    }
    
    div.innerHTML = `
        ${lateWarning}
        <div class="flex justify-between items-start mb-3">
            <div>
                <h6 class="font-semibold text-gray-900">${peminjaman.nomor_peminjaman}</h6>
                <p class="text-xs text-gray-600">Dipinjam: ${peminjaman.tanggal_peminjaman}</p>
                <p class="text-xs text-gray-600">Harus kembali: ${peminjaman.tanggal_harus_kembali}</p>
            </div>
            <span class="px-3 py-1 text-xs font-medium rounded-full ${
                peminjaman.is_late ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'
            }">
                ${peminjaman.detail_peminjaman.length} buku
            </span>
        </div>
        <div class="space-y-2">
            ${peminjaman.detail_peminjaman.map(detail => `
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">${detail.judul_buku}</p>
                        <p class="text-xs text-gray-600">${detail.penulis} - ${detail.kategori}</p>
                    </div>
                    <span class="text-xs text-gray-500">Qty: ${detail.jumlah}</span>
                </div>
            `).join('')}
        </div>
    `;
    
    div.addEventListener('click', () => selectPeminjaman(peminjaman));
    
    return div;
}

function selectPeminjaman(peminjaman) {
    selectedPeminjaman = peminjaman;
    
    // Update form
    document.getElementById('selectedPeminjamanId').value = peminjaman.id;
    
    // Show form
    document.getElementById('pengembalianForm').classList.remove('hidden');
    
    // Load kondisi buku
    loadKondisiBuku(peminjaman.detail_peminjaman);
    
    // Show denda info if late
    if (peminjaman.is_late) {
        showDendaInfo(peminjaman.days_late);
    } else {
        document.getElementById('dendaInfo').classList.add('hidden');
        hideFormPembayaranDenda();
    }
    
    // Highlight selected
    document.querySelectorAll('#peminjamanList > div').forEach(item => {
        item.classList.remove('ring-2', 'ring-green-500');
    });
    // Find the clicked element and add highlight
    const clickedElement = document.querySelector(`[data-peminjaman-id="${peminjaman.id}"]`);
    if (clickedElement) {
        clickedElement.classList.add('ring-2', 'ring-green-500');
    }
    
    showNotification(`Peminjaman ${peminjaman.nomor_peminjaman} dipilih`, 'success');
}

function loadKondisiBuku(detailPeminjaman) {
    const kondisiList = document.getElementById('kondisiBukuList');
    kondisiList.innerHTML = '';
    
    detailPeminjaman.forEach(detail => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        div.innerHTML = `
            <div class="flex-1">
                <p class="font-medium text-gray-900">${detail.judul_buku}</p>
                <p class="text-xs text-gray-600">${detail.penulis} (Qty: ${detail.jumlah})</p>
            </div>
            <select name="kondisi_kembali[${detail.id}]" required 
                    class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
                <option value="hilang">Hilang</option>
            </select>
        `;
        kondisiList.appendChild(div);
    });
}

function showDendaInfo(daysLate) {
    const dendaAmount = daysLate * 1000;
    document.getElementById('dendaDetail').innerHTML = `
        <p><strong>Keterlambatan:</strong> ${daysLate} hari</p>
        <p><strong>Denda per hari:</strong> Rp 1.000</p>
        <p><strong>Total denda:</strong> Rp ${dendaAmount.toLocaleString()}</p>
        <p class="mt-2 text-xs">Denda akan otomatis ditambahkan ke sistem</p>
    `;
    document.getElementById('dendaInfo').classList.remove('hidden');
    
    // Tampilkan form pembayaran denda jika ada keterlambatan
    if (daysLate > 0) {
        showFormPembayaranDenda(dendaAmount);
    } else {
        hideFormPembayaranDenda();
    }
}

function showFormPembayaranDenda(totalDenda) {
    document.getElementById('formPembayaranDenda').classList.remove('hidden');
    document.getElementById('totalDendaDisplay').textContent = `Rp ${totalDenda.toLocaleString()}`;
    
    // Set tanggal pembayaran ke hari ini
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_pembayaran_denda').value = today;
    
    // Set status pembayaran default ke "belum_dibayar"
    document.getElementById('status_pembayaran_denda').value = 'belum_dibayar';
    
    // Update hidden inputs
    document.getElementById('hiddenStatusPembayaranDenda').value = 'belum_dibayar';
    document.getElementById('hiddenTanggalPembayaranDenda').value = today;
    document.getElementById('hiddenCatatanPembayaranDenda').value = '';
}

function hideFormPembayaranDenda() {
    document.getElementById('formPembayaranDenda').classList.add('hidden');
}

function resetForm() {
    clearAnggota();
}

// Form validation
function validateForm() {
    const selectedPeminjamanId = document.getElementById('selectedPeminjamanId').value;
    
    if (!selectedPeminjamanId) {
        showNotification('Pilih peminjaman terlebih dahulu!', 'error');
        return false;
    }
    
    // Validate kondisi buku
    const kondisiInputs = document.querySelectorAll('select[name^="kondisi_kembali"]');
    let isValid = true;
    
    kondisiInputs.forEach(input => {
        if (!input.value) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showNotification('Pilih kondisi untuk semua buku!', 'error');
        return false;
    }
    
    // Validate pembayaran denda
    const statusPembayaran = document.getElementById('status_pembayaran_denda');
    const tanggalPembayaran = document.getElementById('tanggal_pembayaran_denda');
    const formPembayaranDenda = document.getElementById('formPembayaranDenda');
    
    // Jika form pembayaran denda ditampilkan dan status sudah_dibayar, tanggal harus diisi
    if (!formPembayaranDenda.classList.contains('hidden') && 
        statusPembayaran.value === 'sudah_dibayar' && 
        !tanggalPembayaran.value) {
        showNotification('Tanggal pembayaran harus diisi jika status sudah dibayar!', 'error');
        return false;
    }
    
    return true;
}

// Notification function
function showNotification(message, type = 'info') {
    // You can integrate with your existing notification system
    console.log(`${type.toUpperCase()}: ${message}`);
    
    // Simple alert for now
    if (type === 'error') {
        alert('Error: ' + message);
    }
}
</script>
@endsection
