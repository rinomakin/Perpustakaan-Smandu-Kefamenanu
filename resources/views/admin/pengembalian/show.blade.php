@extends('layouts.admin')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="min-h-screen bg-gradient-to-br py-8">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pengembalian</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap pengembalian buku</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pengembalian.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button onclick="window.print()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Pengembalian -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Informasi Pengembalian</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Pengembalian</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->nomor_pengembalian }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Status</h4>
                                @if($pengembalian->status === 'selesai')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Selesai
                                    </span>
                                @elseif($pengembalian->jumlah_hari_terlambat > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Terlambat {{ $pengembalian->jumlah_hari_terlambat }} hari
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ ucfirst($pengembalian->status) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Pengembalian</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->tanggal_pengembalian->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $pengembalian->jam_pengembalian ? $pengembalian->jam_pengembalian->format('H:i') : '-' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Petugas</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->user->nama_lengkap ?? $pengembalian->user->name }}</p>
                            </div>
                        </div>

                        @if($pengembalian->catatan)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Catatan</h4>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $pengembalian->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Anggota -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Informasi Anggota</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->anggota->nama_lengkap }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Anggota</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->anggota->nomor_anggota }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Kelas</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->anggota->kelas->nama_kelas ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis Anggota</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($pengembalian->anggota->jenis_anggota) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Buku -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Detail Buku Dikembalikan</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pengembalian->detailPengembalian as $detail)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $detail->buku->judul_buku }}</div>
                                            <div class="text-sm text-gray-500">{{ $detail->buku->pengarang ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $detail->buku->kategoriBuku->nama_kategori ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $detail->jumlah_dikembalikan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $detail->getKondisiClass() }}">
                                                {{ $detail->getKondisiLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($detail->denda_buku > 0)
                                                <div class="text-sm font-medium text-red-600">Rp {{ number_format($detail->denda_buku, 0, ',', '.') }}</div>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informasi Denda -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Informasi Denda</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Total Denda</h4>
                                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Status Pembayaran</h4>
                                @if($pengembalian->status_denda === 'sudah_dibayar')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Sudah Dibayar
                                    </span>
                                @elseif($pengembalian->status_denda === 'belum_dibayar')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum Dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus mr-1"></i>
                                        Tidak Ada Denda
                                    </span>
                                @endif
                            </div>
                            
                            @if($pengembalian->jumlah_hari_terlambat > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Keterlambatan</h4>
                                <p class="text-lg font-semibold text-red-600">{{ $pengembalian->jumlah_hari_terlambat }} hari</p>
                            </div>
                            @endif
                            
                            @if($pengembalian->tanggal_pembayaran_denda)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Pembayaran</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->tanggal_pembayaran_denda->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Peminjaman -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Informasi Peminjaman</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Peminjaman</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->peminjaman->nomor_peminjaman ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Pinjam</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->peminjaman->tanggal_peminjaman->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Harus Kembali</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $pengembalian->peminjaman->tanggal_harus_kembali->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
