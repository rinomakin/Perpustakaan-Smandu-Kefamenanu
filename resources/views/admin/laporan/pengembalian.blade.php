@extends('layouts.admin')

@section('title', 'Laporan Pengembalian')
@section('page-title', 'Laporan Pengembalian')

@section('content')
<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Pengembalian</h1>
                <p class="text-gray-600 mt-1">Total: {{ $pengembalian->count() }} transaksi</p>
            </div>
            
            <!-- Filter Form -->
            <form method="GET" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.laporan.pengembalian', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-download mr-2"></i>Excel
                </a>
                
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Pengembalian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pengembalian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Denda</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengembalian as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $item->nomor_pengembalian }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $item->detailPengembalian->count() }} Buku
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jumlah_hari_terlambat > 0)
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat {{ $item->jumlah_hari_terlambat }} hari</span>
                            @else
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->total_denda > 0)
                                <span class="text-red-600 font-medium">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-green-600 font-medium">Rp 0</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pengembalian</h3>
                            <p class="text-gray-600">Tidak ada pengembalian yang sesuai dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection