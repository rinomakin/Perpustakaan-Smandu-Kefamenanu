@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 no-print">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Peminjaman</h1>
                <p class="text-gray-600 mt-1">Total: {{ $peminjaman->count() }} transaksi</p>
            </div>
            
            <!-- Filter Form -->
            <form method="GET" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.laporan.peminjaman', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>Excel
                </a>
                
                <button onclick="window.print()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
                
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 no-print">
        @php
            $totalPeminjaman = $peminjaman->count();
            $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
            $sudahDikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
            $terlambat = $peminjaman->where('status', 'terlambat')->count();
        @endphp
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <i class="fas fa-book-reader text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $sedangDipinjam }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Dikembalikan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $sudahDikembalikan }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $terlambat }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="print-area">
        <!-- Print Header -->
        <div class="hidden print:block mb-6 text-center">
            <h1 class="text-xl font-bold">LAPORAN DATA PEMINJAMAN</h1>
            <h2 class="text-lg">PERPUSTAKAAN SMAN 2 KEFAMENANU</h2>
            @if(request('tanggal_mulai') && request('tanggal_akhir'))
                <p class="text-sm mt-2">Periode: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</p>
            @endif
            <hr class="mt-4 mb-6">
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->nomor_peminjaman }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                                @if($item->anggota->kelas)
                                    <div class="text-xs text-gray-500">{{ $item->anggota->kelas->nama_kelas }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->detailPeminjaman->count() }} Buku
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status == 'dipinjam')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Sedang Dipinjam</span>
                                @elseif($item->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Sudah Dikembalikan</span>
                                @elseif($item->status == 'terlambat')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->user->name ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-book-reader text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
                                <p class="text-gray-600">Tidak ada peminjaman yang sesuai dengan filter yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Footer -->
        <div class="hidden print:block mt-6 text-center">
            <p class="text-sm">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 no-print">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Peminjaman</h1>
                <p class="text-gray-600 mt-1">Total: {{ $peminjaman->count() }} transaksi</p>
            </div>
            
            <!-- Filter Form -->
            <form method="GET" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.laporan.peminjaman', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>Excel
                </a>
                
                <button onclick="window.print()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
                
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 no-print">
        @php
            $totalPeminjaman = $peminjaman->count();
            $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
            $sudahDikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
            $terlambat = $peminjaman->where('status', 'terlambat')->count();
        @endphp
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <i class="fas fa-book-reader text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $sedangDipinjam }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Dikembalikan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $sudahDikembalikan }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $terlambat }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="print-area">
        <!-- Print Header -->
        <div class="hidden print:block mb-6 text-center">
            <h1 class="text-xl font-bold">LAPORAN DATA PEMINJAMAN</h1>
            <h2 class="text-lg">PERPUSTAKAAN SMAN 2 KEFAMENANU</h2>
            @if(request('tanggal_mulai') && request('tanggal_akhir'))
                <p class="text-sm mt-2">Periode: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</p>
            @endif
            <hr class="mt-4 mb-6">
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->nomor_peminjaman }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                                @if($item->anggota->kelas)
                                    <div class="text-xs text-gray-500">{{ $item->anggota->kelas->nama_kelas }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $item->detailPeminjaman->count() }} Buku
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status == 'dipinjam')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Sedang Dipinjam</span>
                                @elseif($item->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Sudah Dikembalikan</span>
                                @elseif($item->status == 'terlambat')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->user->name ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-book-reader text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
                                <p class="text-gray-600">Tidak ada peminjaman yang sesuai dengan filter yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Footer -->
        <div class="hidden print:block mt-6 text-center">
            <p class="text-sm">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection