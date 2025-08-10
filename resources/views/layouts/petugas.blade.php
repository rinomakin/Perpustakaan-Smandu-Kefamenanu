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
                <a href="{{ route('petugas.dashboard') }}">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class="h-10 w-auto">
                            <div>
                                <h1 class="font-bold text-xs w-10 whitespace-nowrap">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                                <p class="text-blue-100 text-xs whitespace-nowrap">Petugas Panel</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-2 navbar-menu">
                    <!-- Dashboard -->
                    <a href="{{ route('petugas.dashboard') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Beranda -->
                    <a href="{{ route('petugas.beranda') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.beranda') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Beranda</span>
                    </a>

                    <!-- Tentang -->
                    <a href="{{ route('petugas.tentang') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.tentang') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <span>Tentang</span>
                    </a>

                    <!-- Absensi Pengunjung -->
                    <a href="{{ route('petugas.absensi-pengunjung.index') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('petugas.absensi-pengunjung.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Absensi</span>
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                            <i class="fas fa-bell text-sm"></i>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center overflow-hidden">
                                @if(Auth::user()->foto && file_exists(public_path('storage/' . Auth::user()->foto)))
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                                         alt="Foto Profil"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-sm"></i>
                                @endif
                            </div>
                            <span class="text-sm font-medium">{{ Auth::user()->nama_panggilan ?: Auth::user()->nama_lengkap }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="{{ route('petugas.profil') }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                    <span class="text-xs">Profil</span>
                                </a>
                                
                                <hr class="my-2">
                                
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" 
                                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        <span class="text-xs">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
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
                
                <a href="{{ route('petugas.absensi-pengunjung.index') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    <span>Absensi</span>
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
