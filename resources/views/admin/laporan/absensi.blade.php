@extends('layouts.admin')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Section with Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Absensi Pengunjung</h1>
                <p class="text-gray-600 mt-1">Total: {{ $absensi->count() }} kunjungan</p>
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
                
                <select name="jenis" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <option value="">Semua Jenis</option>
                    <option value="anggota" {{ request('jenis') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                    <option value="tamu" {{ request('jenis') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.laporan.absensi', array_merge(request()->query(), ['export' => 'excel'])) }}" 
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
            $totalKunjungan = $absensi->count();
            $kunjunganAnggota = $absensi->where('jenis_pengunjung', 'anggota')->count();
            $kunjunganTamu = $absensi->where('jenis_pengunjung', 'tamu')->count();
        @endphp
        
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kunjungan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalKunjungan }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                    <i class="fas fa-id-card text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Anggota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $kunjunganAnggota }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-purple-100 rounded-full">
                    <i class="fas fa-user-friends text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tamu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $kunjunganTamu }}</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi/Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absensi as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jam_masuk ?: '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jam_keluar ?: '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jenis_pengunjung === 'anggota' && $item->anggota)
                                <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->anggota->nomor_anggota }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_tamu }}</div>
                                <div class="text-xs text-gray-500">{{ $item->no_telepon }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jenis_pengunjung === 'anggota')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Anggota</span>
                            @else
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">Tamu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->jenis_pengunjung === 'anggota' && $item->anggota && $item->anggota->kelas)
                                {{ $item->anggota->kelas->nama_kelas }} - {{ $item->anggota->kelas->jurusan->nama_jurusan }}
                            @else
                                {{ $item->instansi ?: '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->keperluan ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-clipboard-check text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data absensi</h3>
                            <p class="text-gray-600">Tidak ada kunjungan yang sesuai dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection