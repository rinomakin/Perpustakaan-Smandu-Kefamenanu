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
                @if(Auth::user()->hasPermission('riwayat-transaksi.view') || Auth::user()->isAdmin())
                <a href="{{ route('riwayat-pengembalian.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-history mr-2"></i>Riwayat Pengembalian
                </a>
                @endif
                @if(Auth::user()->hasPermission('pengembalian.create') || Auth::user()->isAdmin())
                <a href="{{ route('pengembalian.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>Proses Pengembalian
                </a>
                @endif
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Denda</th>
                                    @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pengembalian as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $item->nomor_pengembalian }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $item->detailPengembalian->count() }} Buku
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d/m/Y') : 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->jam_pengembalian ?? 'N/A' }}</div>
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
                                    @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin() || Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            @if(Auth::user()->hasPermission('pengembalian.show') || Auth::user()->isAdmin())
                                            <a href="{{ route('pengembalian.show', $item->id) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('pengembalian.edit') || Auth::user()->isAdmin())
                                            <a href="{{ route('pengembalian.edit', $item->id) }}" 
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('pengembalian.delete') || Auth::user()->isAdmin())
                                            <button type="button" onclick="confirmDelete({{ $item->id }})" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-undo-alt text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pengembalian hari ini</h3>
                        <p class="text-gray-600">Belum ada buku yang dikembalikan hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(pengembalianId) {
    if (confirm('Apakah Anda yakin ingin menghapus data pengembalian ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pengembalian/${pengembalianId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
