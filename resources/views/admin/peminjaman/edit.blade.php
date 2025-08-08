@extends('layouts.admin')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Peminjaman</h1>
                    <p class="text-gray-600 mt-1">Edit data peminjaman dan buku yang dipinjam</p>
                </div>
                <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Form Edit Peminjaman</h3>
            </div>
            
            <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Informasi Peminjaman -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Anggota -->
                    <div class="md:col-span-2">
                        <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Anggota
                        </label>
                        <select name="anggota_id" id="anggota_id" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $member)
                            <option value="{{ $member->id }}" {{ old('anggota_id', $peminjaman->anggota_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->nama_lengkap }} - {{ $member->nomor_anggota }}
                            </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </label>
                        <select name="status" id="status" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ old('status', $peminjaman->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Tanggal Pinjam
                        </label>
                        <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" 
                               value="{{ old('tanggal_peminjaman', $peminjaman->tanggal_peminjaman->format('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                               value="{{ old('jam_peminjaman', $peminjaman->jam_peminjaman ? $peminjaman->jam_peminjaman->format('H:i') : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                               value="{{ old('tanggal_harus_kembali', $peminjaman->tanggal_harus_kembali->format('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal_harus_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Kembali -->
                    <div>
                        <label for="jam_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-2"></i>Jam Kembali
                        </label>
                        <input type="time" name="jam_kembali" id="jam_kembali" 
                               value="{{ old('jam_kembali', $peminjaman->jam_kembali ? $peminjaman->jam_kembali->format('H:i') : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Catatan tambahan untuk peminjaman ini...">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                        @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buku yang Dipinjam -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-book mr-2"></i>Buku yang Dipinjam
                            </h3>
                            <p class="text-sm text-gray-600">Kelola buku yang dipinjam dalam peminjaman ini</p>
                        </div>
                        <button type="button" id="addBookBtn" 
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold text-sm transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Tambah Buku
                        </button>
                    </div>

                    <!-- Daftar Buku yang Dipinjam -->
                    <div id="selectedBooks" class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Buku yang Dipilih (<span id="selectedCount">{{ $peminjaman->detailPeminjaman->count() }}</span>)</h4>
                            <div class="text-sm text-gray-600">
                                Total Buku: <span id="totalJumlah" class="font-semibold text-blue-600">{{ $peminjaman->jumlah_buku }}</span>
                            </div>
                                </div>
                        <div id="selectedBooksList" class="space-y-3">
                            @foreach($peminjaman->detailPeminjaman as $detail)
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors duration-150 book-item" data-book-id="{{ $detail->buku_id }}">
                                <div class="flex-1">
                                    <h5 class="font-semibold text-sm text-gray-900">{{ $detail->buku->judul_buku }}</h5>
                                    <p class="text-xs text-gray-600">{{ $detail->buku->penulis ?? 'N/A' }} - Stok Tersedia: {{ $detail->buku->stok_tersedia + $detail->jumlah }}</p>
                                    <p class="text-xs text-gray-500">ISBN: {{ $detail->buku->isbn ?? 'N/A' }} | Kategori: {{ $detail->buku->kategori ? $detail->buku->kategori->nama_kategori : 'N/A' }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center space-x-2">
                                        <label class="text-xs font-medium text-gray-700">Jumlah:</label>
                                        <input type="number" 
                                               name="jumlah_buku[{{ $detail->buku_id }}]" 
                                               value="{{ $detail->jumlah }}" 
                                               min="1" 
                                               max="{{ $detail->buku->stok_tersedia + $detail->jumlah }}"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               onchange="updateTotalJumlah()">
                                    </div>
                                    <button type="button" class="text-red-500 hover:text-red-700 ml-2 transition-colors duration-150" onclick="removeBook({{ $detail->buku_id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search Buku untuk Menambah -->
                    <div id="addBookSection" class="hidden mb-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Buku untuk Ditambahkan</label>
                            <div class="relative">
                                <input type="text" id="buku_search" 
                                       placeholder="Ketik judul buku, penulis, atau ISBN..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                
                                <!-- Dropdown hasil pencarian buku -->
                                <div id="bukuDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                    <!-- Hasil pencarian buku akan muncul di sini -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-t border-gray-200 pt-6 flex justify-end space-x-4">
                    <a href="{{ route('peminjaman.show', $peminjaman->id) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" id="submitBtn"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>Update Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 notifications are handled by layout -->

<script>
// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;

// Debounce function
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

// Add book button functionality
document.getElementById('addBookBtn').addEventListener('click', function() {
    const addBookSection = document.getElementById('addBookSection');
    if (addBookSection.classList.contains('hidden')) {
        addBookSection.classList.remove('hidden');
        document.getElementById('buku_search').focus();
    } else {
        addBookSection.classList.add('hidden');
    }
});

// Search buku functionality
const searchBuku = debounce(function(query) {
    const dropdown = document.getElementById('bukuDropdown');
    
    if (query.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    console.log('Searching buku with query:', query);
    
    // Tampilkan loading
    dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Mencari...</div>';
    dropdown.classList.remove('hidden');
    
    // Fetch buku dari server
    fetch(`/admin/peminjaman/search-buku?query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.data.length > 0) {
            dropdown.innerHTML = '';
            data.data.forEach((book, index) => {
                const item = document.createElement('div');
                item.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition-colors duration-150';
                item.innerHTML = `
                    <div class="font-medium text-gray-900">${book.judul_buku}</div>
                    <div class="text-sm text-gray-600">${book.penulis || 'N/A'} - Stok: ${book.stok_tersedia}</div>
                    <div class="text-xs text-gray-500">ISBN: ${book.isbn || 'N/A'} | Kategori: ${book.kategori}</div>
                `;
                item.addEventListener('click', () => selectBook(book));
                dropdown.appendChild(item);
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.innerHTML = '<div class="px-4 py-3 text-center text-gray-500">Tidak ada buku ditemukan</div>';
            dropdown.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error searching buku:', error);
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
    
    // Tambah buku ke daftar yang dipilih
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
    
    // Update counter
    const currentCount = parseInt(selectedCount.textContent);
    selectedCount.textContent = currentCount + 1;
    
    // Clear search
    document.getElementById('buku_search').value = '';
    document.getElementById('bukuDropdown').classList.add('hidden');
    
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
        
        // Update counter
        const selectedCount = document.getElementById('selectedCount');
        const currentCount = parseInt(selectedCount.textContent);
        selectedCount.textContent = currentCount - 1;
        
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
            showSuccessAlert(message);
            break;
        case 'error':
            showErrorAlert(message);
            break;
        case 'warning':
            showWarningAlert(message);
            break;
        case 'info':
        default:
            showInfoAlert(message);
            break;
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const bukuSearch = document.getElementById('buku_search');
    const bukuDropdown = document.getElementById('bukuDropdown');
    
    if (!bukuSearch.contains(event.target) && !bukuDropdown.contains(event.target)) {
        bukuDropdown.classList.add('hidden');
    }
});

// SweetAlert2 handles all notifications

// Initialize
updateSubmitButton();
updateTotalJumlah();
</script>
@endsection 