@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Peminjaman</h1>
                    <p class="text-gray-600 mt-1">Form peminjaman buku dengan auto-deteksi</p>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Form Peminjaman Buku</h3>
            </div>
            
            <form action="{{ route('peminjaman.store') }}" method="POST" class="p-6">
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
                                       placeholder="Ketik nama anggota, NISN, atau nomor anggota..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
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
                                <button type="button" id="clearAnggota" class="text-red-500 hover:text-red-700">
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
                               value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
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
                               value="{{ old('jam_peminjaman', date('H:i')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        @error('jam_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Harus Kembali -->
                    <div>
                        <label for="tanggal_harus_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-2"></i>Batas Kembali
                        </label>
                        <input type="date" name="tanggal_harus_kembali" id="tanggal_harus_kembali" 
                               value="{{ old('tanggal_harus_kembali') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        @error('tanggal_harus_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Pengembalian -->
                    <div>
                        <label for="jam_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2"></i>Jam Kembali
                        </label>
                        <input type="time" name="jam_kembali" id="jam_kembali" 
                               value="{{ old('jam_kembali') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <p class="text-xs text-gray-500 mt-1">Opsional - akan diisi otomatis saat pengembalian</p>
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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
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

                    <!-- Daftar Semua Buku (Tersembunyi) -->
                    <div class="hidden">
                        @foreach($buku as $book)
                        <input type="hidden" name="buku_ids[]" value="{{ $book->id }}" class="book-input">
                        @endforeach
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

<!-- Success/Error Messages -->
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
                    <div id="scannerContainer" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <div id="scannerPlaceholder" class="text-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Kamera akan aktif saat modal dibuka</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelScan" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Fungsi untuk pencarian anggota
document.getElementById('anggota_search').addEventListener('input', function() {
    const query = this.value.trim();
    const dropdown = document.getElementById('anggotaDropdown');
    
    if (query.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    // Fetch anggota dari server
    fetch(`/peminjaman/search-anggota?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                dropdown.innerHTML = '';
                data.data.forEach(anggota => {
                    const item = document.createElement('div');
                    item.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100';
                    item.innerHTML = `
                        <div class="font-medium text-gray-900">${anggota.nama_lengkap}</div>
                        <div class="text-sm text-gray-600">${anggota.nomor_anggota} - ${anggota.kelas}</div>
                    `;
                    item.addEventListener('click', () => selectAnggota(anggota));
                    dropdown.appendChild(item);
                });
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdown.classList.add('hidden');
        });
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
}

// Fungsi untuk clear anggota
document.getElementById('clearAnggota').addEventListener('click', function() {
    document.getElementById('anggota_id').value = '';
    document.getElementById('anggotaInfo').classList.add('hidden');
    document.getElementById('anggota_search').value = '';
});

// Fungsi untuk pencarian buku
document.getElementById('buku_search').addEventListener('input', function() {
    const query = this.value.trim();
    const dropdown = document.getElementById('bukuDropdown');
    
    if (query.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    // Fetch buku dari server
    fetch(`/peminjaman/search-buku?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                dropdown.innerHTML = '';
                data.data.forEach(book => {
                    const item = document.createElement('div');
                    item.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100';
                    item.innerHTML = `
                        <div class="font-medium text-gray-900">${book.judul_buku}</div>
                        <div class="text-sm text-gray-600">${book.penulis || 'N/A'} - Stok: ${book.stok_tersedia}</div>
                        <div class="text-xs text-gray-500">ISBN: ${book.isbn || 'N/A'} | Barcode: ${book.barcode_buku || 'N/A'}</div>
                    `;
                    item.addEventListener('click', () => selectBook(book));
                    dropdown.appendChild(item);
                });
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdown.classList.add('hidden');
        });
});

// Fungsi untuk memilih buku
function selectBook(book) {
    const selectedBooksList = document.getElementById('selectedBooksList');
    const selectedCount = document.getElementById('selectedCount');
    
    // Cek apakah buku sudah dipilih
    const existingBook = document.querySelector(`[data-book-id="${book.id}"]`);
    if (existingBook) {
        return; // Buku sudah dipilih
    }
    
    // Cek stok
    if (book.stok_tersedia <= 0) {
        alert('Buku tidak tersedia untuk dipinjam!');
        return;
    }
    
    // Tambah buku ke daftar yang dipilih dengan field jumlah
    const bookItem = document.createElement('div');
    bookItem.className = 'flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200';
    bookItem.setAttribute('data-book-id', book.id);
    bookItem.innerHTML = `
        <div class="flex-1">
            <h5 class="font-semibold text-sm text-gray-900">${book.judul_buku}</h5>
            <p class="text-xs text-gray-600">${book.penulis || 'N/A'} - Stok Tersedia: ${book.stok_tersedia}</p>
            <p class="text-xs text-gray-500">ISBN: ${book.isbn || 'N/A'}</p>
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
            <button type="button" class="text-red-500 hover:text-red-700 ml-2" onclick="removeBook(${book.id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    selectedBooksList.appendChild(bookItem);
    
    // Update input hidden
    const bookInput = document.createElement('input');
    bookInput.type = 'hidden';
    bookInput.name = 'buku_ids[]';
    bookInput.value = book.id;
    bookInput.className = 'book-input';
    document.querySelector('form').appendChild(bookInput);
    
    // Update counter
    const currentCount = parseInt(selectedCount.textContent);
    selectedCount.textContent = currentCount + 1;
    
    // Clear search
    document.getElementById('buku_search').value = '';
    document.getElementById('bukuDropdown').classList.add('hidden');
    
    // Update submit button
    updateSubmitButton();
    updateTotalJumlah();
}

// Fungsi untuk menghapus buku
function removeBook(bookId) {
    const bookItem = document.querySelector(`[data-book-id="${bookId}"]`);
    if (bookItem) {
        bookItem.remove();
        
        // Remove input hidden
        const bookInput = document.querySelector(`input[name="buku_ids[]"][value="${bookId}"]`);
        if (bookInput) {
            bookInput.remove();
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
        
        // Update submit button and total
        updateSubmitButton();
        updateTotalJumlah();
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
    } else {
        submitBtn.disabled = true;
    }
}

// Event listeners untuk update submit button
document.getElementById('anggota_id').addEventListener('change', updateSubmitButton);
document.getElementById('selectedCount').addEventListener('DOMSubtreeModified', updateSubmitButton);

// Scanner functionality
document.getElementById('scanAnggotaBtn').addEventListener('click', function() {
    document.getElementById('scannerTitle').textContent = 'Scan Barcode Anggota';
    document.getElementById('scannerDescription').textContent = 'Arahkan kamera ke barcode anggota';
    document.getElementById('scannerModal').classList.remove('hidden');
    // Implementasi scanner untuk anggota
});

document.getElementById('scanBukuBtn').addEventListener('click', function() {
    document.getElementById('scannerTitle').textContent = 'Scan Barcode Buku';
    document.getElementById('scannerDescription').textContent = 'Arahkan kamera ke barcode buku';
    document.getElementById('scannerModal').classList.remove('hidden');
    // Implementasi scanner untuk buku
});

document.getElementById('closeScanner').addEventListener('click', function() {
    document.getElementById('scannerModal').classList.add('hidden');
});

document.getElementById('cancelScan').addEventListener('click', function() {
    document.getElementById('scannerModal').classList.add('hidden');
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
</script>
@endsection 