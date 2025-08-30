<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kepala Sekolah') - SIPERPUS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .profile-dropdown {
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }
        
        .profile-menu:hover .profile-dropdown {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold">SIPERPUS</h1>
                            <p class="text-xs text-purple-100">Kepala Sekolah</p>
                        </div>
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ url('/') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->is('/') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span class="font-medium">Laporan</span>
                    </a>
                    
                    <a href="{{ route('kepsek.data-anggota') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.data-anggota') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="font-medium">Data Anggota</span>
                    </a>
                    
                    <a href="{{ route('kepsek.data-buku') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.data-buku') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="font-medium">Data Buku</span>
                    </a>

                    <a href="{{ route('admin.buku-tamu.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('admin.buku-tamu.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="font-medium">Buku Tamu</span>
                    </a>

                    <a href="{{ route('kepsek.riwayat-peminjaman') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.riwayat-peminjaman') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="font-medium">Riwayat Peminjaman</span>
                    </a>
                    
                    <a href="{{ route('kepsek.riwayat-pengembalian') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.riwayat-pengembalian') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-undo-alt"></i>
                        <span class="font-medium">Riwayat Pengembalian</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" 
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- Right side - Profile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative profile-menu">
                        <button class="flex items-center space-x-3 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-white bg-opacity-30 rounded-full flex items-center justify-center overflow-hidden">
                                @if(Auth::user()->foto && file_exists(public_path('storage/' . Auth::user()->foto)))
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                                         alt="Foto Profil"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-white text-sm"></i>
                                @endif
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium text-white">{{ Auth::user()->nama_panggilan ?: Auth::user()->nama_lengkap }}</div>
                                <div class="text-xs text-purple-100">{{ Auth::user()->role->nama_peran ?? 'Kepala Sekolah' }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-white"></i>
                        </button>
                        
                        <!-- Profile Dropdown Menu -->
                        <div class="profile-dropdown absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden">
                                        @if(Auth::user()->foto && file_exists(public_path('storage/' . Auth::user()->foto)))
                                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                                                 alt="Foto Profil"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-white"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        <div class="text-xs text-purple-600 font-medium">{{ Auth::user()->role->nama_peran ?? 'Kepala Sekolah' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="py-2">
                                <a href="{{ route('kepsek.profil') }}"
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                    <span>Profil Saya</span>
                                </a>
                                
                                <hr class="my-2">
                                
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" 
                                            class="flex w-full items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-purple-700 border-t border-purple-500">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ url('/') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->is('/') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('laporan.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('kepsek.data-anggota') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('kepsek.data-anggota') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Data Anggota</span>
                </a>
                <a href="{{ route('kepsek.data-buku') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('kepsek.data-buku') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-book w-5"></i>
                    <span>Data Buku</span>
                </a>
                <a href="{{ route('admin.buku-tamu.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.buku-tamu.*') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span>Buku Tamu</span>
                </a>
                <a href="{{ route('kepsek.riwayat-peminjaman') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('kepsek.riwayat-peminjaman') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-history w-5"></i>
                    <span>Riwayat Peminjaman</span>
                </a>
                <a href="{{ route('kepsek.riwayat-pengembalian') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('kepsek.riwayat-pengembalian') ? 'bg-purple-600' : '' }}">
                    <i class="fas fa-undo-alt w-5"></i>
                    <span>Riwayat Pengembalian</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </div>
    </main>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Mobile menu toggle script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('laporan.index') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Laporan</span>
                </a>
                
                <a href="{{ route('kepsek.data-anggota') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.data-anggota') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Data Anggota</span>
                </a>
                
                <a href="{{ route('kepsek.data-buku') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.data-buku') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-book w-5"></i>
                    <span>Data Buku</span>
                </a>
                

                
                <a href="{{ route('kepsek.riwayat-peminjaman') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.riwayat-peminjaman') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-history w-5"></i>
                    <span>Riwayat Peminjaman</span>
                </a>
                
                <a href="{{ route('kepsek.riwayat-pengembalian') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.riwayat-pengembalian') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-undo-alt w-5"></i>
                    <span>Riwayat Pengembalian</span>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileButton = document.getElementById('mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !mobileButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // SweetAlert configurations
        window.showAlert = function(type, title, text) {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                confirmButtonColor: '#3B82F6'
            });
        };
        
        window.showConfirm = function(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        };
        
        window.showLoading = function(title = 'Memproses...') {
            Swal.fire({
                title: title,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        };
        
        window.hideLoading = function() {
            Swal.close();
        };
        
        // Toast notification
        window.showToast = function(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type,
                title: message
            });
        };
    </script>
    
    @yield('scripts')
</body>
</html>
