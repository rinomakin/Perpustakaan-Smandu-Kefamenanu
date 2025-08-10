<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Pengunjung - SIPERPUS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Include HTML5-QRCode -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">SIPERPUS</h1>
                        <p class="text-blue-100 text-sm">Sistem Perpustakaan</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('frontend.home') }}" class="text-white hover:text-blue-200 font-medium">Beranda</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Tentag</a>
                    <a href="{{ route('petugas.absensi-pengunjung.index') }}" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 font-medium">Absen Pengunjung</a>
                    <a href="{{ route('frontend.koleksi') }}" class="text-white hover:text-blue-200 font-medium">Koleksi Buku</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Title -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-white">Absen Pengunjung</h1>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- QR Scanner -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-black text-white px-4 py-3 rounded-t-lg flex items-center">
                    <i class="fas fa-qrcode mr-2"></i>
                    <span class="font-medium">Scan QR Code Disini</span>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-camera text-gray-400 text-4xl"></i>
                        </div>
                        <p class="text-gray-600 mb-4">Klik tombol di bawah untuk memulai scan QR Code</p>
                    </div>
                    <div class="flex space-x-4">
                        <button id="openScannerBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-qrcode mr-2"></i>Buka Scanner
                        </button>
                        <button id="manualInputBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-keyboard mr-2"></i>Input Manual
                        </button>
                    </div>
                </div>
            </div>

            <!-- Visitor Data -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-black text-white px-4 py-3 rounded-t-lg flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    <span class="font-medium">Data Pengunjung Hari Ini</span>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Show</span>
                            <select class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span class="text-sm text-gray-600">entries</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Search:</span>
                            <input type="text" id="searchInput" class="border border-gray-300 rounded px-3 py-1 text-sm" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Kunjungan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($absensiHariIni as $index => $absensi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($absensi->anggota->nama_lengkap, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $absensi->anggota->nama_lengkap }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $absensi->anggota->nomor_anggota }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absensi->anggota->kelas->nama_kelas ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absensi->waktu_masuk->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('petugas.absensi-pengunjung.destroy', $absensi->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-4"></i>
                                        <p>No data available in table</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-700">
                            Showing {{ $absensiHariIni->count() }} to {{ $absensiHariIni->count() }} of {{ $absensiHariIni->count() }} entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Attendance -->
        <div class="mt-8 bg-white rounded-lg shadow-md">
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span class="font-medium">+ Absen Secara Manual</span>
            </div>
            <div class="p-6">
                <form action="{{ route('petugas.absensi-pengunjung.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggota</label>
                            <select name="anggota_id" id="anggota_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">Pilih anggota...</option>
                                @foreach(\App\Models\Anggota::where('status', 'aktif')->get() as $anggota)
                                <option value="{{ $anggota->id }}">
                                    {{ $anggota->nomor_anggota }} - {{ $anggota->nama_lengkap }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Keterangan...">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Catat Absensi
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-white"></i>
                </div>
                <span class="text-xl font-bold">SIPERPUS</span>
            </div>
            <p class="text-gray-400">&copy; {{ date('Y') }} Sistem Perpustakaan SMAN 1 Kefamenanu.</p>
        </div>
    </footer>

    <!-- QR Scanner Modal -->
    <div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Scan QR Code Absensi</h3>
                        <button type="button" id="closeScanner" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-600 mb-4">Arahkan kamera ke QR Code untuk scan absensi</p>
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
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // QR Code Scanner functionality
        let html5QrcodeScanner = null;
        let isScanning = false;

        // Setup CSRF token untuk AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Check browser compatibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            const openScannerBtn = document.getElementById('openScannerBtn');
            
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                openScannerBtn.disabled = true;
                openScannerBtn.classList.add('opacity-50', 'cursor-not-allowed');
                openScannerBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Browser Tidak Mendukung';
                showNotification('Browser Anda tidak mendukung akses kamera. Silakan gunakan browser modern.', 'warning');
            } else {
                console.log('✅ Browser supports camera access');
            }
        });

        // Open Scanner button functionality
        document.getElementById('openScannerBtn').addEventListener('click', function() {
            document.getElementById('scannerModal').classList.remove('hidden');
            initializeHTML5QRCodeScanner();
        });

        // Manual input button functionality
        document.getElementById('manualInputBtn').addEventListener('click', function() {
            showManualInputDialog();
        });

        // Modal control buttons
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
                    showNotification('Scanner HTML5-QRCode siap. Arahkan kamera ke QR Code.', 'success');
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
            console.log('🎉 QR Code detected:', decodedText);
            processScannedQRCode(decodedText);
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
                    html5QrcodeScanner.stop();
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
            
            // Reset placeholder content
            scannerPlaceholder.innerHTML = `
                <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
            `;
            
            // Remove manual input button if exists
            const manualInputBtn = document.getElementById('manualInputBtn');
            if (manualInputBtn) {
                manualInputBtn.remove();
            }
        }

        // Process scanned QR Code
        function processScannedQRCode(qrData) {
            const scannerStatus = document.getElementById('scannerStatus');
            scannerStatus.textContent = 'Memproses QR Code...';
            
            // Send QR data to server for processing
            fetch(`{{ route('petugas.absensi-pengunjung.scan-qr') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ qr_code: qrData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeScanner();
                    // Reload page to show updated attendance list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message, 'error');
                    scannerStatus.textContent = 'Scan gagal - coba lagi';
                }
            })
            .catch(error => {
                console.error('Error processing QR code:', error);
                showNotification('Terjadi kesalahan saat memproses QR Code', 'error');
                scannerStatus.textContent = 'Error - coba lagi';
            });
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
            
            showNotification('Gunakan tombol "Input Manual" untuk memasukkan QR Code.', 'info');
        }

        function showManualInputDialog() {
            const qrCodeInput = prompt('Masukkan QR Code atau nomor anggota:');
            if (qrCodeInput && qrCodeInput.trim()) {
                processScannedQRCode(qrCodeInput.trim());
            }
        }

        // Notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transform transition-all duration-300 translate-x-full`;
            
            // Set background color based on type
            switch(type) {
                case 'success':
                    notification.classList.add('bg-green-500');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500');
                    break;
                case 'warning':
                    notification.classList.add('bg-yellow-500');
                    break;
                default:
                    notification.classList.add('bg-blue-500');
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (html5QrcodeScanner && isScanning) {
                html5QrcodeScanner.stop();
            }
        });
    </script>
</body>
</html>