@extends('layouts.admin')

@section('title', 'Tambah Anggota')

@push('styles')
<style>
    .barcode-container {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .barcode-display {
        text-align: center;
        font-family: 'Courier New', monospace;
        font-size: 18px;
        font-weight: bold;
        color: #333;
        background: white;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 10px 0;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    
    #video {
        width: 100%;
        height: 300px;
        background: #000;
        border-radius: 8px;
    }
    
    .camera-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 100px;
        border: 2px solid #fff;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container px-6 mx-auto grid">
    <p class="my-6 ">
    <a href="{{ route('anggota.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
    </p>

    <!-- Alert Sukses -->
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Alert Validasi Error -->
    @if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('anggota.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Barcode Section -->
            <div class="barcode-container p-2">
                <!-- <h3 class="text-lg font-semibold mb-3">Barcode Anggota</h3> -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label for="barcode_anggota" class="block text-sm font-medium text-gray-700 mb-2">Barcode <span class="text-red-500">*</span></label>
                        <div class="flex space-x-2">
                            <input type="text" name="barcode_anggota" id="barcode_anggota" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Masukkan atau scan barcode">
                            <button type="button" onclick="generateBarcode()"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i>Generate
                            </button>
                        </div>
                        <!-- <div id="barcodeDisplay" class="barcode-display mt-2 hidden">
                            <div class="text-center">
                                <img id="barcodeImage" src="" alt="Barcode" class="mx-auto mb-2" style="max-width: 200px; height: auto;">
                                <div id="barcodeText" class="font-mono text-sm"></div>
                            </div>
                        </div> -->
                    </div>
                    <div>
                        <!-- nama lengkap form -->
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Masukkan nama lengkap">
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama lengkap">
                </div> -->
                
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" required maxlength="16"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan NIK (16 digit)">
                </div>
                
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan email (opsional)">
                </div>
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Masukkan alamat lengkap"></textarea>
            </div>

            <!-- School Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="jenis_anggota" class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota <span class="text-red-500">*</span></label>
                    <select name="jenis_anggota" id="jenis_anggota" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Anggota</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan jabatan (opsional)">
                </div>
            </div>

            <!-- Status and Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditangguhkan">Ditangguhkan</option>
                    </select>
                </div>
                
                <div>
                    <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="mb-10   ">
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
               
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 w-full text-white rounded-md hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<!-- <div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Scan Barcode Anggota</h3>
                    <button type="button" id="closeScannerBtn" class="text-white hover:text-gray-200">
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
</div> -->

<script>
let stream = null;

function generateBarcode() {
    const prefix = 'BC';
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const barcode = prefix + timestamp + random;
    
    document.getElementById('barcode_anggota').value = barcode;
    document.getElementById('barcodeText').textContent = barcode;
    
    // Generate barcode image using AJAX
    fetch('{{ route("anggota.generate-barcode") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: barcode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('barcodeImage').src = 'data:image/png;base64,' + data.barcode;
            document.getElementById('barcodeDisplay').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error generating barcode:', error);
        document.getElementById('barcodeDisplay').classList.remove('hidden');
    });
}

    // Scanner functionality
    let quaggaInitialized = false;

    // Scan barcode button
    document.getElementById('scanBarcodeBtn').addEventListener('click', function() {
        document.getElementById('scannerModal').classList.remove('hidden');
        initializeScanner();
    });

    // Close scanner modal
    document.getElementById('closeScannerBtn').addEventListener('click', function() {
        closeScanner();
    });

    // Start scanning
    document.getElementById('startScanBtn').addEventListener('click', function() {
        startScanning();
    });

    // Stop scanning
    document.getElementById('stopScanBtn').addEventListener('click', function() {
        stopScanning();
    });

    // Cancel scan
    document.getElementById('cancelScan').addEventListener('click', function() {
        closeScanner();
    });

    // Close modal when clicking outside
    document.getElementById('scannerModal').addEventListener('click', function(e) {
        if (e.target === this) {
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
        document.getElementById('barcode_anggota').value = cleanBarcode;
        
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
        } else if (type === 'warning') {
            notification.className += ' bg-yellow-500 text-white';
        } else {
            notification.className += ' bg-blue-500 text-white';
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
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

// Auto generate barcode on page load
document.addEventListener('DOMContentLoaded', function() {
    generateBarcode();
    
    // Set default date to today
    document.getElementById('tanggal_bergabung').value = new Date().toISOString().split('T')[0];
});
</script>
@endsection 