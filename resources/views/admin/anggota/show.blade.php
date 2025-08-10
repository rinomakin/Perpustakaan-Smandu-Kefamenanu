@extends('layouts.admin')

@section('title', 'Detail Anggota')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Detail Anggota
    </h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">{{ $anggota->nama_lengkap }}</h3>
            <div class="flex space-x-2">
                @if(Auth::user()->hasPermission('anggota.update'))
                <a href="{{ route('anggota.edit', $anggota->id) }}"
                   class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @endif
                @if(Auth::user()->hasPermission('anggota.cetak-kartu'))
                <a href="{{ route('anggota.cetak-kartu', $anggota->id) }}" target="_blank"
                   class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                    <i class="fas fa-print mr-1"></i>Cetak Kartu
                </a>
                @endif
                <a href="{{ route('anggota.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Pribadi -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-lg font-semibold mb-4 text-gray-800">Informasi Pribadi</h4>
                
                <div class="flex items-center mb-4">
                    @if($anggota->foto)
                        <img src="{{ asset('storage/anggota/' . $anggota->foto) }}" 
                             alt="Foto" class="w-20 h-20 rounded-full mr-4 object-cover">
                    @else
                        <div class="w-20 h-20 bg-gray-300 rounded-full mr-4 flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="text-xl font-semibold">{{ $anggota->nama_lengkap }}</div>
                        <div class="text-sm text-gray-600">{{ ucfirst($anggota->jenis_anggota) }}</div>
                        <div class="text-sm text-gray-500 mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $anggota->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                <i class="fas {{ $anggota->jenis_kelamin == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                {{ $anggota->jenis_kelamin ?: '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <div class="text-gray-900">{{ $anggota->nik }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="text-gray-900">{{ $anggota->nomor_telepon }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="text-gray-900">{{ $anggota->email ?: '-' }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <div class="text-gray-900">{{ $anggota->alamat }}</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Keanggotaan -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-lg font-semibold mb-4 text-gray-800">Informasi Keanggotaan</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Anggota</label>
                        <div class="text-gray-900 font-mono">{{ $anggota->nomor_anggota }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Barcode</label>
                        <div class="text-center bg-white p-3 border rounded">
                            <img src="data:image/png;base64,{{ \App\Helpers\BarcodeHelper::generateBarcodeImage($anggota->barcode_anggota, 'C128') }}" 
                                 alt="Barcode" class="mx-auto mb-2" style="max-width: 200px; height: auto;">
                            <div class="text-gray-900 font-mono text-sm">{{ $anggota->barcode_anggota }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                            {{ $anggota->status == 'aktif' ? 'text-green-700 bg-green-100' : 
                               ($anggota->status == 'nonaktif' ? 'text-red-700 bg-red-100' : 'text-yellow-700 bg-yellow-100') }}">
                            {{ ucfirst($anggota->status) }}
                        </span>
                    </div>

                     @if($anggota->kelas)
                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700">Kelas</label>
                        <div class="text-gray-900">{{ $anggota->kelas->nama_kelas }}</div>
                    </div> -->
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <div class="text-gray-900">{{ $anggota->kelas->jurusan->nama_jurusan }}</div>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                        <div class="text-gray-900">{{ $anggota->tanggal_bergabung->format('d F Y') }}</div>
                    </div>
                    
                   
                    
                    @if($anggota->jabatan)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <div class="text-gray-900">{{ $anggota->jabatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistik Peminjaman -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h4 class="text-lg font-semibold mb-4 text-gray-800">Statistik Peminjaman</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-2xl font-bold text-blue-600">{{ $anggota->peminjaman->count() }}</div>
                    <div class="text-sm text-gray-600">Total Peminjaman</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $anggota->peminjaman->where('status', 'dikembalikan')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Buku Dikembalikan</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-2xl font-bold text-red-600">
                        {{ $anggota->denda->sum('jumlah_denda') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Denda</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 