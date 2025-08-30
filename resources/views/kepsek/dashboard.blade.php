@extends('layouts.admin')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Kepala Sekolah</h1>
                <p class="text-gray-600 mt-1">Sistem Informasi Perpustakaan SMK Negeri 1 Kefamenanu</p>
            </div>
            <div>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ now()->format('d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Anggota -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Anggota</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAnggota) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Buku -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Buku</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalBuku) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-book text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Buku Dipinjam -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Buku Dipinjam</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPeminjaman) }}</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <i class="fas fa-book-reader text-indigo-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Denda</p>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Aktivitas Perpustakaan -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Aktivitas Bulan Ini</h3>
                <div class="bg-purple-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($peminjamanBulanIni) }}</div>
                    <div class="text-sm text-gray-600">Peminjaman</div>
                    <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(($peminjamanBulanIni / max($totalBuku, 1)) * 100, 100) }}%"></div>
                    </div>
                </div>
                <div class="text-center bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($pengembalianBulanIni) }}</div>
                    <div class="text-sm text-gray-600">Pengembalian</div>
                    <div class="w-full bg-green-200 rounded-full h-2 mt-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(($pengembalianBulanIni / max($totalBuku, 1)) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('laporan.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-chart-line mr-2"></i>
                    Lihat Laporan Lengkap
                </a>
            </div>
        </div>

        <!-- Ringkasan Sistem -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-indigo-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Ringkasan Sistem</h3>
                <div class="bg-indigo-100 p-2 rounded-lg">
                    <i class="fas fa-cogs text-indigo-600"></i>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-indigo-600">{{ number_format(($totalPeminjaman / max($totalBuku, 1)) * 100, 1) }}%</div>
                            <div class="text-sm text-gray-600">Tingkat Sirkulasi Buku</div>
                        </div>
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-percentage text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ number_format($totalBuku - $totalPeminjaman) }}</div>
                            <div class="text-sm text-gray-600">Buku Tersedia</div>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <div class="text-xs text-gray-500 flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-1 animate-spin"></i>
                    Data diperbarui secara real-time
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Monitoring Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-eye text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Real-time</div>
                    <div class="text-lg font-semibold">Monitoring</div>
                </div>
            </div>
            <p class="text-blue-100 text-sm mb-4">Pantau aktivitas perpustakaan secara real-time dan dapatkan insight terbaru</p>
            <div class="flex items-center text-xs">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                Status: Aktif
            </div>
        </div>

        <!-- Laporan Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Analytics</div>
                    <div class="text-lg font-semibold">Laporan</div>
                </div>
            </div>
            <p class="text-green-100 text-sm mb-4">Akses laporan komprehensif dan statistik perpustakaan</p>
            <div class="mt-auto">
                <a href="{{ route('laporan.index') }}" 
                   class="inline-flex items-center px-3 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-xs font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Data Access Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-database text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Data</div>
                    <div class="text-lg font-semibold">Akses Data</div>
                </div>
            </div>
            <p class="text-purple-100 text-sm mb-4">Akses data anggota, buku, dan riwayat transaksi perpustakaan</p>
            <div class="grid grid-cols-2 gap-2 mt-auto">
                <a href="{{ route('kepsek.data-anggota') }}" 
                   class="inline-flex items-center justify-center px-2 py-1 bg-white bg-opacity-20 hover:bg-opacity-30 rounded text-xs font-medium transition-colors duration-200">
                    <i class="fas fa-users mr-1"></i>
                    Anggota
                </a>
                <a href="{{ route('kepsek.data-buku') }}" 
                   class="inline-flex items-center justify-center px-2 py-1 bg-white bg-opacity-20 hover:bg-opacity-30 rounded text-xs font-medium transition-colors duration-200">
                    <i class="fas fa-book mr-1"></i>
                    Buku
                </a>
            </div>
        </div>
    </div>

    <!-- Status Notifications -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bell text-orange-500 mr-2"></i>
                Notifikasi & Status Sistem
            </h3>
            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                {{ now()->format('H:i') }} WIB
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <div class="text-sm font-medium text-gray-700">Sistem Online</div>
                    <div class="text-xs text-gray-500">Berjalan normal</div>
                </div>
            </div>
            <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                <div>
                    <div class="text-sm font-medium text-gray-700">Database</div>
                    <div class="text-xs text-gray-500">Terhubung</div>
                </div>
            </div>
            <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                <div>
                    <div class="text-sm font-medium text-gray-700">Backup</div>
                    <div class="text-xs text-gray-500">Terjadwal</div>
                </div>
            </div>
            <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                <div>
                    <div class="text-sm font-medium text-gray-700">Monitoring</div>
                    <div class="text-xs text-gray-500">Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation for statistics cards
    const cards = document.querySelectorAll('.grid > div');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Progress bar animations
    const progressBars = document.querySelectorAll('.bg-blue-600, .bg-green-600');
    setTimeout(() => {
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            bar.style.transition = 'width 1.5s ease-in-out';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 1000);

    // Live time update
    function updateTime() {
        const timeElement = document.querySelector('.bg-orange-100.text-orange-800');
        if (timeElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            timeElement.textContent = timeString + ' WIB';
        }
    }

    // Update time every minute
    setInterval(updateTime, 60000);

    // Add hover effects for interactive elements
    const interactiveCards = document.querySelectorAll('.hover\\:scale-105');
    interactiveCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add floating animation to pulse elements
    const pulseElements = document.querySelectorAll('.animate-pulse');
    pulseElements.forEach(element => {
        setInterval(() => {
            element.style.opacity = '0.3';
            setTimeout(() => {
                element.style.opacity = '1';
            }, 500);
        }, 2000);
    });

    // Auto refresh data setiap 5 menit (optional)
    setInterval(function() {
        // Uncomment jika ingin auto refresh
        // location.reload();
    }, 300000); // 5 menit

    // Show success message if dashboard loaded successfully
    setTimeout(() => {
        if (window.showToast) {
            showToast('Dashboard berhasil dimuat', 'success');
        }
    }, 2000);
});
</script>
@endsection
