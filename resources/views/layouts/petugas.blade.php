<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pengaturan->nama_website ?? 'SIPERPUS' }} - Petugas</title>
    
    @if($pengaturan && $pengaturan->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $pengaturan->favicon) }}">
    @endif
    
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
    <nav class="bg-gradient-to-r from-green-600 to-teal-700 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('petugas.dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset($pengaturan->logo ?? 'images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                        <div>
                            <h1 class="font-bold text-lg">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                            <p class="text-xs text-green-100">Petugas Panel</p>
                        </div>
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('petugas.dashboard') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('petugas.beranda') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.beranda') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-home"></i>
                        <span class="font-medium">Beranda</span>
                    </a>

                    <a href="{{ route('petugas.tentang') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.tentang') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-medium">Tentang</span>
                    </a>

                    <a href="{{ route('petugas.buku-tamu.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.buku-tamu.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="font-medium">Buku Tamu</span>
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
                                <div class="text-xs text-green-100">{{ Auth::user()->role->nama_peran ?? 'Petugas' }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-white"></i>
                        </button>
                        
                        <!-- Profile Dropdown Menu -->
                        <div class="profile-dropdown absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center overflow-hidden">
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
                                        <div class="text-xs text-green-600 font-medium">{{ Auth::user()->role->nama_peran ?? 'Petugas' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="py-2">
                                <a href="{{ route('petugas.profil') }}"
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
        <div id="mobileMenu" class="hidden md:hidden bg-green-700 border-t border-green-500">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('petugas.dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-green-600 transition-colors {{ request()->routeIs('petugas.dashboard') ? 'bg-green-600' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('petugas.beranda') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-green-600 transition-colors {{ request()->routeIs('petugas.beranda') ? 'bg-green-600' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('petugas.tentang') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-green-600 transition-colors {{ request()->routeIs('petugas.tentang') ? 'bg-green-600' : '' }}">
                    <i class="fas fa-info-circle w-5"></i>
                    <span>Tentang</span>
                </a>
                <a href="{{ route('petugas.buku-tamu.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-green-600 transition-colors {{ request()->routeIs('petugas.buku-tamu.*') ? 'bg-green-600' : '' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span>Buku Tamu</span>
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
                    </div>

                    <!-- Mobile menu button -->
                    <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-blue-700">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ route('petugas.dashboard') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('petugas.beranda') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-home mr-3"></i>
                    <span>Beranda</span>
                </a>
                
                <a href="{{ route('petugas.tentang') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-info-circle mr-3"></i>
                    <span>Tentang</span>
                </a>
                
                <a href="{{ route('petugas.buku-tamu.index') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-book mr-3"></i>
                    <span>Buku Tamu</span>
                </a>
                
                <hr class="border-blue-600 my-2">
                
                <a href="{{ route('petugas.profil') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Profil</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="flex w-full items-center px-3 py-2 text-sm font-medium text-red-300 hover:bg-red-600 hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });

        // Global confirm dialog function
        function showConfirmDialog(message, title, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }

        // Global delete confirmation
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            showConfirmDialog(message, 'Konfirmasi Hapus', function() {
                document.getElementById(formId).submit();
            });
        }
    </script>
</body>
</html>
