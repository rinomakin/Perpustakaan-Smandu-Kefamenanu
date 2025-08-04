<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Pengunjung - SIPERPUS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">SIPERPUS</h1>
                        <p class="text-blue-100 text-sm">Sistem Perpustakaan</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('frontend.home') }}" class="text-white hover:text-blue-200 font-medium">Beranda</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Tentag</a>
                    <a href="{{ route('petugas.absensi-pengunjung.index') }}" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 font-medium">Absen Pengunjung</a>
                    <a href="{{ route('frontend.koleksi') }}" class="text-white hover:text-blue-200 font-medium">Koleksi Buku</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Title -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-white">Absen Pengunjung</h1>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- QR Scanner -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-black text-white px-4 py-3 rounded-t-lg flex items-center">
                    <i class="fas fa-qrcode mr-2"></i>
                    <span class="font-medium">Scan QR Code Disini</span>
                </div>
                <div class="p-6">
                    <div id="reader" class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <div class="text-center">
                            <i class="fas fa-camera text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">Kamera akan muncul di sini</p>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <button id="startCamera" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-play mr-2"></i>Play Camera
                        </button>
                        <button id="stopCamera" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            <i class="fas fa-stop mr-2"></i>Stop Camera
                        </button>
                    </div>
                </div>
            </div>

            <!-- Visitor Data -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-black text-white px-4 py-3 rounded-t-lg flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    <span class="font-medium">Data Pengunjung Hari Ini</span>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Show</span>
                            <select class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span class="text-sm text-gray-600">entries</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Search:</span>
                            <input type="text" id="searchInput" class="border border-gray-300 rounded px-3 py-1 text-sm" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Kunjungan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($absensiHariIni as $index => $absensi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($absensi->anggota->nama_lengkap, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $absensi->anggota->nama_lengkap }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $absensi->anggota->nomor_anggota }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absensi->anggota->kelas->nama_kelas ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absensi->waktu_masuk->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('petugas.absensi-pengunjung.destroy', $absensi->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-4"></i>
                                        <p>No data available in table</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-700">
                            Showing {{ $absensiHariIni->count() }} to {{ $absensiHariIni->count() }} of {{ $absensiHariIni->count() }} entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Attendance -->
        <div class="mt-8 bg-white rounded-lg shadow-md">
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span class="font-medium">+ Absen Secara Manual</span>
            </div>
            <div class="p-6">
                <form action="{{ route('petugas.absensi-pengunjung.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggota</label>
                            <select name="anggota_id" id="anggota_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">Pilih anggota...</option>
                                @foreach(\App\Models\Anggota::where('status', 'aktif')->get() as $anggota)
                                <option value="{{ $anggota->id }}">
                                    {{ $anggota->nomor_anggota }} - {{ $anggota->nama_lengkap }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Keterangan...">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Catat Absensi
                        </button>
                    </div>
                    
                </form>
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
            <p class="text-gray-400">&copy; {{ date('Y') }} Sistem Perpustakaan SMAN 1 Kefamenanu.</p>
        </div>
    </footer>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 