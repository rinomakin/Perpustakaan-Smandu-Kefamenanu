@extends('layouts.admin')

@section('title', 'Absensi Pengunjung')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">📋 Absensi Pengunjung</h1>
                <p class="text-blue-100 mt-1">Kelola absensi pengunjung perpustakaan dengan pencarian anggota</p>
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
                    <i class="fas fa-search text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Member Search & Visitor Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Left Panel: Member Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-search mr-2 text-blue-600"></i>
                    Pencarian Anggota
                </h2>
                <div class="flex items-center space-x-2">
                    <div id="connection-status" class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600" id="status-text">Siap</span>
                </div>
            </div>

            <!-- Search Form -->
            <div class="space-y-4">
                <!-- Search Input with Scan Button -->
                <div class="flex space-x-2">
                    <div class="flex-1 relative">
                        <input type="text" id="member-search" 
                               placeholder="Cari anggota berdasarkan nama, nomor anggota, atau barcode..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button id="scan-barcode-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-qrcode mr-2"></i>
                        Scan
                    </button>
                </div>

                <!-- Search Results -->
                <div id="search-results" class="hidden">
                    <div class="border border-gray-200 rounded-lg max-h-64 overflow-y-auto">
                        <div id="search-results-list" class="divide-y divide-gray-200">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Selected Member Info -->
                <div id="selected-member" class="hidden p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <img id="selected-photo" src="" alt="Foto" class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <div id="selected-name" class="font-medium text-gray-900"></div>
                            <div id="selected-info" class="text-sm text-gray-600"></div>
                        </div>
                        <button id="record-attendance" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Catat Absensi
                        </button>
                    </div>
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
        </div>

        <!-- Right Panel: Today's Visitors -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    Pengunjung Hari Ini
                </h2>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.absensi-pengunjung.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Manual
                    </a>
                    <button id="refresh-visitors" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-1"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Visitors List -->
            <div id="visitors-container" class="space-y-3 max-h-96 overflow-y-auto">
                @if($absensiHariIni->count() === 0)
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-3"></i>
                        <p>Belum ada pengunjung hari ini</p>
                    </div>
                @else
                    @foreach($absensiHariIni as $absensi)
                        @if(!$absensi->anggota)
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                    <div class="flex-1">
                                        <div class="font-medium text-red-800">Data Absensi Bermasalah</div>
                                        <div class="text-sm text-red-600">Absensi ID: {{ $absensi->id }} - Anggota tidak ditemukan</div>
                                    </div>
                                    <form action="{{ route('admin.absensi-pengunjung.destroy', $absensi->id) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors duration-200" 
                                                title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                    <div class="visitor-item flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200" data-id="{{ $absensi->id }}">
                        <img src="{{ $absensi->anggota && $absensi->anggota->foto ? asset('storage/' . $absensi->anggota->foto) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>') }}" 
                             alt="Foto" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $absensi->anggota ? $absensi->anggota->nama_lengkap : 'Nama Tidak Tersedia' }}</div>
                            <div class="text-sm text-gray-600">
                                {{ $absensi->anggota && $absensi->anggota->kelas ? $absensi->anggota->kelas->nama_kelas : '-' }} | 
                                {{ $absensi->anggota ? $absensi->anggota->nomor_anggota : 'N/A' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $absensi->waktu_masuk->format('H:i') }}</div>
                            <div class="text-xs text-gray-500">{{ $absensi->waktu_masuk->diffForHumans() }}</div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2 ml-3">
                            <a href="{{ route('admin.absensi-pengunjung.show', $absensi->id) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                               title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.absensi-pengunjung.edit', $absensi->id) }}" 
                               class="text-yellow-600 hover:text-yellow-800 transition-colors duration-200" 
                               title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('admin.absensi-pengunjung.destroy', $absensi->id) }}" 
                                  method="POST" 
                                  class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200" 
                                        title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                        @endif
                    @endforeach
                @endif
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

