@extends('layouts.admin')

@section('title', 'Edit Anggota')

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
    
    .current-barcode {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
        text-align: center;
        font-family: 'Courier New', monospace;
        font-size: 16px;
        font-weight: bold;
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
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Edit Anggota
    </h2>

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
        <form method="POST" action="{{ route('anggota.update', $anggota->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Barcode Section -->
            <div class="barcode-container">
                <h3 class="text-lg font-semibold mb-3">Barcode Anggota</h3>
                
                <!-- Current Barcode Display -->
                <div class="current-barcode">
                    <div class="text-sm text-gray-600 mb-1">Barcode Saat Ini:</div>
                    <div>{{ $anggota->barcode_anggota }}</div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="barcode_anggota" class="block text-sm font-medium text-gray-700 mb-2">Barcode Baru (Opsional)</label>
                        <div class="flex space-x-2">
                            <input type="text" name="barcode_anggota" id="barcode_anggota"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Kosongkan jika tidak ingin mengubah">
                            <button type="button" onclick="generateBarcode()"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i>Generate
                            </button>
                        </div>
                        <div id="barcodeDisplay" class="barcode-display mt-2 hidden">
                            <div class="text-center">
                                <img id="barcodeImage" src="" alt="Barcode" class="mx-auto mb-2" style="max-width: 200px; height: auto;">
                                <div id="barcodeText" class="font-mono text-sm"></div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Kosongkan field ini jika tidak ingin mengubah barcode</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scan Barcode</label>
                        <button type="button" onclick="toggleCamera()"
                                class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            <i class="fas fa-camera mr-1"></i>Buka Kamera
                        </button>
                        <div id="cameraContainer" class="camera-container mt-2 hidden">
                            <video id="video" autoplay></video>
                            <div class="camera-overlay"></div>
                            <button type="button" onclick="closeCamera()"
                                    class="mt-2 w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                <i class="fas fa-times mr-1"></i>Tutup Kamera
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->nama_lengkap }}"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ $anggota->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $anggota->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik" required maxlength="16"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->nik }}"
                           placeholder="Masukkan NIK (16 digit)">
                </div>
                
                <div>
                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->nomor_telepon }}"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->email }}"
                           placeholder="Masukkan email (opsional)">
                </div>
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Masukkan alamat lengkap">{{ $anggota->alamat }}</textarea>
            </div>

            <!-- School Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelas (opsional)</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $anggota->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="jenis_anggota" class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota <span class="text-red-500">*</span></label>
                    <select name="jenis_anggota" id="jenis_anggota" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Anggota</option>
                        <option value="siswa" {{ $anggota->jenis_anggota == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ $anggota->jenis_anggota == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="staff" {{ $anggota->jenis_anggota == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->jabatan }}"
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
                        <option value="aktif" {{ $anggota->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $anggota->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="ditangguhkan" {{ $anggota->status == 'ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>
                </div>
                
                <div>
                    <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ $anggota->tanggal_bergabung->format('Y-m-d') }}">
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="mb-6">
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                
                @if($anggota->foto)
                <div class="mb-3">
                    <img src="{{ asset('storage/anggota/' . $anggota->foto) }}" 
                         alt="Foto saat ini" class="w-32 h-32 object-cover rounded-lg border">
                    <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
                </div>
                @endif
                
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('anggota.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

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

function toggleCamera() {
    const container = document.getElementById('cameraContainer');
    const video = document.getElementById('video');
    
    if (container.classList.contains('hidden')) {
        container.classList.remove('hidden');
        startCamera();
    } else {
        closeCamera();
    }
}

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(mediaStream) {
            stream = mediaStream;
            document.getElementById('video').srcObject = mediaStream;
        })
        .catch(function(error) {
            console.error('Error accessing camera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
        });
}

function closeCamera() {
    const container = document.getElementById('cameraContainer');
    container.classList.add('hidden');
    
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
}

// Handle form submission to preserve barcode if empty
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const barcodeInput = document.getElementById('barcode_anggota');
    
    form.addEventListener('submit', function(e) {
        if (barcodeInput.value.trim() === '') {
            // If barcode is empty, set it to current barcode
            barcodeInput.value = '{{ $anggota->barcode_anggota }}';
        }
    });
});
</script>
@endsection 