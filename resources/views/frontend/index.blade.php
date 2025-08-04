<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERPUS - Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class="h-12 w-auto">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</h1>
                        <p class="text-blue-100 text-sm">Sistem Perpustakaan</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('frontend.home') }}" class="text-white hover:text-blue-200 font-medium">Beranda</a>
                    <div class="relative group">
                        <a href="#" class="text-white hover:text-blue-200 font-medium flex items-center">
                            Tentang <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                    </div>
                    
                    <a href="{{ route('petugas.absensi-pengunjung.index') }}" class="text-white hover:text-blue-200 font-medium">Absen Pengunjung</a>
                    <a href="{{ route('frontend.koleksi') }}" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 font-medium">Koleksi Buku</a>
                    <a class="flex items-center  justify-center gap-2 cursor-pointer">
                        <p class= "text-white  font-medium cursor-pointer" ">Petugas</p>
                        <i class="fas fa-user text-white mr-2"></i>
                    </a>
                    
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 min-h-screen flex items-center justify-center text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
            <!-- School Logo -->
            <div class="mb-8">
                <div class="w-32 h-32 bg-green-500 rounded-full border-4 border-yellow-400 mx-auto flex items-center justify-center mb-4">
                    <div class="text-center">
                        <i class="fas fa-book text-white text-3xl mb-2"></i>
                        <div class="text-xs font-bold">{{ $pengaturan->nama_website ?? 'SIPERPUS' }}</div>
                    </div>
                </div>
                <div class="text-xs text-center">
                    <div>SEKOLAH MENENGAH KEJURUAN</div>
                    <div class="font-bold">SOEDIRMAN PURBALINGGA</div>
                </div>
            </div>

            <!-- Welcome Text -->
            <h1 class="text-5xl font-bold mb-4">Selamat Datang</h1>
            <h2 class="text-3xl font-semibold mb-6">Di Perpustakaan SMAN 1 Kefamenanu</h2>
            
            <!-- Description -->
            <p class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed mb-8">
                Perpustakaan SMAN 1 Kefamenanu merupakan layanan yang diberikan kepada civitas akademik khususnya siswa/i untuk memperoleh informasi seperti buku teks pelajaran, buku bacaan, kamus umum, sampai ensiklopedia.
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('frontend.koleksi') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Cari Buku
                </a>
                <a href="{{ route('frontend.tentang') }}" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold">
                    <i class="fas fa-info-circle mr-2"></i>Tentang Kami
                </a>
            </div>
        </div>

        <!-- Navigation Dots -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
            <div class="w-3 h-3 bg-white rounded-full opacity-50"></div>
            <div class="w-3 h-3 bg-white rounded-full opacity-50"></div>
        </div>
    </section>

    <!-- Quick Access -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Akses Cepat</h2>
                <p class="text-lg text-gray-600">Temukan informasi yang Anda butuhkan dengan mudah</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-8 text-white text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Cari Buku</h3>
                    <p class="text-blue-100 mb-4">Temukan buku yang Anda butuhkan</p>
                    <a href="{{ route('frontend.koleksi') }}" class="inline-block bg-white text-blue-600 px-6 py-2 rounded-md font-medium">
                        Mulai Pencarian
                    </a>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-8 text-white text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Absen Pengunjung</h3>
                    <p class="text-green-100 mb-4">Daftarkan kehadiran Anda</p>
                    <a href="{{ route('petugas.absensi-pengunjung.index') }}" class="inline-block bg-white text-green-600 px-6 py-2 rounded-md font-medium">
                        Absen Sekarang
                    </a>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-8 text-white text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Tentang Kami</h3>
                    <p class="text-purple-100 mb-4">Pelajari lebih lanjut tentang perpustakaan</p>
                    <a href="{{ route('frontend.tentang') }}" class="inline-block bg-white text-purple-600 px-6 py-2 rounded-md font-medium">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-white"></i>
                </div>
                <span class="text-xl font-bold">SIPERPUS</span>
            </div>
            <p class="text-gray-400">&copy; {{ date('Y') }} Sistem Perpustakaan SMAN 1 Kefamenanu. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 