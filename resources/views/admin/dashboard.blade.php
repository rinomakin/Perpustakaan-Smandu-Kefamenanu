@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            Selamat Datang di {{ $pengaturan->nama_website ?? 'SIPERPUS' }}
        </h2>
        <p class="text-gray-600">
            {{ $pengaturan->alamat_sekolah ?? 'Sistem Informasi Perpustakaan' }}
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Anggota -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Anggota::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Buku -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Buku</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Buku::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Peminjaman Aktif -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peminjaman Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-money-bill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Denda</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format(\App\Models\Denda::sum('jumlah_denda'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('anggota.create') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-user-plus text-blue-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Tambah Anggota</p>
                    <p class="text-sm text-gray-600">Daftarkan anggota baru</p>
                </div>
            </a>
            
            <a href="{{ route('buku.create') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-book-medical text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Tambah Buku</p>
                    <p class="text-sm text-gray-600">Tambah buku baru</p>
                </div>
            </a>
            
            <a href="{{ route('peminjaman.create') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-handshake text-yellow-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Peminjaman</p>
                    <p class="text-sm text-gray-600">Proses peminjaman</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            @php
                $recentPeminjaman = \App\Models\Peminjaman::with('anggota')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            
            @forelse($recentPeminjaman as $peminjaman)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $peminjaman->anggota->nama_lengkap }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Meminjam buku - {{ $peminjaman->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $peminjaman->status === 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                           ($peminjaman->status === 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas peminjaman</p>
            @endforelse
        </div>
    </div>
</div>
@endsection 