<!-- Scan Modal -->
<div id="scanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Scan Barcode Anggota</h3>
                    <button type="button" id="closeScanModal" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 mb-4">Arahkan kamera ke barcode anggota untuk scan</p>
                    <div id="scanContainer" class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                        <div id="scanPlaceholder" class="text-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
                        </div>
                        <div id="scanVideo" class="w-full h-full hidden">
                            <div id="reader" class="w-full h-full"></div>
                        </div>
                        <div id="scanLoading" class="absolute inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
                            <div class="text-center text-white">
                                <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                                <p>Memulai kamera...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span id="scanStatus">Siap untuk scan</span>
                    </div>
                    <div class="flex space-x-3" id="scanControls">
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

@section('scripts')
<!-- Include ZXing barcode scanning library -->
<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
// Member Search and Barcode Scanner functionality
class MemberSearchScanner {
    constructor() {
        this.isScanning = false;
        this.stream = null;
        this.codeReader = null;
        this.html5QrcodeScanner = null;
        this.lastScanTime = 0;
        this.scanCooldown = 3000; // 3 seconds cooldown between scans
        this.searchTimeout = null;
        this.selectedMember = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.setStatus('ready');
    }

    bindEvents() {
        // Search functionality
        document.getElementById('member-search').addEventListener('input', (e) => this.handleSearch(e.target.value));
        document.getElementById('member-search').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.handleSearch(e.target.value);
        });

        // Scan button
        document.getElementById('scan-barcode-btn').addEventListener('click', () => this.openScanModal());

        // Manual input
        document.getElementById('process-manual').addEventListener('click', () => this.processManualBarcode());
        document.getElementById('manual-barcode').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.processManualBarcode();
        });

        // Record attendance
        document.getElementById('record-attendance').addEventListener('click', () => this.recordAttendance());

        // Other buttons
        document.getElementById('refresh-visitors').addEventListener('click', () => this.refreshVisitors());
        document.getElementById('search-history').addEventListener('click', () => this.searchHistory());

        // Modal controls
        document.getElementById('closeScanModal').addEventListener('click', () => this.closeScanModal());
        document.getElementById('cancelScan').addEventListener('click', () => this.closeScanModal());
        document.getElementById('startScanBtn').addEventListener('click', () => this.startScanning());
        document.getElementById('stopScanBtn').addEventListener('click', () => this.stopScanning());

        // Close modal when clicking outside
        document.getElementById('scanModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                this.closeScanModal();
            }
        });
    }

    // Search functionality
    handleSearch(query) {
        clearTimeout(this.searchTimeout);
        
        if (query.length < 2) {
            this.hideSearchResults();
            return;
        }

        this.searchTimeout = setTimeout(() => {
            this.searchMembers(query);
        }, 300);
    }

    async searchMembers(query) {
        try {
            const response = await fetch(`{{ route('admin.absensi-pengunjung.search-members') }}?q=${encodeURIComponent(query)}`);
            const result = await response.json();

            if (result.success) {
                this.displaySearchResults(result.data);
            } else {
                this.showMessage(result.message, 'error');
            }
        } catch (error) {
            console.error('Error searching members:', error);
            this.showMessage('Terjadi kesalahan saat mencari anggota', 'error');
        }
    }

    displaySearchResults(members) {
        const resultsContainer = document.getElementById('search-results');
        const resultsList = document.getElementById('search-results-list');

        if (members.length === 0) {
            resultsList.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <p>Tidak ada anggota yang ditemukan</p>
                </div>
            `;
        } else {
            resultsList.innerHTML = members.map(member => `
                <div class="p-3 hover:bg-gray-50 cursor-pointer transition-colors duration-200 member-result" 
                     data-member='${JSON.stringify(member)}'>
                    <div class="flex items-center space-x-3">
                        <img src="${member.foto || 'data:image/svg+xml;base64,' + btoa('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>')}" 
                             alt="Foto" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${member.nama_lengkap}</div>
                            <div class="text-sm text-gray-600">
                                ${member.nomor_anggota} | ${member.kelas || '-'}
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            ${member.status === 'aktif' ? '<span class="text-green-600">Aktif</span>' : '<span class="text-red-600">Tidak Aktif</span>'}
                        </div>
                    </div>
                </div>
            `).join('');

            // Add click event listeners
            resultsList.querySelectorAll('.member-result').forEach(element => {
                element.addEventListener('click', () => {
                    const member = JSON.parse(element.dataset.member);
                    this.selectMember(member);
                });
            });
        }

        resultsContainer.classList.remove('hidden');
    }

    selectMember(member) {
        this.selectedMember = member;
        
        // Update selected member display
        document.getElementById('selected-photo').src = member.foto || 'data:image/svg+xml;base64,' + btoa('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>');
        document.getElementById('selected-name').textContent = member.nama_lengkap;
        document.getElementById('selected-info').textContent = `${member.nomor_anggota} | ${member.kelas || '-'}`;
        
        // Show selected member section
        document.getElementById('selected-member').classList.remove('hidden');
        
        // Hide search results
        this.hideSearchResults();
        
        // Clear search input
        document.getElementById('member-search').value = '';
    }

    hideSearchResults() {
        document.getElementById('search-results').classList.add('hidden');
    }

    // Scan functionality
    openScanModal() {
        document.getElementById('scanModal').classList.remove('hidden');
        this.initializeScanner();
    }

    closeScanModal() {
        this.stopScanning();
        document.getElementById('scanModal').classList.add('hidden');
        this.resetScanModal();
    }

    resetScanModal() {
        const scanContainer = document.getElementById('scanContainer');
        const scanPlaceholder = document.getElementById('scanPlaceholder');
        const scanVideo = document.getElementById('scanVideo');
        const scanLoading = document.getElementById('scanLoading');
        
        scanLoading.classList.add('hidden');
        scanPlaceholder.classList.remove('hidden');
        scanVideo.classList.add('hidden');
        
        scanPlaceholder.innerHTML = `
            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
            <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
        `;
        
        document.getElementById('scanStatus').textContent = 'Siap untuk scan';
        document.getElementById('startScanBtn').classList.remove('hidden');
        document.getElementById('stopScanBtn').classList.add('hidden');
    }

    async initializeScanner() {
        console.log('🚀 Memulai inisialisasi scanner...');
        
        const scanContainer = document.getElementById('scanContainer');
        const scanLoading = document.getElementById('scanLoading');
        const scanVideo = document.getElementById('scanVideo');
        const scanPlaceholder = document.getElementById('scanPlaceholder');
        
        // Tampilkan loading
        scanLoading.classList.remove('hidden');
        scanPlaceholder.classList.add('hidden');
        scanVideo.classList.remove('hidden');
        
        try {
            // Periksa apakah browser mendukung getUserMedia
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Browser tidak mendukung akses kamera');
            }
            
            // Minta izin akses kamera terlebih dahulu
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            
            // Hentikan stream sementara
            stream.getTracks().forEach(track => track.stop());
            
            // Buat HTML5-QRCode scanner
            this.html5QrcodeScanner = new Html5Qrcode("reader");
            
            // Konfigurasi scanner
            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                supportedScanTypes: [
                    Html5QrcodeScanType.SCAN_TYPE_CAMERA
                ]
            };
            
            // Mulai scanning
            await this.html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => this.onScanSuccess(decodedText, decodedResult),
                (error) => this.onScanFailure(error)
            );
            
            console.log('📹 Scanner berhasil dimulai');
            scanLoading.classList.add('hidden');
            scanVideo.classList.remove('hidden');
            document.getElementById('scanStatus').textContent = 'Scanner aktif - Arahkan ke barcode';
            document.getElementById('startScanBtn').classList.add('hidden');
            document.getElementById('stopScanBtn').classList.remove('hidden');
            this.showMessage('Scanner siap! Arahkan kamera ke barcode anggota.', 'success');
            
        } catch (error) {
            console.error('❌ Error inisialisasi scanner:', error);
            scanLoading.classList.add('hidden');
            scanPlaceholder.classList.remove('hidden');
            scanVideo.classList.add('hidden');
            
            let errorMessage = 'Gagal menginisialisasi scanner';
            
            if (error.name === 'NotAllowedError') {
                errorMessage = 'Akses kamera ditolak. Silakan klik ikon kamera di address bar dan izinkan akses kamera.';
            } else if (error.name === 'NotFoundError') {
                errorMessage = 'Tidak ada kamera yang ditemukan di perangkat ini.';
            } else if (error.name === 'NotSupportedError') {
                errorMessage = 'Browser tidak mendukung akses kamera. Gunakan browser modern seperti Chrome, Firefox, atau Safari.';
            } else if (error.message.includes('HTTPS')) {
                errorMessage = 'Akses kamera memerlukan koneksi HTTPS. Silakan gunakan server HTTPS.';
            } else {
                errorMessage = 'Gagal menginisialisasi scanner: ' + error.message;
            }
            
            this.showMessage(errorMessage, 'error');
            
            // Tampilkan opsi manual input
            scanPlaceholder.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-yellow-400 mb-2"></i>
                    <p class="text-gray-500 mb-4">Kamera tidak tersedia</p>
                    <p class="text-sm text-gray-400 mb-4">${errorMessage}</p>
                    <button onclick="document.getElementById('manual-barcode').focus()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-keyboard mr-2"></i>
                        Gunakan Input Manual
                    </button>
                </div>
            `;
        }
    }

    onScanSuccess(decodedText, decodedResult) {
        console.log('🎉 Barcode terdeteksi:', decodedText);
        
        // Hentikan scanner untuk mencegah scan berulang
        this.stopScanning();
        
        // Tampilkan pesan sukses
        this.showMessage(`Barcode terdeteksi: ${decodedText}`, 'success');
        
        // Proses barcode
        this.processBarcode(decodedText);
    }

    onScanFailure(error) {
        // Handle scan failure silently untuk menghindari spam log
        // Hanya log jika ada error yang signifikan
        if (error && error.name !== 'NotFoundException') {
            console.log('⚠️ Scan failure:', error);
        }
    }

    startScanning() {
        if (!this.html5QrcodeScanner) {
            this.showMessage('Scanner belum siap. Silakan tunggu.', 'warning');
            return;
        }
        
        try {
            document.getElementById('scanStatus').textContent = 'Scanning aktif - Arahkan ke barcode';
            this.showMessage('Scanner aktif! Arahkan kamera ke barcode anggota.', 'info');
        } catch (error) {
            console.error('Error starting scanner:', error);
            this.showMessage('Gagal memulai scanner. Silakan coba lagi.', 'error');
        }
    }

    stopScanning() {
        if (this.html5QrcodeScanner) {
            try {
                this.html5QrcodeScanner.stop();
                document.getElementById('scanStatus').textContent = 'Scanner dihentikan';
                this.showMessage('Scanner dihentikan.', 'info');
            } catch (error) {
                console.error('Error stopping scanner:', error);
            }
        }
    }

    async processBarcode(barcode) {
        if (!barcode) return;

        this.setStatus('processing');
        this.showMessage('Memproses barcode...', 'info');
        
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
                
                // Tutup modal setelah 2 detik untuk memberikan waktu melihat hasil
                setTimeout(() => {
                    this.closeScanModal();
                }, 2000);
            } else {
                this.showMessage(result.message, 'error');
                // Buka kembali scanner jika ada error
                setTimeout(() => {
                    this.startScanning();
                }, 1000);
            }

        } catch (error) {
            console.error('Error processing barcode:', error);
            this.showMessage('Terjadi kesalahan saat memproses barcode. Silakan coba lagi.', 'error');
            
            // Buka kembali scanner jika ada error
            setTimeout(() => {
                this.startScanning();
            }, 1000);
        }

        this.setStatus('ready');
    }

    processManualBarcode() {
        const barcode = document.getElementById('manual-barcode').value.trim();
        if (barcode) {
            this.showMessage('Memproses barcode manual...', 'info');
            this.processBarcode(barcode);
            document.getElementById('manual-barcode').value = '';
        } else {
            this.showMessage('Silakan masukkan barcode terlebih dahulu', 'warning');
            document.getElementById('manual-barcode').focus();
        }
    }

    async recordAttendance() {
        if (!this.selectedMember) {
            this.showMessage('Pilih anggota terlebih dahulu', 'warning');
            return;
        }

        try {
            const response = await fetch('{{ route("admin.absensi-pengunjung.store-ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    anggota_id: this.selectedMember.id,
                    keterangan: 'Pencarian Manual'
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage(result.message, 'success');
                this.refreshVisitors();
                this.clearSelectedMember();
            } else {
                this.showMessage(result.message, 'error');
            }

        } catch (error) {
            console.error('Error recording attendance:', error);
            this.showMessage('Terjadi kesalahan saat mencatat absensi', 'error');
        }
    }

    clearSelectedMember() {
        this.selectedMember = null;
        document.getElementById('selected-member').classList.add('hidden');
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
                    <div class="font-medium text-gray-900">${visitor.nama_lengkap || 'Nama Tidak Tersedia'}</div>
                    <div class="text-sm text-gray-600">
                        ${visitor.kelas || '-'} | ${visitor.nomor_anggota || 'N/A'}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">${visitor.waktu_masuk}</div>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center space-x-2 ml-3">
                    <a href="{{ route('admin.absensi-pengunjung.show', '') }}/${visitor.id}" 
                       class="text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                       title="Lihat Detail">
                        <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('admin.absensi-pengunjung.edit', '') }}/${visitor.id}" 
                       class="text-yellow-600 hover:text-yellow-800 transition-colors duration-200" 
                       title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    <form action="{{ route('admin.absensi-pengunjung.destroy', '') }}/${visitor.id}" 
                          method="POST" 
                          class="inline" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 transition-colors duration-200" 
                                title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
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
            case 'ready':
                statusElement.className = 'w-3 h-3 bg-green-500 rounded-full';
                textElement.textContent = 'Siap';
                scannerStatus.textContent = 'Siap';
                scannerStatus.className = 'text-lg font-semibold text-green-600';
                break;
            case 'processing':
                statusElement.className = 'w-3 h-3 bg-blue-500 rounded-full animate-pulse';
                textElement.textContent = 'Memproses...';
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
                statusElement.className = 'w-3 h-3 bg-green-500 rounded-full';
                textElement.textContent = 'Siap';
                scannerStatus.textContent = 'Siap';
                scannerStatus.className = 'text-lg font-semibold text-green-600';
        }
    }

    showMessage(message, type) {
        const container = document.getElementById('message-container');
        let alertClass;
        let icon;
        
        switch (type) {
            case 'success':
                alertClass = 'bg-green-500';
                icon = 'fas fa-check-circle';
                break;
            case 'info':
                alertClass = 'bg-blue-500';
                icon = 'fas fa-info-circle';
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
        
        container.appendChild(messageDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
}

// Initialize scanner when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.memberSearchScanner = new MemberSearchScanner();
    
    // Set default dates (today)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filter-start-date').value = today;
    document.getElementById('filter-end-date').value = today;
});

// Cleanup when page is unloaded
window.addEventListener('beforeunload', function() {
    if (window.memberSearchScanner) {
        window.memberSearchScanner.stopScanning();
    }
});
</script>
@endsection
