@extends('layouts.admin')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Riwayat Peminjaman</h1>
                    <p class="text-gray-600 mt-1">Lihat dan filter riwayat peminjaman buku</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('riwayat-peminjaman.export') }}?{{ http_build_query(request()->all()) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                    <a href="{{ route('peminjaman.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Filter & Pencarian</h3>
            </div>
            
            <form method="GET" action="{{ route('riwayat-peminjaman.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nomor peminjaman, nama anggota..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Anggota -->
                    <div>
                        <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <select name="anggota_id" id="anggota_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                               value="{{ request('tanggal_akhir') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" 
                               value="{{ request('jam_mulai') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Jam Akhir -->
                    <div>
                        <label for="jam_akhir" class="block text-sm font-medium text-gray-700 mb-2">Jam Akhir</label>
                        <input type="time" name="jam_akhir" id="jam_akhir" 
                               value="{{ request('jam_akhir') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    
                    <a href="{{ route('riwayat-peminjaman.index') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times mr-2"></i>Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-history text-white text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Daftar Riwayat Peminjaman</h3>
                    </div>
                    <div class="text-white text-sm">
                        Total: {{ $peminjaman->total() }} data
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nomor Peminjaman</th>
                                <th scope="col" class="px-6 py-3">Anggota</th>
                                <th scope="col" class="px-6 py-3">Tanggal Pinjam</th>
                                <th scope="col" class="px-6 py-3">Jam Pinjam</th>
                                <th scope="col" class="px-6 py-3">Batas Kembali</th>
                                <th scope="col" class="px-6 py-3">Tanggal Kembali</th>
                                <th scope="col" class="px-6 py-3">Jam Kembali</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Petugas</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $loan)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $loan->nomor_peminjaman }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $loan->anggota->nama_lengkap }}</div>
                                    <div class="text-gray-500 text-xs">{{ $loan->anggota->nomor_anggota }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->tanggal_peminjaman ? $loan->tanggal_peminjaman->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->jam_peminjaman ? $loan->jam_peminjaman->format('H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->tanggal_harus_kembali ? $loan->tanggal_harus_kembali->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->jam_kembali ? $loan->jam_kembali->format('H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($loan->status == 'dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Dipinjam</span>
                                    @elseif($loan->status == 'dikembalikan')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Dikembalikan</span>
                                    @elseif($loan->status == 'terlambat')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('peminjaman.show', $loan->id) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        <a href="{{ route('peminjaman.edit', $loan->id) }}" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center py-8">
                                        <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                                        <p>Tidak ada data riwayat peminjaman</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($peminjaman->hasPages())
                <div class="mt-6">
                    {{ $peminjaman->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 