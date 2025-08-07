@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap peminjaman</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('peminjaman.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Informasi Peminjaman</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Peminjaman</label>
                        <p class="text-gray-900 font-semibold">{{ $peminjaman->nomor_peminjaman }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Buku</label>
                        <p class="text-gray-900 font-semibold">{{ $peminjaman->jumlah_buku }} Buku</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Loan Information -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Status</h4>
                            @if($peminjaman->status == 'dipinjam')
                                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">Dipinjam</span>
                            @elseif($peminjaman->status == 'dikembalikan')
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Dikembalikan</span>
                            @elseif($peminjaman->status == 'terlambat')
                                <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">Terlambat</span>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Peminjaman</h4>
                            <p>
                                {{ $peminjaman->tanggal_peminjaman->format('d M Y') }}
                                @if($peminjaman->jam_peminjaman)
                                    <span class="text-sm text-gray-500">Jam {{ $peminjaman->jam_peminjaman->format('H:i') }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Harus Kembali</h4>
                            <p>{{ $peminjaman->tanggal_harus_kembali->format('d M Y') }}</p>
                        </div>

                        @if($peminjaman->tanggal_kembali)
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Dikembalikan</h4>
                            <p>
                                {{ $peminjaman->tanggal_kembali->format('d M Y') }}
                                @if($peminjaman->jam_kembali)
                                    <span class="text-sm text-gray-500">Jam {{ $peminjaman->jam_kembali->format('H:i') }}</span>
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($peminjaman->catatan)
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Catatan</h4>
                            <p class="text-gray-600">{{ $peminjaman->catatan }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Member Information -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Anggota</h4>
                            <div class="flex items-center space-x-3">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                    @if($peminjaman->anggota->foto)
                                        <img src="{{ asset('uploads/' . $peminjaman->anggota->foto) }}" 
                                             alt="{{ $peminjaman->anggota->nama_lengkap }}" 
                                             class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-lg">{{ $peminjaman->anggota->nama_lengkap }}</p>
                                    <p class="text-sm text-gray-500">{{ $peminjaman->anggota->nomor_anggota }}</p>
                                    <p class="text-sm text-gray-500">{{ $peminjaman->anggota->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Petugas</h4>
                            <p>{{ $peminjaman->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Books List -->
                <div class="mt-8">
                    <h4 class="font-semibold text-gray-700 mb-4">Buku yang Dipinjam</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($peminjaman->detailPeminjaman as $detail)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-3">
                                <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    @if($detail->buku->gambar_sampul)
                                        <img src="{{ asset('uploads/' . $detail->buku->gambar_sampul) }}" 
                                             alt="{{ $detail->buku->judul_buku }}" 
                                             class="w-full h-full object-cover rounded">
                                    @else
                                        <i class="fas fa-book text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-sm">{{ $detail->buku->judul_buku }}</h5>
                                    <p class="text-xs text-gray-500">{{ $detail->buku->penulis ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">ISBN: {{ $detail->buku->isbn ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Jumlah: <span class="font-semibold text-blue-600">{{ $detail->jumlah ?? 1 }}</span> eksemplar</p>
                                    <p class="text-xs text-gray-500">Kondisi: {{ $detail->kondisi_kembali }}</p>
                                    @if($detail->catatan)
                                    <p class="text-xs text-gray-500 mt-1">{{ $detail->catatan }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            <i class="fas fa-book-open text-4xl mb-4"></i>
                            <p>Tidak ada buku dipinjam</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<script>
// Auto hide messages after 5 seconds
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
</script>
@endsection 