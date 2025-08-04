<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku - SIPERPUS</title>
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
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">SIPERPUS</h1>
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
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Cari buku apa? yuk bisa cari langsung disini ...</h2>
            
            <form action="{{ route('frontend.cari.buku') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Cari Buku Berdasarkan</option>
                        <option value="judul">Judul Buku</option>
                        <option value="penulis">Penulis</option>
                        <option value="penerbit">Penerbit</option>
                        <option value="kategori">Kategori</option>
                        <option value="isbn">ISBN</option>
                    </select>
                </div>
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="q" placeholder="Ketikkan kata kunci disini..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Book Categories -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Kategori Buku</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                <!-- Buku Teks Pelajaran -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book text-red-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Buku Teks Pelajaran</p>
                </div>

                <!-- Buku Bacaan -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-books text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Buku Bacaan</p>
                </div>

                <!-- Buku Penunjang Ujian -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Buku Penunjang Ujian</p>
                </div>

                <!-- Buku Penunjang Pelajaran -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-graduation-cap text-orange-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Buku Penunjang Pelajaran</p>
                </div>

                <!-- Kamus Umum -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-brown-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book text-amber-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Kamus Umum</p>
                </div>

                <!-- Kamus Produktif -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Kamus Produktif</p>
                </div>

                <!-- Al-Qur'an -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book text-green-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Al-Qur'an</p>
                </div>

                <!-- Ensiklopedia -->
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition duration-200 cursor-pointer">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-search text-purple-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Ensiklopedia</p>
                </div>
            </div>
        </div>

        <!-- Favorite Books Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Buku Terfavorit</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($bukuTerbaru ?? [] as $buku)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                        <i class="fas fa-book text-gray-400 text-4xl"></i>
                        <div class="absolute top-2 right-2 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-info text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $buku->judul_buku }}</h4>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                <span>{{ $buku->penulis->nama_penulis }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-building mr-2 text-gray-400"></i>
                                <span>{{ $buku->penerbit->nama_penerbit }}</span>
                            </div>
                            @if($buku->tahun_terbit)
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                <span>{{ $buku->tahun_terbit }}</span>
                            </div>
                            @endif
                        </div>
                        @if($buku->lokasi_rak)
                        <div class="mt-3 p-2 bg-blue-50 rounded">
                            <div class="flex items-center text-sm text-blue-800">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span><strong>Lokasi:</strong> {{ $buku->lokasi_rak }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-sm text-gray-500">Stok: {{ $buku->stok_tersedia }}/{{ $buku->jumlah_stok }}</span>
                            @if($buku->stok_tersedia > 0)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tersedia</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada buku tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
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

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition duration-200 opacity-0 invisible">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                scrollToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
                scrollToTopBtn.classList.remove('opacity-100', 'visible');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html> 