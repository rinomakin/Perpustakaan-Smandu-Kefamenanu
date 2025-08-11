@extends('layouts.admin')

@section('title', 'Data Pengembalian')

@section('content')
<div class="min-h-screen bg-gradient-to-br py-8">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Data Pengembalian Hari Ini</h1>
                <p class="text-gray-600 mt-2">Riwayat pengembalian buku perpustakaan untuk hari ini ({{ date('d/m/Y') }})</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('riwayat-pengembalian.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-history mr-2"></i>Riwayat Pengembalian
                </a>
                <a href="{{ route('pengembalian.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>Proses Pengembalian
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalPengembalianHariIni = $pengembalian->count();
                $totalTerlambatHariIni = $pengembalian->where('jumlah_hari_terlambat', '>', 0)->count();
                $totalDendaHariIni = $pengembalian->sum('total_denda');
                $totalBukuDikembalikan = $pengembalian->sum(function($item) {
                    return $item->detailPengembalian->count();
                });
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Dikembalikan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPengembalianHariIni }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTerlambatHariIni }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDendaHariIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Buku</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalBukuDikembalikan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Data Pengembalian Hari Ini</h3>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pengembalian as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nomor_pengembalian }}</div>
                                        <div class="text-sm text-gray-500">No. Peminjaman: {{ $item->peminjaman->nomor_peminjaman }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                                        @if($item->anggota->kelas)
                                            <div class="text-xs text-gray-400">{{ $item->anggota->kelas->nama_kelas }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $item->detailPengembalian->count() }} Buku
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->tanggal_pengembalian->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->jam_pengembalian ? $item->jam_pengembalian->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status === 'selesai')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Selesai
                                            </span>
                                        @elseif($item->jumlah_hari_terlambat > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Terlambat {{ $item->jumlah_hari_terlambat }} hari
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->total_denda > 0)
                                            <div class="text-sm font-medium text-red-600">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->status_denda === 'sudah_dibayar' ? 'Lunas' : 'Belum Bayar' }}</div>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('pengembalian.show', $item->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pengembalian->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Pengembalian</h3>
                        <p class="text-gray-600 mb-6">Mulai proses pengembalian buku untuk melihat data di sini.</p>
                        <a href="{{ route('pengembalian.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Proses Pengembalian
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function viewDetail(id) {
    // Implementation for viewing detail can be added here
    console.log('View detail for ID:', id);
}
</script>
@endsection
