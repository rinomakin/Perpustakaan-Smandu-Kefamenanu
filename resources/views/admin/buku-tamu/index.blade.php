@extends('layouts.admin')

@section('title', 'Buku Tamu')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-7xl mx-auto">
   

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-[12px] font-medium text-gray-600 uppercase">Hari Ini</h4>
                    <p class="text-[16px] font-bold text-gray-900">{{ $totalTamuHariIni }}</p>
                </div>
                <div class="bg-blue-100 h-12 w-12 flex items-center justify-center rounded-full">
                    <i class="fas fa-users text-blue-600 text-sm"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[12px] font-medium text-gray-600 uppercase">Bulan Ini</h3>
                    <p class="text-[16px] font-bold text-gray-900">{{ $totalTamuBulanIni }}</p>
                </div>
                <div class="bg-green-100 h-12 w-12 flex items-center justify-center rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[12px] font-medium text-gray-600 uppercase">Sedang Berkunjung</h3>
                    <p class="text-[16px] font-bold text-gray-900">{{ $sedangBerkunjung }}</p>
                </div>
                <div class="bg-yellow-100 h-12 w-12 flex items-center justify-center rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[12px] font-medium text-gray-600 uppercase">Total Kunjungan</h3>
                    <p class="text-[16px] font-bold text-gray-900">{{ $totalKunjungan }}</p>
                </div>
                <div class="bg-purple-100 h-12 w-12 flex items-center justify-center rounded-full">
                    <i class="fas fa-history text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Today's Visitors -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="flex items-center bg-blue-700 py-4 justify-between mb-6 px-4 rounded-t-xl">
            <h2 class="text-sm font-semibold text-gray-100 flex space-x-2 items-center">
                <i class="fas fa-users mr-2 "></i>
                Tamu Hari Ini
                <span class="text-xs text-gray-400" >{{ now()->format('d F Y') }}</span>
            </h2>
            <p class="text-xs text-gray-100">
                
            </p>
            <div class="flex space-x-2">
            <input type="text" id="searchVisitor" placeholder="Cari tamu..." 
                   class="px-4 py-2 rounded-lg border outline-none border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs">


                <a href="{{ route('admin.buku-tamu.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah
            </a>
            <a href="{{ route('admin.buku-tamu.history') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-history mr-2"></i>
                Riwayat
            </a>
            </div>
        </div>

        <!-- Visitors List -->
        <div id="visitors-container" class="space-y-4">
            @if($kunjunganHariIni->count() === 0)
                <div class="text-center py-12 text-gray-800 bg-blue-800 rounded-lg">
                    <i class="fas fa-users text-xl mb-4 text-gray-300"></i>
                    <h3 class="text-sm font-medium mb-2">Belum ada tamu hari ini</h3>
                    <p class="text-gray-400">Mulai dengan menambahkan tamu baru</p>
                </div>
            @else
                @foreach($kunjunganHariIni as $kunjungan)
                    @if($kunjungan->anggota || $kunjungan->nama_tamu)
                        <div class="visitor-item bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200" data-id="{{ $kunjungan->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">

                                    <img src="{{ $kunjungan->anggota && $kunjungan->anggota->foto ? asset('storage/' . $kunjungan->anggota->foto) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" fill="#e5e7eb"/><text x="20" y="24" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="14">👤</text></svg>') }}" 
                                         alt="Foto" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $kunjungan->nama_tamu ?? ($kunjungan->anggota ? $kunjungan->anggota->nama_lengkap : 'Nama Tidak Tersedia') }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $kunjungan->anggota ? $kunjungan->anggota->nomor_anggota : 'Tamu Umum' }} | 
                                            {{ $kunjungan->instansi ?? ($kunjungan->anggota && $kunjungan->anggota->kelas ? $kunjungan->anggota->kelas->nama_kelas : '-') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Datang: {{ $kunjungan->waktu_datang->format('H:i') }} 
                                            @if($kunjungan->waktu_pulang)
                                                | Pulang: {{ $kunjungan->waktu_pulang->format('H:i') }}
                                            @endif
                                        </p>
                                        @if($kunjungan->keperluan)
                                            <p class="text-xs text-blue-600 font-medium">
                                                <i class="fas fa-bullseye mr-1"></i>
                                                {{ $kunjungan->keperluan }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <!-- Status Badge -->
                                    <div class="flex items-center">
                                        @if($kunjungan->waktu_pulang)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                Sudah Pulang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Sedang Berkunjung
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$kunjungan->waktu_pulang)
                                            <button onclick="recordExit({{ $kunjungan->id }})" 
                                                    class="text-red-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                Pulang
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('admin.buku-tamu.show', $kunjungan->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.buku-tamu.edit', $kunjungan->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition-colors duration-200" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.buku-tamu.destroy', $kunjungan->id) }}" 
                                              method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>
@endsection

@section('scripts')
<script>
// Make functions globally available immediately
window.recordExit = async function(kunjunganId) {
    if (!confirm('Apakah Anda yakin ingin mencatat waktu pulang untuk tamu ini?')) {
        return;
    }

    try {
        const response = await fetch(`{{ route('admin.buku-tamu.record-exit') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ kunjungan_id: kunjunganId })
        });

        const result = await response.json();

        if (result.success) {
            showMessage(result.message, 'success');
            // Refresh the page to update the display
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showMessage(result.message, 'error');
        }
    } catch (error) {
        console.error('Error recording exit:', error);
        showMessage('Terjadi kesalahan saat mencatat waktu pulang', 'error');
    }
};

// Show message function
window.showMessage = function(message, type) {
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
};

// Wait for DOM to be ready for event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality for visitors
    const searchInput = document.getElementById('searchVisitor');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const visitorItems = document.querySelectorAll('.visitor-item');
            
            visitorItems.forEach(item => {
                const visitorName = item.querySelector('h4').textContent.toLowerCase();
                const visitorInfo = item.querySelector('.text-gray-600').textContent.toLowerCase();
                
                if (visitorName.includes(searchTerm) || visitorInfo.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
