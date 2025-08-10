@extends('layouts.admin')

@section('title', 'Absensi Pengunjung')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">📋 Absensi Pengunjung</h1>
                <p class="text-blue-100 mt-1">Kelola absensi pengunjung perpustakaan dengan scan barcode</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ $totalPengunjungHariIni }}</div>
                <div class="text-sm text-blue-100">Pengunjung Hari Ini</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600 uppercase">Hari Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPengunjungHariIni }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600 uppercase">Bulan Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPengunjungBulanIni }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-600 uppercase">Status Scanner</h3>
                    <p class="text-lg font-semibold text-green-600" id="scanner-status">Siap</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-qrcode text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Scanner & Visitor Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Left Panel: Barcode Scanner -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-camera mr-2 text-blue-600"></i>
                    Barcode Scanner
                </h2>
                <div class="flex items-center space-x-2">
                    <div id="connection-status" class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600" id="status-text">Disconnected</span>
                </div>
            </div>

            <!-- Camera Preview -->
            <div class="relative mb-4">
                <div id="camera-container" class="bg-gray-100 rounded-lg overflow-hidden relative" style="height: 300px;">
                    <video id="camera-preview" class="w-full h-full object-cover hidden"></video>
                    <canvas id="camera-canvas" class="hidden"></canvas>
                    
                    <!-- Placeholder ketika camera off -->
                    <div id="camera-placeholder" class="flex items-center justify-center h-full bg-gray-50">
                        <div class="text-center">
                            <i class="fas fa-video text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600">Klik "Aktifkan Kamera" untuk memulai scan</p>
                        </div>
                    </div>

                    <!-- Scanning Frame -->
                    <div id="scan-frame" class="absolute inset-0 pointer-events-none hidden">
                        <div class="w-full h-full relative">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-red-500 rounded-lg">
                                <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-red-500"></div>
                                <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-red-500"></div>
                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-red-500"></div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-red-500"></div>
                            </div>
                            <!-- Scanning Line Animation -->
                            <div id="scan-line" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-0.5 bg-red-500 opacity-75 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Camera Controls -->
            <div class="flex space-x-3 mb-4">
                <button id="start-camera" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-play mr-2"></i>
                    Aktifkan Kamera
                </button>
                <button id="stop-camera" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 hidden">
                    <i class="fas fa-stop mr-2"></i>
                    Matikan Kamera
                </button>
            </div>

            <!-- Manual Input -->
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Input Manual Barcode:</label>
                <div class="flex space-x-2">
                    <input type="text" id="manual-barcode" placeholder="Masukkan barcode anggota..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="process-manual" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>

            <!-- Last Scan Result -->
            <div id="scan-result" class="mt-4 p-3 rounded-lg hidden">
                <div class="flex items-center space-x-3">
                    <img id="result-photo" src="" alt="Foto" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <div id="result-name" class="font-medium text-gray-900"></div>
                        <div id="result-class" class="text-sm text-gray-600"></div>
                        <div id="result-time" class="text-xs text-gray-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Today's Visitors -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    Pengunjung Hari Ini
                </h2>
                <button id="refresh-visitors" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Refresh
                </button>
            </div>

            <!-- Visitors List -->
            <div id="visitors-container" class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($absensiHariIni as $absensi)
                    <div class="visitor-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200" data-id="{{ $absensi->id }}">
                        <img src="{{ $absensi->anggota->foto ? asset('storage/' . $absensi->anggota->foto) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>') }}" 
                             alt="Foto" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $absensi->anggota->nama_lengkap }}</div>
                            <div class="text-sm text-gray-600">
                                {{ $absensi->anggota->kelas ? $absensi->anggota->kelas->nama_kelas : '-' }} | 
                                {{ $absensi->anggota->nomor_anggota }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $absensi->waktu_masuk->format('H:i') }}</div>
                            <div class="text-xs text-gray-500">{{ $absensi->waktu_masuk->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-3"></i>
                        <p>Belum ada pengunjung hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- History Search Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-purple-600"></i>
                Riwayat Kunjungan
            </h2>
        </div>

        <!-- Search Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="filter-start-date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" id="filter-end-date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Anggota</label>
                <input type="text" id="filter-member" placeholder="Nama atau nomor anggota..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button id="search-history" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </div>
        </div>

        <!-- History Results -->
        <div id="history-results" class="hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
            <div id="history-pagination" class="mt-4 flex justify-center">
                <!-- Pagination will be added via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>
@endsection

@section('scripts')
<!-- Include ZXing barcode scanning library -->
<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>

<script>
// Camera and Barcode Scanner functionality
class BarcodeScanner {
    constructor() {
        this.isScanning = false;
        this.stream = null;
        this.codeReader = null;
        this.lastScanTime = 0;
        this.scanCooldown = 3000; // 3 seconds cooldown between scans
        this.init();
    }

    init() {
        this.bindEvents();
        this.setStatus('disconnected');
    }

    bindEvents() {
        document.getElementById('start-camera').addEventListener('click', () => this.startCamera());
        document.getElementById('stop-camera').addEventListener('click', () => this.stopCamera());
        document.getElementById('process-manual').addEventListener('click', () => this.processManualBarcode());
        document.getElementById('manual-barcode').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.processManualBarcode();
        });
        document.getElementById('refresh-visitors').addEventListener('click', () => this.refreshVisitors());
        document.getElementById('search-history').addEventListener('click', () => this.searchHistory());
    }

    async startCamera() {
        const videoElement = document.getElementById('camera-preview');
        const scannerLoading = document.getElementById('scan-frame');
        const scannerPlaceholder = document.getElementById('camera-placeholder');
        
        try {
            this.setStatus('connecting');
            console.log('Setting up reliable camera scanner...');
            
            // Check if getUserMedia is supported
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                console.error('getUserMedia not supported');
                this.showMessage('Browser tidak mendukung akses kamera', 'error');
                this.setStatus('error');
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
            
            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
            console.log('Camera access granted!');
            
            // Set the video stream
            videoElement.srcObject = this.stream;
            await videoElement.play();
            
            // Show camera elements
            scannerPlaceholder.classList.add('hidden');
            videoElement.classList.remove('hidden');
            scannerLoading.classList.remove('hidden');
            document.getElementById('start-camera').classList.add('hidden');
            document.getElementById('stop-camera').classList.remove('hidden');

            this.setStatus('connected');
            await this.startZXingScanning();

        } catch (error) {
            console.error('Error accessing camera:', error);
            this.handleCameraError(error);
        }
    }

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }

        if (this.codeReader) {
            this.codeReader.reset();
            this.codeReader = null;
        }

        // Hide camera elements
        document.getElementById('camera-preview').classList.add('hidden');
        document.getElementById('scan-frame').classList.add('hidden');
        document.getElementById('camera-placeholder').classList.remove('hidden');
        document.getElementById('start-camera').classList.remove('hidden');
        document.getElementById('stop-camera').classList.add('hidden');

        this.isScanning = false;
        this.setStatus('disconnected');
    }

    async startZXingScanning() {
        try {
            // Load ZXing library
            if (typeof ZXing === 'undefined') {
                console.error('ZXing library not loaded');
                this.showMessage('Library barcode scanner tidak tersedia', 'error');
                return;
            }

            const videoElement = document.getElementById('camera-preview');
            
            // Initialize ZXing reader
            this.codeReader = new ZXing.BrowserMultiFormatReader();
            
            console.log('ZXing scanner initialized, starting detection...');
            this.isScanning = true;
            
            // Start continuous scanning with throttling
            await this.codeReader.decodeFromVideoDevice(null, videoElement, (result, error) => {
                if (result && this.isScanning) {
                    const currentTime = Date.now();
                    if (currentTime - this.lastScanTime > this.scanCooldown) {
                        console.log('🎉 Barcode detected:', result.text);
                        this.lastScanTime = currentTime;
                        this.processBarcode(result.text);
                    }
                }
                
                if (error && error.name !== 'NotFoundException') {
                    console.warn('Scanner error:', error);
                }
            });
            
            console.log('ZXing scanner started successfully!');
            
        } catch (error) {
            console.error('Error starting ZXing scanner:', error);
            this.showMessage('Gagal memulai scanner: ' + error.message, 'error');
            this.setStatus('error');
        }
    }

    handleCameraError(error) {
        console.error('Camera error details:', error);
        
        this.showDetailedError(error);
        this.setStatus('error');
        
        // Show manual input hint
        setTimeout(() => {
            this.showMessage('Anda dapat menggunakan input manual di bawah untuk scan barcode', 'info');
            document.getElementById('manual-barcode').focus();
        }, 2000);
    }



    async processBarcode(barcode) {
        if (!barcode) return;

        this.setStatus('processing');
        
        try {
            const response = await fetch('{{ route("admin.absensi-pengunjung.scan-barcode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ barcode: barcode })
            });

            const result = await response.json();

            if (result.success) {
                this.showScanResult(result.data);
                this.showMessage(result.message, 'success');
                this.refreshVisitors();
            } else {
                this.showMessage(result.message, 'error');
            }

        } catch (error) {
            console.error('Error processing barcode:', error);
            this.showMessage('Terjadi kesalahan saat memproses barcode', 'error');
        }

        this.setStatus('connected');
    }

    processManualBarcode() {
        const barcode = document.getElementById('manual-barcode').value.trim();
        if (barcode) {
            this.processBarcode(barcode);
            document.getElementById('manual-barcode').value = '';
        }
    }

    showScanResult(data) {
        const resultDiv = document.getElementById('scan-result');
        const photo = document.getElementById('result-photo');
        const name = document.getElementById('result-name');
        const classInfo = document.getElementById('result-class');
        const time = document.getElementById('result-time');

        photo.src = data.foto || 'data:image/svg+xml;base64,' + btoa('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>');
        name.textContent = data.nama_lengkap;
        classInfo.textContent = `${data.kelas} | ${data.nomor_anggota}`;
        time.textContent = `Waktu masuk: ${data.waktu_masuk}`;

        resultDiv.classList.remove('hidden', 'bg-red-50', 'bg-green-50');
        resultDiv.classList.add('bg-green-50');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            resultDiv.classList.add('hidden');
        }, 5000);
    }

    async refreshVisitors() {
        try {
            const response = await fetch('{{ route("admin.absensi-pengunjung.today") }}');
            const result = await response.json();

            if (result.success) {
                this.updateVisitorsList(result.data);
            }
        } catch (error) {
            console.error('Error refreshing visitors:', error);
        }
    }

    updateVisitorsList(visitors) {
        const container = document.getElementById('visitors-container');
        
        if (visitors.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users text-4xl mb-3"></i>
                    <p>Belum ada pengunjung hari ini</p>
                </div>
            `;
            return;
        }

        container.innerHTML = visitors.map(visitor => `
            <div class="visitor-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200" data-id="${visitor.id}">
                <img src="${visitor.foto || 'data:image/svg+xml;base64,' + btoa('<svg xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"40\\" height=\\"40\\" viewBox=\\"0 0 40 40\\"><rect width=\\"40\\" height=\\"40\\" fill=\\"#e5e7eb\\"/><text x=\\"20\\" y=\\"24\\" text-anchor=\\"middle\\" fill=\\"#9ca3af\\" font-family=\\"Arial\\" font-size=\\"14\\">👤</text></svg>')}" 
                     alt="Foto" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1">
                    <div class="font-medium text-gray-900">${visitor.nama_lengkap}</div>
                    <div class="text-sm text-gray-600">
                        ${visitor.kelas} | ${visitor.nomor_anggota}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">${visitor.waktu_masuk}</div>
                </div>
            </div>
        `).join('');
    }

    async searchHistory() {
        const startDate = document.getElementById('filter-start-date').value;
        const endDate = document.getElementById('filter-end-date').value;
        const member = document.getElementById('filter-member').value;

        const params = new URLSearchParams();
        if (startDate) params.append('tanggal_mulai', startDate);
        if (endDate) params.append('tanggal_selesai', endDate);
        if (member) params.append('anggota', member);

        try {
            const response = await fetch(`{{ route("admin.absensi-pengunjung.history.search") }}?${params}`);
            const result = await response.json();

            if (result.success) {
                this.displayHistory(result.data);
                document.getElementById('history-results').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error searching history:', error);
            this.showMessage('Terjadi kesalahan saat mencari riwayat', 'error');
        }
    }

    displayHistory(data) {
        const tbody = document.getElementById('history-table-body');
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.map(item => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900">${item.nama_lengkap}</div>
                    <div class="text-sm text-gray-500">${item.nomor_anggota}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.kelas}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.waktu_masuk}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${item.keterangan || '-'}
                </td>
            </tr>
        `).join('');
    }

    setStatus(status) {
        const statusElement = document.getElementById('connection-status');
        const textElement = document.getElementById('status-text');
        const scannerStatus = document.getElementById('scanner-status');

        switch (status) {
            case 'connected':
                statusElement.className = 'w-3 h-3 bg-green-500 rounded-full';
                textElement.textContent = 'Connected';
                scannerStatus.textContent = 'Aktif';
                scannerStatus.className = 'text-lg font-semibold text-green-600';
                break;
            case 'connecting':
                statusElement.className = 'w-3 h-3 bg-yellow-500 rounded-full animate-pulse';
                textElement.textContent = 'Connecting...';
                scannerStatus.textContent = 'Menghubungkan...';
                scannerStatus.className = 'text-lg font-semibold text-yellow-600';
                break;
            case 'processing':
                statusElement.className = 'w-3 h-3 bg-blue-500 rounded-full animate-pulse';
                textElement.textContent = 'Processing...';
                scannerStatus.textContent = 'Memproses...';
                scannerStatus.className = 'text-lg font-semibold text-blue-600';
                break;
            case 'error':
                statusElement.className = 'w-3 h-3 bg-red-500 rounded-full';
                textElement.textContent = 'Error';
                scannerStatus.textContent = 'Error';
                scannerStatus.className = 'text-lg font-semibold text-red-600';
                break;
            default:
                statusElement.className = 'w-3 h-3 bg-red-500 rounded-full';
                textElement.textContent = 'Disconnected';
                scannerStatus.textContent = 'Siap';
                scannerStatus.className = 'text-lg font-semibold text-gray-600';
        }
    }

    showMessage(message, type) {
        const container = document.getElementById('message-container');
        let alertClass;
        
        switch (type) {
            case 'success':
                alertClass = 'bg-green-500';
                break;
            case 'info':
                alertClass = 'bg-blue-500';
                break;
            case 'warning':
                alertClass = 'bg-yellow-500';
                break;
            default:
                alertClass = 'bg-red-500';
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `${alertClass} text-white px-4 py-3 rounded-lg shadow-lg mb-2 transform transition-all duration-300`;
        messageDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        container.appendChild(messageDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }

    // Add permission request helper
    async requestCameraPermission() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (error) {
            console.error('Camera permission denied:', error);
            return false;
        }
    }

    // Add device detection
    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Enhanced error handling
    showDetailedError(error) {
        let helpText = '';
        
        switch (error.name) {
            case 'NotAllowedError':
                helpText = `
                    <div class="mt-2 text-sm">
                        <p><strong>Cara mengatasi:</strong></p>
                        <ol class="list-decimal list-inside mt-1 space-y-1">
                            <li>Klik ikon kamera di address bar</li>
                            <li>Pilih "Always allow" atau "Allow"</li>
                            <li>Refresh halaman dan coba lagi</li>
                        </ol>
                    </div>
                `;
                break;
            case 'NotFoundError':
                helpText = `
                    <div class="mt-2 text-sm">
                        <p><strong>Solusi:</strong></p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Pastikan kamera terhubung dengan baik</li>
                            <li>Gunakan input manual barcode di bawah</li>
                        </ul>
                    </div>
                `;
                break;
        }
        
        this.showMessage(this.getErrorMessage(error) + helpText, 'error');
    }

    getErrorMessage(error) {
        switch (error.name) {
            case 'NotAllowedError':
                return 'Akses kamera ditolak. Mohon berikan izin akses kamera.';
            case 'NotFoundError':
                return 'Kamera tidak ditemukan pada device ini.';
            case 'NotSupportedError':
                return 'Browser tidak mendukung akses kamera.';
            case 'NotReadableError':
                return 'Kamera sedang digunakan oleh aplikasi lain.';
            case 'OverconstrainedError':
                return 'Kamera tidak mendukung resolusi yang diminta.';
            default:
                return 'Error: ' + error.message;
        }
    }
}

// Initialize scanner when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.barcodeScanner = new BarcodeScanner();
    
    // Set default dates (today)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filter-start-date').value = today;
    document.getElementById('filter-end-date').value = today;
});

// Cleanup when page is unloaded
window.addEventListener('beforeunload', function() {
    if (window.barcodeScanner) {
        window.barcodeScanner.stopCamera();
    }
});
</script>
@endsection
