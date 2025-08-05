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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar Overlay untuk Mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden sidebar-overlay"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="bg-blue-800 text-white w-64 flex-shrink-0 sidebar-transition lg:translate-x-0 sidebar-mobile z-50">
            <div class="p-4 h-full flex flex-col">
                <!-- Header Sidebar dengan Tombol Close untuk Mobile -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div>
                            <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class="h-12 w-auto">
                        </div>
                        <div>
                            <h1 class="font-bold text-lg">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                            <p class="text-blue-200 text-xs">Admin Panel</p>
                        </div>
                    </div>
                    <!-- Tombol Close untuk Mobile -->
                    <button id="closeSidebar" class="lg:hidden text-white hover:text-blue-200 p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 space-y-2 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="border-t border-blue-700 my-4"></div>
                    
                    <!-- Data Master Dropdown -->
                    <div class="relative">
                        <button id="dataMasterBtn" 
                                class="w-full flex items-center justify-between space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('jurusan.*', 'kelas.*', 'jenis-buku.*', 'sumber-buku.*', 'penerbit.*', 'penulis.*') ? 'bg-blue-700' : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-database w-5"></i>
                                <span>Data Master</span>
                            </div>
                            <i id="dataMasterIcon" class="fas fa-chevron-down w-4 transition-transform"></i>
                        </button>
                        
                        <div id="dataMasterDropdown" class="hidden bg-blue-900 rounded-lg mt-1 ml-4">
                            <a href="{{ route('jurusan.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('jurusan.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-graduation-cap w-5"></i>
                                <span>Data Jurusan</span>
                            </a>
                            
                            <a href="{{ route('kelas.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('kelas.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-chalkboard w-5"></i>
                                <span>Data Kelas</span>
                            </a>
                            
                            <a href="{{ route('jenis-buku.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('jenis-buku.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-list w-5"></i>
                                <span>Jenis Buku</span>
                            </a>
                            
                            <a href="{{ route('sumber-buku.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('sumber-buku.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-source w-5"></i>
                                <span>Sumber Buku</span>
                            </a>
                            
                            <a href="{{ route('penerbit.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('penerbit.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-building w-5"></i>
                                <span>Penerbit</span>
                            </a>
                            
                            <a href="{{ route('penulis.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('penulis.*') ? 'bg-blue-700' : '' }}">
                                <i class="fas fa-user-edit w-5"></i>
                                <span>Penulis</span>
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('anggota.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('anggota.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Data Anggota</span>
                    </a>
                    
                    <a href="{{ route('buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('buku.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-book w-5"></i>
                        <span>Data Buku</span>
                    </a>
                    
                    <a href="{{ route('peminjaman.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('peminjaman.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-exchange-alt w-5"></i>
                        <span>Peminjaman</span>
                    </a>
                    
                    <div class="border-t border-blue-700 my-4"></div>
                    
                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Laporan</span>
                    </a>
                    
                    <a href="{{ route('admin.pengaturan') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors {{ request()->routeIs('admin.pengaturan') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span>Pengaturan Website</span>
                    </a>
                </nav>
                
                <!-- Footer Sidebar -->
                <div class="border-t border-blue-700 pt-4 mt-auto">
                    <div class="text-center text-blue-200 text-xs">
                        <p>&copy; {{ date('Y') }} {{ $pengaturan->nama_website ?? 'SIPERPUS' }}</p>
                        <p>All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Tombol Toggle Sidebar untuk Mobile -->
                    <div class="flex items-center space-x-4">
                        <button id="toggleSidebar" class="lg:hidden text-gray-600 hover:text-gray-800 p-2">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth()->user()->nama_lengkap }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript untuk sidebar dan dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const toggleSidebar = document.getElementById('toggleSidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const dataMasterBtn = document.getElementById('dataMasterBtn');
            const dataMasterDropdown = document.getElementById('dataMasterDropdown');
            const dataMasterIcon = document.getElementById('dataMasterIcon');
            
            // Toggle sidebar untuk mobile
            function openSidebar() {
                sidebar.classList.add('open');
                sidebarOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            
            function closeSidebarFunc() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
            
            // Event listeners untuk sidebar
            if (toggleSidebar) {
                toggleSidebar.addEventListener('click', openSidebar);
            }
            
            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeSidebarFunc);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebarFunc);
            }
            
            // Close sidebar ketika klik link di mobile
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) { // lg breakpoint
                        closeSidebarFunc();
                    }
                });
            });
            
            // Data Master dropdown
            if (dataMasterBtn) {
                dataMasterBtn.addEventListener('click', function() {
                    const isHidden = dataMasterDropdown.classList.contains('hidden');
                    
                    if (isHidden) {
                        dataMasterDropdown.classList.remove('hidden');
                        dataMasterIcon.classList.remove('fa-chevron-down');
                        dataMasterIcon.classList.add('fa-chevron-up');
                    } else {
                        dataMasterDropdown.classList.add('hidden');
                        dataMasterIcon.classList.remove('fa-chevron-up');
                        dataMasterIcon.classList.add('fa-chevron-down');
                    }
                });
            }
            
            // Auto expand Data Master dropdown jika halaman aktif
            if (dataMasterDropdown && window.location.pathname.includes('/jurusan') || 
                window.location.pathname.includes('/kelas') || 
                window.location.pathname.includes('/jenis-buku') || 
                window.location.pathname.includes('/sumber-buku') || 
                window.location.pathname.includes('/penerbit') || 
                window.location.pathname.includes('/penulis')) {
                dataMasterDropdown.classList.remove('hidden');
                dataMasterIcon.classList.remove('fa-chevron-down');
                dataMasterIcon.classList.add('fa-chevron-up');
            }
            
            // Keyboard shortcut untuk toggle sidebar (Esc untuk close)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    closeSidebarFunc();
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 