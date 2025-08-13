@extends('layouts.admin')

@section('title', 'Detail Denda')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-red-600 to-pink-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">👁️ Detail Denda</h1>
                <p class="text-red-100 mt-1">Detail informasi denda keterlambatan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.denda.edit', $denda->id) }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.denda.index') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Detail Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Denda Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-money-bill-wave mr-2 text-red-600"></i>
                Informasi Denda
            </h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">ID Denda:</span>
                    <span class="text-sm text-gray-900 font-semibold">#{{ $denda->id }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Jumlah Hari Terlambat:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $denda->jumlah_hari_terlambat }} hari
                    </span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Jumlah Denda:</span>
                    <span class="text-sm text-gray-900 font-semibold">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Status Pembayaran:</span>
                    @if($denda->status_pembayaran === 'sudah_dibayar')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Sudah Dibayar
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Belum Dibayar
                        </span>
                    @endif
                </div>

                @if($denda->tanggal_pembayaran)
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Tanggal Pembayaran:</span>
                    <span class="text-sm text-gray-900">{{ $denda->tanggal_pembayaran->format('d/m/Y') }}</span>
                </div>
                @endif

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Tanggal Dibuat:</span>
                    <span class="text-sm text-gray-900">{{ $denda->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Terakhir Diupdate:</span>
                    <span class="text-sm text-gray-900">{{ $denda->updated_at->format('d/m/Y H:i') }}</span>
                </div>

                @if($denda->catatan)
                <div class="py-3">
                    <span class="text-sm font-medium text-gray-600 block mb-2">Catatan:</span>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-700">{{ $denda->catatan }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Anggota Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-user mr-2 text-blue-600"></i>
                Informasi Anggota
            </h2>

            @if($denda->anggota)
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-16 w-16">
                    <img class="h-16 w-16 rounded-full object-cover" 
                         src="{{ $denda->anggota->foto ? asset('storage/' . $denda->anggota->foto) : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" fill="#e5e7eb"/><text x="32" y="38" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="24">👤</text></svg>') }}" 
                         alt="Foto Anggota">
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $denda->anggota->nama_lengkap }}</h3>
                    <p class="text-sm text-gray-600">{{ $denda->anggota->nomor_anggota }}</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Nama Lengkap:</span>
                    <span class="text-sm text-gray-900">{{ $denda->anggota->nama_lengkap }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Nomor Anggota:</span>
                    <span class="text-sm text-gray-900">{{ $denda->anggota->nomor_anggota }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Kelas:</span>
                    <span class="text-sm text-gray-900">{{ $denda->anggota->kelas ? $denda->anggota->kelas->nama_kelas : '-' }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Jurusan:</span>
                    <span class="text-sm text-gray-900">{{ $denda->anggota->jurusan ? $denda->anggota->jurusan->nama_jurusan : '-' }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $denda->anggota->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($denda->anggota->status) }}
                    </span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-user-slash text-4xl mb-3"></i>
                <p>Data anggota tidak ditemukan</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Peminjaman Information -->
    @if($denda->peminjaman)
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-book mr-2 text-green-600"></i>
            Informasi Peminjaman
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">ID Peminjaman:</span>
                    <span class="text-sm text-gray-900 font-semibold">#{{ $denda->peminjaman->id }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Tanggal Peminjaman:</span>
                    <span class="text-sm text-gray-900">{{ $denda->peminjaman->tanggal_peminjaman->format('d/m/Y') }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Tanggal Harus Kembali:</span>
                    <span class="text-sm text-gray-900">{{ $denda->peminjaman->tanggal_harus_kembali->format('d/m/Y') }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst($denda->peminjaman->status) }}
                    </span>
                </div>
            </div>

            <!-- Detail Buku -->
            <div class="md:col-span-2">
                <h3 class="text-md font-medium text-gray-800 mb-3">Buku yang Dipinjam:</h3>
                <div class="space-y-2">
                    @forelse($denda->peminjaman->detailPeminjaman as $detail)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-gray-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $detail->buku->judul }}</p>
                                <p class="text-xs text-gray-500">{{ $detail->buku->pengarang }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-900">Qty: {{ $detail->jumlah }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-book-open text-2xl mb-2"></i>
                        <p class="text-sm">Tidak ada detail buku</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4 mt-8">
        <a href="{{ route('admin.denda.edit', $denda->id) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i>
            Edit Denda
        </a>
        <form action="{{ route('admin.denda.destroy', $denda->id) }}" 
              method="POST" 
              class="inline" 
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data denda ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-trash mr-2"></i>
                Hapus Denda
            </button>
        </form>
        <a href="{{ route('admin.denda.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
