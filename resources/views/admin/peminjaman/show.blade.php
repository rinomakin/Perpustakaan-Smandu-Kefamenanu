@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap peminjaman buku</p>
                </div>
                <div class="flex space-x-4">
                    @if(Auth::user()->role == 'admin')
                    <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    @endif
                    <a href="{{ route('peminjaman.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Informasi Peminjaman -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Informasi Peminjaman</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Peminjaman</label>
                        <p class="text-gray-900 font-semibold text-lg">{{ $peminjaman->nomor_peminjaman }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Buku</label>
                        <p class="text-gray-900 font-semibold text-lg">{{ $peminjaman->jumlah_buku }} Buku</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        @if($peminjaman->status == 'dipinjam')
                            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">Dipinjam</span>
                        @elseif($peminjaman->status == 'dikembalikan')
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Dikembalikan</span>
                        @elseif($peminjaman->status == 'terlambat')
                            <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">Terlambat</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Petugas</label>
                        <p class="text-gray-900 font-semibold">{{ $peminjaman->user->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Loan Information -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Peminjaman</h4>
                            <p class="text-gray-900">
                                {{ $peminjaman->tanggal_peminjaman->format('d M Y') }}
                                @if($peminjaman->jam_peminjaman)
                                    <span class="text-sm text-gray-500">Jam {{ $peminjaman->jam_peminjaman->format('H:i') }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Harus Kembali</h4>
                            <p class="text-gray-900">{{ $peminjaman->tanggal_harus_kembali->format('d M Y') }}</p>
                        </div>

                        @if($peminjaman->tanggal_kembali)
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Tanggal Dikembalikan</h4>
                            <p class="text-gray-900">
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
                            <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $peminjaman->catatan }}</p>
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
                                    <p class="font-semibold text-lg text-gray-900">{{ $peminjaman->anggota->nama_lengkap }}</p>
                                    <p class="text-sm text-gray-500">{{ $peminjaman->anggota->nomor_anggota }}</p>
                                    <p class="text-sm text-gray-500">{{ $peminjaman->anggota->email ?? 'N/A' }}</p>
                                    @if($peminjaman->anggota->kelas)
                                    <p class="text-sm text-gray-500">{{ $peminjaman->anggota->kelas->nama_kelas }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-book text-white text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Buku yang Dipinjam</h3>
                    </div>
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        {{ $peminjaman->detailPeminjaman->count() }} Buku
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($peminjaman->detailPeminjaman as $detail)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                @if($detail->buku->gambar_sampul)
                                    <img src="{{ asset('uploads/' . $detail->buku->gambar_sampul) }}" 
                                         alt="{{ $detail->buku->judul_buku }}" 
                                         class="w-full h-full object-cover rounded">
                                @else
                                    <i class="fas fa-book text-gray-400 text-xl"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-sm text-gray-900 mb-1 truncate">{{ $detail->buku->judul_buku }}</h5>
                                <p class="text-xs text-gray-500 mb-1">{{ $detail->buku->penulis ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mb-2">ISBN: {{ $detail->buku->isbn ?? 'N/A' }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                            {{ $detail->jumlah ?? 1 }} eksemplar
                                        </span>
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                            {{ $detail->kondisi_kembali }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($detail->buku->kategori)
                                <p class="text-xs text-gray-500 mt-1">Kategori: {{ $detail->buku->kategori->nama_kategori }}</p>
                                @endif
                                
                                @if($detail->catatan)
                                <p class="text-xs text-gray-500 mt-1 italic">"{{ $detail->catatan }}"</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-gray-500 py-12">
                        <i class="fas fa-book-open text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg font-medium">Tidak ada buku dipinjam</p>
                        <p class="text-sm">Belum ada buku yang dipinjam dalam peminjaman ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 notifications are handled by layout -->
@endsection 