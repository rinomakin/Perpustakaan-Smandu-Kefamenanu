@extends('layouts.admin')

@section('title', 'Laporan Denda')
@section('page-title', 'Laporan Denda')

@section('content')
<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Denda</h1>
                <p class="text-gray-600 mt-1">Total: {{ $denda->count() }} denda</p>
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
                
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.laporan.denda', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-download mr-2"></i>Excel
                </a>
                
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalDenda = $denda->sum('total_denda');
            $dendaSudahBayar = $denda->where('status', 'sudah_bayar')->sum('total_denda');
            $dendaBelumBayar = $denda->where('status', 'belum_bayar')->sum('total_denda');
        @endphp
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Denda</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Bayar</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($dendaSudahBayar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Belum Bayar</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Peminjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari Terlambat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($denda as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->peminjaman->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $item->peminjaman->anggota->nomor_anggota }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $item->peminjaman->nomor_peminjaman }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jumlah_hari_terlambat }} hari</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-red-600 font-medium">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status == 'sudah_bayar')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Sudah Bayar</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data denda</h3>
                            <p class="text-gray-600">Tidak ada denda yang sesuai dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection