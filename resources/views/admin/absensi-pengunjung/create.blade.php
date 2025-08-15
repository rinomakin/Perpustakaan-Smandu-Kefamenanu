@extends('layouts.admin')

@section('title', 'Tambah Pengunjung')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-blue-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">➕ Tambah Pengunjung</h1>
                <p class="text-green-100 mt-1">Catat kunjungan anggota perpustakaan</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ $totalPengunjungHariIni ?? 0 }}</div>
                <div class="text-sm text-green-100">Pengunjung Hari Ini</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-search mr-2 text-blue-600"></i>
                Form Pencarian & Absensi Anggota
            </h2>
            <div class="flex items-center space-x-2">
                <div id="connection-status" class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600" id="status-text">Siap</span>
            </div>
        </div>

        <!-- Search & Scan Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="search-member" class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                <div class="flex">
                    <input type="text" id="search-member" 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Masukkan nama atau nomor anggota...">
                    <button type="button" id="search-btn" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-r-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Scan Barcode</label>
                <div class="flex">
                    <input type="text" id="barcode-input" 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                           placeholder="Scan barcode atau masukkan nomor anggota...">
                    <button type="button" id="scan-btn" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-r-lg font-medium transition-colors duration-200">
                        <i class="fas fa-barcode"></i> Scan
                    </button>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="mb-6" style="display: none;">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">Hasil Pencarian:</h5>
            <div id="members-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
        </div>

        <!-- Auto-Loaded Member Form -->
        <div id="member-form" class="mb-6" style="display: none;">
            <!-- Member Data Card -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Data Anggota</h5>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-1 text-center">
                        <img id="member-photo" src="" alt="Foto Anggota" 
                             class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-white shadow-lg">
                    </div>
                    <div class="md:col-span-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" id="member-name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Anggota</label>
                                <input type="text" id="member-number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <input type="text" id="member-class" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                                <input type="text" id="member-major" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Form -->
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Form Absensi</h5>
                <form id="attendance-form">
                    <input type="hidden" id="anggota-id" name="anggota_id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="waktu-masuk" class="block text-sm font-medium text-gray-700 mb-2">Waktu Masuk</label>
                            <input type="datetime-local" id="waktu-masuk" name="waktu_masuk" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                        <div>
                            <label for="tujuan-kunjungan" class="block text-sm font-medium text-gray-700 mb-2">Tujuan Berkunjung</label>
                            <select id="tujuan-kunjungan" name="tujuan_kunjungan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih tujuan kunjungan</option>
                                <option value="1">1 - Membaca Buku</option>
                                <option value="2">2 - Meminjam Buku</option>
                                <option value="3">3 - Mengembalikan Buku</option>
                                <option value="4">4 - Belajar/Kerja Kelompok</option>
                                <option value="5">5 - Konsultasi dengan Petugas</option>
                                <option value="6">6 - Menggunakan Komputer/Internet</option>
                                <option value="7">7 - Mengikuti Kegiatan Perpustakaan</option>
                                <option value="8">8 - Lainnya</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea id="keterangan" name="keterangan" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      placeholder="Catatan tambahan..."></textarea>
                        </div>
                    </div>
                    <div class="flex space-x-3 mt-6">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i> Catat Absensi
                        </button>
                        <button type="button" id="reset-form" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('admin.absensi-pengunjung.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
        <div class="text-sm text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Gunakan pencarian atau scan barcode untuk mencatat absensi
        </div>
    </div>
</div>

<!-- Scan Modal -->
<div id="scanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-white">Scan Barcode Anggota</h3>
                    <button type="button" id="closeScanModal" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 mb-4">Arahkan kamera ke barcode anggota untuk scan</p>
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

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// Wait for jQuery to be available
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    // HTML5-QRCode Scanner
    let html5QrcodeScanner = null;

    class MemberSearchScanner {
        constructor() {
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.setupCSRF();
        }

        setupCSRF() {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        setupEventListeners() {
            jQuery('#search-btn').on('click', () => this.searchMembers());
            jQuery('#search-member').on('keypress', (e) => {
                if (e.which === 13) this.searchMembers();
            });
            jQuery('#scan-btn').on('click', () => this.openScanModal());
            jQuery('#barcode-input').on('keypress', (e) => {
                if (e.which === 13) this.scanBarcode();
            });
            jQuery('#attendance-form').on('submit', (e) => this.submitAttendance(e));
            jQuery('#reset-form').on('click', () => this.resetForm());

            // Scanner modal events
            document.getElementById('closeScanModal').addEventListener('click', () => this.closeScanModal());
            document.getElementById('cancelScan').addEventListener('click', () => this.closeScanModal());
            document.getElementById('startScanBtn').addEventListener('click', () => this.startScanning());
            document.getElementById('stopScanBtn').addEventListener('click', () => this.stopScanning());

            // Close modal when clicking outside
            document.getElementById('scanModal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('scanModal')) {
                    this.closeScanModal();
                }
            });
        }

        searchMembers() {
            const query = jQuery('#search-member').val().trim();
            
            if (query.length < 2) {
                this.showMessage('Minimal 2 karakter untuk pencarian', 'warning');
                return;
            }

            jQuery.ajax({
                url: '{{ route("admin.absensi-pengunjung.search-members") }}',
                method: 'GET',
                data: { q: query },
                success: (response) => {
                    if (response.success) {
                        this.displaySearchResults(response.data);
                    } else {
                        this.showMessage(response.message, 'error');
                    }
                },
                error: (xhr) => {
                    this.showMessage('Terjadi kesalahan saat mencari anggota', 'error');
                }
            });
        }

        displaySearchResults(members) {
            const container = jQuery('#members-list');
            container.empty();

            if (members.length === 0) {
                container.html(`
                    <div class="col-span-full">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <i class="fas fa-info-circle text-blue-600 text-xl mb-2"></i>
                            <p class="text-blue-800">Tidak ada anggota ditemukan</p>
                        </div>
                    </div>
                `);
            } else {
                members.forEach(member => {
                    const memberCard = `
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow duration-200 member-card" data-member='${JSON.stringify(member)}'>
                            <div class="text-center">
                                <img src="${member.foto || '/images/default-avatar.png'}" 
                                     alt="Foto ${member.nama_lengkap}" 
                                     class="w-20 h-20 rounded-full object-cover mx-auto mb-3 border-2 border-gray-200">
                                <h6 class="font-semibold text-gray-800 mb-2">${member.nama_lengkap}</h6>
                                <p class="text-sm text-gray-600 mb-3">
                                    ${member.nomor_anggota}<br>
                                    ${member.kelas} - ${member.jurusan}
                                </p>
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 select-member">
                                    <i class="fas fa-check mr-1"></i> Pilih
                                </button>
                            </div>
                        </div>
                    `;
                    container.append(memberCard);
                });

                jQuery('.select-member').on('click', (e) => {
                    const card = jQuery(e.target).closest('.member-card');
                    const memberData = JSON.parse(card.data('member'));
                    this.loadMemberData(memberData);
                    jQuery('#search-results').hide();
                });
            }

            jQuery('#search-results').show();
        }

        scanBarcode() {
            const barcode = jQuery('#barcode-input').val().trim();
            
            if (!barcode) {
                this.showMessage('Masukkan barcode atau nomor anggota', 'warning');
                return;
            }

            jQuery.ajax({
                url: '{{ route("admin.absensi-pengunjung.scan-barcode") }}',
                method: 'POST',
                data: { barcode: barcode },
                success: (response) => {
                    if (response.success) {
                        this.loadMemberData(response.data);
                        jQuery('#barcode-input').val('');
                        this.showMessage(response.message, 'success');
                    } else {
                        this.showMessage(response.message, 'error');
                    }
                },
                error: (xhr) => {
                    this.showMessage('Terjadi kesalahan saat memproses barcode', 'error');
                }
            });
        }

        openScanModal() {
            document.getElementById('scanModal').classList.remove('hidden');
            this.initializeHTML5QRCodeScanner();
        }

        initializeHTML5QRCodeScanner() {
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
                    (decodedText, decodedResult) => {
                        console.log('🎉 Barcode detected:', decodedText);
                        this.onBarcodeDetected(decodedText);
                    },
                    (error) => {
                        // Handle scan failure silently
                        console.log('⚠️ Scan failure:', error);
                    }
                ).then(() => {
                    console.log('📹 Scanner started successfully');
                    scannerLoading.classList.add('hidden');
                    scannerVideo.classList.remove('hidden');
                    document.getElementById('scannerStatus').textContent = 'Scanner aktif';
                    document.getElementById('startScanBtn').classList.add('hidden');
                    document.getElementById('stopScanBtn').classList.remove('hidden');
                    this.showMessage('Scanner HTML5-QRCode siap. Arahkan kamera ke barcode.', 'success');
                }).catch((err) => {
                    console.error('❌ Scanner initialization error:', err);
                    scannerLoading.classList.add('hidden');
                    scannerPlaceholder.classList.remove('hidden');
                    scannerVideo.classList.add('hidden');
                    
                    if (err.name === 'NotAllowedError') {
                        this.showMessage('Akses kamera ditolak. Silakan izinkan akses kamera di browser.', 'error');
                    } else if (err.name === 'NotFoundError') {
                        this.showMessage('Tidak ada kamera yang ditemukan.', 'error');
                    } else {
                        this.showMessage('Gagal menginisialisasi scanner: ' + err.message, 'error');
                    }
                });
                
            } catch (error) {
                console.error('❌ HTML5-QRCode initialization error:', error);
                scannerLoading.classList.add('hidden');
                scannerPlaceholder.classList.remove('hidden');
                scannerVideo.classList.add('hidden');
                this.showMessage('Gagal menginisialisasi scanner: ' + error.message, 'error');
            }
        }

        onBarcodeDetected(decodedText) {
            // Stop the scanner
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    console.log('Scanner stopped after successful scan');
                }).catch((error) => {
                    console.error('Error stopping scanner:', error);
                });
            }

            // Update UI
            jQuery('#barcode-input').val(decodedText);
            this.closeScanModal();
            this.scanBarcode();
        }

        startScanning() {
            if (!html5QrcodeScanner) {
                this.showMessage('Scanner belum siap. Silakan tunggu.', 'warning');
                return;
            }
            
            try {
                document.getElementById('scannerStatus').textContent = 'Scanning...';
            } catch (error) {
                console.error('Error starting scanner:', error);
                this.showMessage('Gagal memulai scanner. Silakan coba lagi.', 'error');
            }
        }

        stopScanning() {
            if (html5QrcodeScanner) {
                try {
                    html5QrcodeScanner.stop();
                    document.getElementById('scannerStatus').textContent = 'Scanner dihentikan';
                } catch (error) {
                    console.error('Error stopping scanner:', error);
                }
            }
        }

        closeScanModal() {
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
            
            document.getElementById('scanModal').classList.add('hidden');
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
        }

        loadMemberData(memberData) {
            jQuery('#anggota-id').val(memberData.id);
            jQuery('#member-name').val(memberData.nama_lengkap);
            jQuery('#member-number').val(memberData.nomor_anggota);
            jQuery('#member-class').val(memberData.kelas);
            jQuery('#member-major').val(memberData.jurusan);
            
            if (memberData.foto) {
                jQuery('#member-photo').attr('src', memberData.foto);
            } else {
                jQuery('#member-photo').attr('src', '/images/default-avatar.png');
            }

            jQuery('#member-form').show();
            jQuery('html, body').animate({
                scrollTop: jQuery('#member-form').offset().top - 100
            }, 500);
        }

        submitAttendance(e) {
            e.preventDefault();
            
            const anggotaId = jQuery('#anggota-id').val();
            const tujuanKunjungan = jQuery('#tujuan-kunjungan').val();
            
            if (!anggotaId) {
                this.showMessage('Pilih anggota terlebih dahulu', 'warning');
                return;
            }
            
            if (!tujuanKunjungan) {
                this.showMessage('Pilih tujuan kunjungan', 'warning');
                jQuery('#tujuan-kunjungan').focus();
                return;
            }
            
            const formData = {
                anggota_id: anggotaId,
                waktu_masuk: jQuery('#waktu-masuk').val(),
                tujuan_kunjungan: tujuanKunjungan,
                keterangan: jQuery('#keterangan').val()
            };

            jQuery.ajax({
                url: '{{ route("admin.absensi-pengunjung.store-ajax") }}',
                method: 'POST',
                data: formData,
                success: (response) => {
                    if (response.success) {
                        this.showMessage(response.message, 'success');
                        this.resetForm();
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.absensi-pengunjung.index") }}';
                        }, 2000);
                    } else {
                        this.showMessage(response.message, 'error');
                    }
                },
                error: (xhr) => {
                    this.showMessage('Terjadi kesalahan saat mencatat absensi', 'error');
                }
            });
        }

        resetForm() {
            jQuery('#member-form').hide();
            jQuery('#search-results').hide();
            jQuery('#search-member').val('');
            jQuery('#barcode-input').val('');
            jQuery('#attendance-form')[0].reset();
            jQuery('#waktu-masuk').val('{{ now()->format("Y-m-d\TH:i") }}');
            jQuery('#tujuan-kunjungan').val(''); // Reset tujuan kunjungan
        }

        showMessage(message, type) {
            const container = jQuery('#message-container');
            let alertClass;
            let icon;
            
            switch (type) {
                case 'success':
                    alertClass = 'bg-green-500';
                    icon = 'fas fa-check-circle';
                    break;
                case 'warning':
                    alertClass = 'bg-yellow-500';
                    icon = 'fas fa-exclamation-triangle';
                    break;
                default:
                    alertClass = 'bg-red-500';
                    icon = 'fas fa-times-circle';
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `${alertClass} text-white px-4 py-3 rounded-lg shadow-lg mb-2 transform transition-all duration-300 z-50`;
            messageDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${icon} mr-2"></i>
                        <span>${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.append(messageDiv);
            
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }

    // Initialize the scanner
    window.memberSearchScanner = new MemberSearchScanner();
});
</script>
@endpush
