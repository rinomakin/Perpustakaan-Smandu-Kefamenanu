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
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <!-- Logo dan Nama -->
                <div class="flex items-center gap-3 justify-center  mb-4">
                    <div>

                        <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class=" h-16 w-auto mx-auto mb-4">
                    </div>
                        <div>
                        <h1 class="font-bold text-lg ">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                        <p class="text-blue-200 text-xs">Admin Panel</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="border-t border-blue-700 my-4"></div>
                    
                    <!-- Data Master Dropdown -->
                    <div class="relative">
                        <button id="dataMasterBtn" 
                                class="w-full flex items-center justify-between space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-database w-5"></i>
                                <span>Data Master</span>
                            </div>
                            <i id="dataMasterIcon" class="fas fa-chevron-down w-4 transition-transform"></i>
                        </button>
                        
                        <div id="dataMasterDropdown" class="hidden bg-blue-900 rounded-lg mt-1 ml-4">
                            <a href="{{ route('jurusan.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-graduation-cap w-5"></i>
                                <span>Data Jurusan</span>
                            </a>
                            
                            <a href="{{ route('kelas.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-chalkboard w-5"></i>
                                <span>Data Kelas</span>
                            </a>
                            
                            <a href="{{ route('jenis-buku.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-list w-5"></i>
                                <span>Jenis Buku</span>
                            </a>
                            
                            <a href="{{ route('sumber-buku.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-source w-5"></i>
                                <span>Sumber Buku</span>
                            </a>
                            
                            <a href="{{ route('penerbit.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-building w-5"></i>
                                <span>Penerbit</span>
                            </a>
                            
                            <a href="{{ route('penulis.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-user-edit w-5"></i>
                                <span>Penulis</span>
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('anggota.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-users w-5"></i>
                        <span>Data Anggota</span>
                    </a>
                    
                    <a href="{{ route('buku.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-book w-5"></i>
                        <span>Data Buku</span>
                    </a>
                    
                    <a href="{{ route('peminjaman.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-exchange-alt w-5"></i>
                        <span>Peminjaman</span>
                    </a>
                    
                    <div class="border-t border-blue-700 my-4"></div>
                    
                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Laporan</span>
                    </a>
                    
                    <a href="{{ route('admin.pengaturan') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-cog w-5"></i>
                        <span>Pengaturan Website</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    
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

    <!-- JavaScript untuk dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataMasterBtn = document.getElementById('dataMasterBtn');
            const dataMasterDropdown = document.getElementById('dataMasterDropdown');
            const dataMasterIcon = document.getElementById('dataMasterIcon');
            
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
        });
    </script>
    
    @stack('scripts')
</body>
</html> 