<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kepala Sekolah') - SIPERPUS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Dropdown styles */
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
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Brand -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <a href="{{ route('kepsek.dashboard') }}" class="flex items-center space-x-2">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold">SIPERPUS</h1>
                                <p class="text-xs text-blue-100">Kepala Sekolah</p>
                            </div>
                        </a>
                    </div>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-2 navbar-menu">
                    <!-- Dashboard -->
                    <a href="{{ route('kepsek.dashboard') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Laporan -->
                    <a href="{{ route('kepsek.laporan') }}" 
                       class="flex text-xs items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.laporan') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                    
                    <!-- Data Anggota -->
                    <a href="{{ route('kepsek.data-anggota') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.data-anggota') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-users text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Data Anggota</span>
                    </a>
                    
                    <!-- Data Buku -->
                    <a href="{{ route('kepsek.data-buku') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.data-buku') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-book text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Data Buku</span>
                    </a>



                    <!-- Riwayat Peminjaman -->
                    <a href="{{ route('kepsek.riwayat-peminjaman') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.riwayat-peminjaman') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-history text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Riwayat Peminjaman</span>
                    </a>
                    
                    <!-- Riwayat Pengembalian -->
                    <a href="{{ route('kepsek.riwayat-pengembalian') }}" 
                       class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors {{ request()->routeIs('kepsek.riwayat-pengembalian') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-undo-alt text-xs"></i>
                        <span class="whitespace-nowrap text-xs">Riwayat Pengembalian</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" 
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- Right side - User info -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->nama_lengkap }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <div class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->role->nama_peran ?? 'Kepala Sekolah' }}</div>
                                </div>
                                
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-blue-700 border-t border-blue-500">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('kepsek.dashboard') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.dashboard') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('kepsek.laporan') }}" 
                   class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors {{ request()->routeIs('kepsek.laporan') ? 'bg-blue-600' : '' }}">
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
