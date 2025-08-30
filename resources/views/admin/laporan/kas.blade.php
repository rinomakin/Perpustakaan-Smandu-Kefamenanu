@extends('layouts.admin')

@section('title', 'Laporan Kas')
@section('page-title', 'Laporan Kas')

@section('content')
<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Kas Perpustakaan</h1>
                <p class="text-gray-600 mt-1">Total Pemasukan: Rp {{ number_format($kas->sum('total_denda'), 0, ',', '.') }}</p>
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
                
                <a href="{{ route('admin.laporan.kas', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-download mr-2"></i>Excel
                </a>
                
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Total Pemasukan Kas</h3>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($kas->sum('total_denda'), 0, ',', '.') }}</p>
                <p class="text-green-100 text-sm mt-1">Dari {{ $kas->count() }} transaksi pembayaran denda</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-wallet text-3xl"></i>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kas as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->peminjaman->anggota->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $item->peminjaman->anggota->nomor_anggota }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                Denda Keterlambatan
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Pembayaran denda {{ $item->jumlah_hari_terlambat }} hari terlambat
                            <div class="text-xs text-gray-500">{{ $item->peminjaman->nomor_peminjaman }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-green-600 font-bold">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->user->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-wallet text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pemasukan kas</h3>
                            <p class="text-gray-600">Tidak ada transaksi kas yang sesuai dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection