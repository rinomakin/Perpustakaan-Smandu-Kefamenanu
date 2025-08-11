@extends('layouts.admin')

@section('title', 'Riwayat Pengembalian')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Riwayat Pengembalian</h1>
                    <p class="text-gray-600 mt-1">Lihat dan filter riwayat pengembalian buku</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('riwayat-pengembalian.export') }}?{{ http_build_query(request()->all()) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                    <a href="{{ route('pengembalian.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Filter & Pencarian</h3>
            </div>
            
            <form method="GET" action="{{ route('riwayat-pengembalian.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nomor pengembalian, nama anggota..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Status Terlambat -->
                    <div>
                        <label for="status_terlambat" class="block text-sm font-medium text-gray-700 mb-2">Status Terlambat</label>
                        <select name="status_terlambat" id="status_terlambat" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Semua Status</option>
                            <option value="terlambat" {{ request('status_terlambat') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="tepat_waktu" {{ request('status_terlambat') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        </select>
                    </div>

                    <!-- Anggota -->
                    <div>
                        <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <select name="anggota_id" id="anggota_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Semua Anggota</option>
                            @foreach($anggota as $member)
                            <option value="{{ $member->id }}" {{ request('anggota_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->nama_lengkap }} - {{ $member->nomor_anggota }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buku -->
                    <div>
                        <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">Buku</label>
                        <select name="buku_id" id="buku_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Semua Buku</option>
                            @foreach($buku as $book)
                            <option value="{{ $book->id }}" {{ request('buku_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->judul_buku }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                               value="{{ request('tanggal_mulai') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                               value="{{ request('tanggal_akhir') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" 
                               value="{{ request('jam_mulai') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Jam Akhir -->
                    <div>
                        <label for="jam_akhir" class="block text-sm font-medium text-gray-700 mb-2">Jam Akhir</label>
                        <input type="time" name="jam_akhir" id="jam_akhir" 
                               value="{{ request('jam_akhir') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex justify-between items-center mt-6">
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('riwayat-pengembalian.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-refresh mr-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalPengembalian = \App\Models\Pengembalian::count();
                $totalTerlambat = \App\Models\Pengembalian::where('jumlah_hari_terlambat', '>', 0)->count();
                $totalDenda = \App\Models\Pengembalian::sum('total_denda');
                $pengembalianHariIni = \App\Models\Pengembalian::whereDate('tanggal_pengembalian', today())->count();
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Dikembalikan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPengembalian }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-clock text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Terlambat</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTerlambat }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Denda</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pengembalianHariIni }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Riwayat Pengembalian</h3>
            </div>
            
            <div class="p-6">
                @if($pengembalian->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pengembalian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pengembalian as $return)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $return->nomor_pengembalian }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $return->anggota->nama_lengkap }}</div>
                                                <div class="text-sm text-gray-500">{{ $return->anggota->nomor_anggota }}</div>
                                                <div class="text-xs text-gray-400">{{ $return->anggota->kelas->nama_kelas ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $return->jumlah_buku }} buku
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $return->tanggal_pengembalian ? $return->tanggal_pengembalian->format('d/m/Y') : '-' }}</div>
                                        <div class="text-sm text-gray-500">{{ $return->jam_pengembalian ? $return->jam_pengembalian->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($return->jumlah_hari_terlambat > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-clock mr-1"></i>Terlambat {{ $return->jumlah_hari_terlambat }} hari
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Tepat Waktu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($return->total_denda > 0)
                                            <span class="text-sm font-medium text-red-600">Rp {{ number_format($return->total_denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $return->user->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('pengembalian.show', $return->id) }}" 
                                           class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pengembalian->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-inbox text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pengembalian</h3>
                        <p class="text-gray-500">Belum ada riwayat pengembalian buku yang ditemukan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
