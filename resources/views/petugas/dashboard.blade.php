@extends('layouts.petugas')

@section('content')
<div class="space-y-8 animate-stagger">
    <!-- Welcome Header with Gradient Background -->
    <div class="modern-card-gradient relative overflow-hidden card-hover-float">
        <div class="absolute inset-0 bg-gradient-to-br from-green-400/20 to-teal-600/20"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 bg-white rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
            <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        <div class="relative px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2 animate-fadeInLeft">
                        Selamat Datang, {{ Auth::user()->nama_panggilan ?: Auth::user()->nama_lengkap }}! 👋
                    </h1>
                    <p class="text-green-100 text-sm md:text-base animate-fadeInLeft" style="animation-delay: 0.2s;">
                        Kelola buku tamu dan data kunjungan perpustakaan dengan mudah
                    </p>
                </div>
                <div class="hidden md:block animate-fadeInRight">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center icon-pulse">
                        <i class="fas fa-user-tie text-3xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Row -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 animate-stagger">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 card-hover-float">
                    <div class="text-white text-2xl font-bold">{{ $totalTamuHariIni ?? 0 }}</div>
                    <div class="text-green-100 text-sm">Tamu Hari Ini</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 card-hover-float">
                    <div class="text-white text-2xl font-bold">{{ $tamuDatang ?? 0 }}</div>
                    <div class="text-green-100 text-sm">Sedang Berkunjung</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 card-hover-float">
                    <div class="text-white text-2xl font-bold">{{ $tamuPulang ?? 0 }}</div>
                    <div class="text-green-100 text-sm">Sudah Pulang</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 card-hover-float">
                    <div class="text-white text-2xl font-bold" data-time>{{ now()->format('H:i') }}</div>
                    <div class="text-green-100 text-sm">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-1">
            <div class="modern-card">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2 icon-bounce"></i>
                        Quick Actions
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('petugas.buku-tamu.create') }}" class="btn-modern btn-primary w-full btn-hover-glow">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Tamu Baru</span>
                        </a>
                        
                        <a href="{{ route('petugas.buku-tamu.index') }}" class="btn-modern btn-secondary w-full">
                            <i class="fas fa-list"></i>
                            <span>Lihat Daftar Tamu</span>
                        </a>
                        
                        <a href="{{ route('petugas.beranda') }}" class="btn-modern btn-ghost w-full">
                            <i class="fas fa-home"></i>
                            <span>Beranda Website</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="modern-card mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2 icon-pulse"></i>
                        Informasi Sistem
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Versi Sistem:</span>
                            <span class="font-medium">SIPERPUS v2.0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Server Time:</span>
                            <span class="font-medium" data-time>{{ now()->format('H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="badge-modern badge-success animate-pulse">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="modern-card h-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock text-green-500 mr-2 icon-spin"></i>
                            Aktivitas Terbaru
                        </h3>
                        <a href="{{ route('petugas.buku-tamu.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium btn-hover-glow">
                            Lihat Semua →
                        </a>
                    </div>
                    
                    <!-- Activity Timeline -->
                    <div class="space-y-4">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg table-row-hover">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center animate-scaleIn">
                                    <i class="fas fa-user text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity->nama_pengunjung }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->asal_instansi }} • {{ $activity->tujuan_kunjungan }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($activity->waktu_keluar)
                                    <span class="badge-modern badge-success">Selesai</span>
                                @else
                                    <span class="badge-modern badge-warning animate-pulse">Berkunjung</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 animate-fadeInUp">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                                <i class="fas fa-clipboard-list text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada aktivitas hari ini</p>
                            <a href="{{ route('petugas.buku-tamu.create') }}" class="btn-modern btn-primary mt-4 btn-hover-glow">
                                <i class="fas fa-plus"></i>
                                Tambah Tamu Pertama
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-stagger">
        <!-- Today's Visitors -->
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $totalTamuHariIni ?? 0 }}</h4>
                <p class="text-sm text-gray-600 mb-2">Pengunjung Hari Ini</p>
                <div class="flex items-center text-xs">
                    <span class="text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% dari kemarin
                    </span>
                </div>
            </div>
        </div>

        <!-- Currently Visiting -->
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $tamuDatang ?? 0 }}</h4>
                <p class="text-sm text-gray-600 mb-2">Sedang Berkunjung</p>
                <div class="flex items-center text-xs">
                    <span class="text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                        <i class="fas fa-eye mr-1"></i>
                        Real-time
                    </span>
                </div>
            </div>
        </div>

        <!-- Completed Visits -->
        <div class="stat-card">
            <div class="stat-icon secondary">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $tamuPulang ?? 0 }}</h4>
                <p class="text-sm text-gray-600 mb-2">Selesai Berkunjung</p>
                <div class="flex items-center text-xs">
                    <span class="text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                        <i class="fas fa-chart-line mr-1"></i>
                        Efisiensi tinggi
                    </span>
                </div>
            </div>
        </div>

        <!-- Monthly Total -->
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $totalBulanIni ?? 0 }}</h4>
                <p class="text-sm text-gray-600 mb-2">Total Bulan Ini</p>
                <div class="flex items-center text-xs">
                    <span class="text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">
                        <i class="fas fa-trophy mr-1"></i>
                        Target tercapai
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tips & Guides -->
        <div class="modern-card">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Tips & Panduan
                </h3>
                
                <div class="space-y-4">
                    <div class="alert-modern alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <p class="font-medium">Fitur Scan Barcode</p>
                            <p class="text-sm">Gunakan kamera untuk scan barcode anggota dengan cepat</p>
                        </div>
                    </div>
                    
                    <div class="alert-modern alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <p class="font-medium">Pencatatan Otomatis</p>
                            <p class="text-sm">Waktu kunjungan tercatat otomatis saat menambah tamu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shortcuts -->
        <div class="modern-card">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-keyboard text-blue-500 mr-2"></i>
                    Keyboard Shortcuts
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tambah Tamu Baru</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl + N</kbd>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Cari Tamu</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl + F</kbd>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Refresh Halaman</span>
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">F5</kbd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-refresh script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh time every second
    setInterval(function() {
        const timeElements = document.querySelectorAll('[data-time]');
        timeElements.forEach(element => {
            element.textContent = new Date().toLocaleTimeString('id-ID');
        });
    }, 1000);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route('petugas.buku-tamu.create') }}';
        }
        
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            window.location.href = '{{ route('petugas.buku-tamu.index') }}';
        }
    });
});
</script>
@endsection