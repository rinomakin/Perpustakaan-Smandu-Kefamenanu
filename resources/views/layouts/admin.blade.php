<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pengaturan->nama_website ?? 'SIPERPUS' }} - Admin</title>
    
    @if($pengaturan && $pengaturan->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $pengaturan->favicon) }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Navbar and dropdown styles */
        .group:hover .group-hover\:opacity-100 {
            opacity: 1;
        }
        
        .group:hover .group-hover\:visible {
            visibility: visible;
        }
        
        /* Mobile menu transition */
        #mobileMenu {
            transition: all 0.3s ease-in-out;
        }
        
        /* Additional CSS to ensure proper styling */
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .btn {
            @apply px-4 py-2 rounded-lg font-medium transition-colors duration-200;
        }
        
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white;
        }
        
        .btn-success {
            @apply bg-green-600 hover:bg-green-700 text-white;
        }
        
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white;
        }
        
        .btn-warning {
            @apply bg-yellow-600 hover:bg-yellow-700 text-white;
        }
        
        .form-input {
            @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-md border border-gray-200;
        }
        
        .table {
            @apply min-w-full divide-y divide-gray-200;
        }
        
        .table th {
            @apply px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
        }
        
        .table td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
        }
        
        /* Dropdown arrow animation */
        .group:hover .fa-chevron-down {
            transform: rotate(180deg);
            transition: transform 0.2s ease-in-out;
        }
        
        .fa-chevron-down {
            transition: transform 0.2s ease-in-out;
        }
        
        /* Navbar responsive adjustments */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo dan Brand -->
                 <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.dashboard') : route('admin.dashboard') }}">
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class="h-10 w-auto">
                        <div>
                            <h1 class="font-bold text-xs w-10 whitespace-nowrap">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                            <p class="text-blue-100  text-xs whitespace-nowrap">{{ Auth::user()->isKepalaSekolah() ? 'Kepala Sekolah' : 'Admin Panel' }}</p>
                        </div>
                    </div>
                </div>
                </a>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-2 navbar-menu">
                    <!-- Dashboard -->
                    @if(Auth::user()->hasPermission('dashboard.view') || Auth::user()->isAdmin() || Auth::user()->isKepalaSekolah())
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.dashboard') : route('admin.dashboard') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ (request()->routeIs('admin.dashboard') || request()->routeIs('kepsek.dashboard')) ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    @endif

                    <!-- Data Master Dropdown -->
                    @php
                        $hasMasterPermission = Auth::user()->hasAnyPermission([
                            'role.manage', 'permissions.manage', 'jurusan.manage', 'kelas.manage', 
                            'jenis-buku.manage', 'kategori-buku.manage', 'rak-buku.manage', 'sumber-buku.manage'
                        ]) || Auth::user()->isAdmin();
                    @endphp
                    
                    @if($hasMasterPermission)
                    <div class="relative group flex items-center">
                        <button class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('jurusan.*', 'kelas.*', 'jenis-buku.*', 'sumber-buku.*', 'penerbit.*', 'penulis.*', 'kategori-buku.*', 'rak-buku.*', 'role.*', 'permissions.*') ? 'bg-white bg-opacity-20' : '' }}">
                            <i class="fas fa-database"></i>
                            <p class="whitespace-nowrap">Master</p>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute text-xs top-full left-0 mt-2 w-64 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                @if(Auth::user()->hasPermission('role.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('role.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('role.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-user-shield w-5"></i>
                                    <span>Role</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('permissions.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('permissions.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('permissions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-shield-alt w-5"></i>
                                    <span>Hak Akses</span>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('user.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('user.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('permissions.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                   <i class="fas fa-user-cog text-xs"></i>
                                   <span class="whitespace-nowrap text-xs">User</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('jurusan.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('jurusan.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('jurusan.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-graduation-cap w-5"></i>
                                    <span>Data Jurusan</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('kelas.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('kelas.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('kelas.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-chalkboard w-5"></i>
                                    <span>Data Kelas</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('jenis-buku.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('jenis-buku.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('jenis-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-book w-5"></i>
                                    <span>Jenis Buku</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('kategori-buku.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('kategori-buku.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('kategori-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-tags w-5"></i>
                                    <span>Kategori Buku</span>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('rak-buku.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('rak-buku.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('rak-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-archive w-5"></i>
                                    <span>Rak Buku</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('sumber-buku.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('sumber-buku.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('sumber-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-source w-5"></i>
                                    <span>Sumber Buku</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Data Anggota -->
                    @if(Auth::user()->hasAnyPermission(['anggota.view', 'anggota.create', 'anggota.update', 'anggota.delete']) || Auth::user()->isAdmin())
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.data-anggota') : route('anggota.index') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('anggota.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-users text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Data Anggota</span>
                    </a>
                    @endif

                                          <!-- Absensi Pengunjung -->
                     @if(Auth::user()->hasAnyPermission(['absensi.manage', 'absensi.scan', 'absensi.history']) || Auth::user()->isAdmin())
                     <a href="{{ route('admin.absensi-pengunjung.index') }}" 
                        class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('admin.absensi-pengunjung.*') ? 'bg-white bg-opacity-20' : '' }}">
                         <i class="fas fa-qrcode text-xs"></i>
                         <span class="whitespace-nowrap text-xs">Absensi</span>
                     </a>
                     @endif

                    <!-- Data Buku -->
                    @if(Auth::user()->hasAnyPermission(['buku.view', 'buku.create', 'buku.update', 'buku.delete']) || Auth::user()->isAdmin())
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.data-buku') : route('buku.index') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('buku.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="whitespace-nowrap text-xs">Data Buku</span>
                    </a>
                    @endif

                    <!-- Transaksi Dropdown -->
                    @php
                        $hasTransaksiPermission = Auth::user()->hasAnyPermission([
                            'peminjaman.manage', 'pengembalian.manage', 'riwayat-transaksi.view'
                        ]) || Auth::user()->isAdmin();
                    @endphp
                    
                    @if($hasTransaksiPermission)
                    <div class="relative group flex items-center">
                        <button class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('peminjaman.*', 'pengembalian.*') ? 'bg-white bg-opacity-20' : '' }}">
                            <i class="fas fa-exchange-alt"></i>
                            <p class="whitespace-nowrap">Transaksi</p>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute text-xs top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                @if(Auth::user()->hasPermission('peminjaman.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('peminjaman.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('peminjaman.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-book-reader w-5"></i>
                                    <span>Peminjaman</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('pengembalian.manage') || Auth::user()->isAdmin())
                                <a href="{{ route('pengembalian.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('pengembalian.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <i class="fas fa-undo w-5"></i>
                                    <span>Pengembalian</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('riwayat-transaksi.view') || Auth::user()->isAdmin())
                                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.riwayat-peminjaman') : route('riwayat-peminjaman.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-history w-5"></i>
                                    <span>Riwayat Peminjaman</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('pengembalian.manage') || Auth::user()->isAdmin())
                                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.riwayat-pengembalian') : route('riwayat-pengembalian.index') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-undo-alt w-5"></i>
                                    <span>Riwayat Pengembalian</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Laporan Dropdown -->
                    @php
                        $hasLaporanPermission = Auth::user()->hasAnyPermission([
                            'laporan.anggota', 'laporan.buku', 'laporan.kas'
                        ]) || Auth::user()->isAdmin();
                    @endphp
                    
                    @if($hasLaporanPermission)
                    <div class="relative group flex items-center">
                        <button class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-white bg-opacity-20' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <p class="whitespace-nowrap">Laporan</p>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute text-xs top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                @if(Auth::user()->hasPermission('laporan.anggota') || Auth::user()->isAdmin())
                                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '#' }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-users w-5"></i>
                                    <span>Laporan Anggota</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('laporan.buku') || Auth::user()->isAdmin())
                                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '#' }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-book w-5"></i>
                                    <span>Laporan Buku</span>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('laporan.kas') || Auth::user()->isAdmin())
                                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '#' }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-money-bill w-5"></i>
                                    <span>Laporan Kas</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pengaturan -->
                    @if(Auth::user()->hasPermission('pengaturan.manage') || Auth::user()->isAdmin())
                    <a href="{{ route('admin.pengaturan') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('admin.pengaturan') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-cog text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Pengaturan</span>
                    </a>
                    @endif
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-2">
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden text-white hover:text-blue-100 p-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 text-white hover:text-blue-100 transition-colors">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->foto && file_exists(public_path('storage/' . auth()->user()->foto)))
                                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" 
                                         alt="Foto Profil" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-xs"></i>
                                @endif
                            </div>
                            <span class="hidden sm:block text-xs">
                                {{ auth()->user()->nama_panggilan ?: auth()->user()->nama_lengkap }}
                            </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-medium text-gray-900">
                                        {{ auth()->user()->nama_panggilan ?: auth()->user()->nama_lengkap }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ auth()->user()->role ? auth()->user()->role->nama_peran : 'Administrator' }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.profil') }}" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <i class="fas fa-user-circle w-5 text-xs"></i>
                                    <span class="text-xs">Profil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5 text-xs"></i>
                                        <span class="text-xs">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden bg-white border-t border-gray-200 hidden">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.dashboard') : route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ (request()->routeIs('admin.dashboard') || request()->routeIs('kepsek.dashboard')) ? 'bg-blue-50 text-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Mobile Data Master -->
                <div class="border-t border-gray-100 pt-2">
                    <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Data Master</div>
                    <a href="{{ route('role.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('role.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-user-shield w-5"></i>
                        <span>Role</span>
                    </a>
                    <a href="{{ route('jurusan.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('jurusan.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-graduation-cap w-5"></i>
                        <span>Data Jurusan</span>
                    </a>
                    <a href="{{ route('kelas.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('kelas.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-chalkboard w-5"></i>
                        <span>Data Kelas</span>
                    </a>
                    <a href="{{ route('jenis-buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('jenis-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-list w-5"></i>
                        <span>Jenis Buku</span>
                    </a>
                    <a href="{{ route('kategori-buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('kategori-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-tags w-5"></i>
                        <span>Kategori Buku</span>
                    </a>
                    <a href="{{ route('rak-buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('rak-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-bookshelf w-5"></i>
                        <span>Rak Buku</span>
                    </a>
                    <a href="{{ route('sumber-buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('sumber-buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-source w-5"></i>
                        <span>Sumber Buku</span>
                    </a>
                    
                </div>
                
                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.data-anggota') : route('anggota.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('anggota.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Data Anggota</span>
                </a>
                
                @if(Auth::user()->hasAnyPermission(['absensi.manage', 'absensi.scan', 'absensi.history']) || Auth::user()->isAdmin())
                                        <a href="{{ route('admin.absensi-pengunjung.index') }}" 
                                        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('admin.absensi-pengunjung.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <i class="fas fa-qrcode w-5"></i>
                    <span>Absensi</span>
                </a>
                @endif
                
                <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.data-buku') : route('buku.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('buku.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <i class="fas fa-book w-5"></i>
                    <span>Data Buku</span>
                </a>
                
                <!-- Mobile Transaksi -->
                <div class="border-t border-gray-100 pt-2">
                    <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</div>
                    <a href="{{ route('peminjaman.index') }}"
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('peminjaman.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-book-reader w-5"></i>
                        <span>Peminjaman</span>
                    </a>
                    <a href="{{ route('pengembalian.index') }}"
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('pengembalian.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-undo w-5"></i>
                        <span>Pengembalian</span>
                    </a>
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.riwayat-peminjaman') : route('riwayat-peminjaman.index') }}"
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('riwayat-peminjaman.*') || request()->routeIs('kepsek.riwayat-peminjaman') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-history w-5"></i>
                        <span>Riwayat Peminjaman</span>
                    </a>
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.riwayat-pengembalian') : route('riwayat-pengembalian.index') }}"
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('riwayat-pengembalian.*') || request()->routeIs('kepsek.riwayat-pengembalian') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-undo-alt w-5"></i>
                        <span>Riwayat Pengembalian</span>
                    </a>
                </div>
                
                <!-- Mobile Laporan -->
                <div class="border-t border-gray-100 pt-2">
                    <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Laporan</div>
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '' }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('laporan.anggota') || request()->routeIs('kepsek.laporan') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Laporan Anggota</span>
                    </a>
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '' }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('laporan.buku') || request()->routeIs('kepsek.laporan') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-book w-5"></i>
                        <span>Laporan Buku</span>
                    </a>
                    <a href="{{ Auth::user()->isKepalaSekolah() ? route('kepsek.laporan') : '' }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('laporan.kas') || request()->routeIs('kepsek.laporan') ? 'bg-blue-50 text-blue-700' : '' }}">
                        <i class="fas fa-money-bill w-5"></i>
                        <span>Laporan Kas</span>
                    </a>
                </div>
                
                @if(Auth::user()->hasPermission('pengaturan.manage') || Auth::user()->isAdmin())
                <a href="{{ route('admin.pengaturan') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors {{ request()->routeIs('admin.pengaturan') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span>Pengaturan Website</span>
                </a>
                @endif
                
                <!-- Mobile User Menu -->
                <div class="border-t border-gray-100 pt-2">
                    <a href="{{ route('admin.profil') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition-colors">
                        <i class="fas fa-user-circle w-5"></i>
                        <span>Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-red-700 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-100">
        
        <!-- Page Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-xl text-center font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
            </div>
        </div>

        <!-- Page Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- Session notifications will be handled by SweetAlert2 -->

            @yield('content')
        </div>
    </div>

    <!-- SweetAlert2 Helper Functions -->
    <script>
        // SweetAlert2 Helper Functions
        function showSuccessAlert(message, title = 'Berhasil!') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        function showErrorAlert(message, title = 'Error!') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: true
            });
        }

        function showWarningAlert(message, title = 'Peringatan!') {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        function showInfoAlert(message, title = 'Informasi!') {
            Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        function showConfirmDialog(message, title = 'Konfirmasi', callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // Session notifications handler
        document.addEventListener('DOMContentLoaded', function() {
            // Handle session success messages
            @if(session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            // Handle session error messages
            @if(session('error'))
                showErrorAlert('{{ session('error') }}');
            @endif

            // Handle session warning messages
            @if(session('warning'))
                showWarningAlert('{{ session('warning') }}');
            @endif

            // Handle session info messages
            @if(session('info'))
                showInfoAlert('{{ session('info') }}');
            @endif
        });

        // JavaScript untuk navbar dan mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            
            // Toggle mobile menu
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        mobileMenuBtn.innerHTML = '<i class="fas fa-times text-xl"></i>';
                    } else {
                        mobileMenu.classList.add('hidden');
                        mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                    }
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                }
            });
            
            // Close mobile menu when clicking on a link
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                });
            });
            
            // Keyboard shortcut untuk close mobile menu (Esc)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 