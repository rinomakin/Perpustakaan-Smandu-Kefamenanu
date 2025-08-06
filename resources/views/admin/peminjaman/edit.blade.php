@extends('layouts.admin')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Peminjaman</h1>
            <a href="{{ route('peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Form Edit Peminjaman</h3>
            </div>
            
            <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Anggota -->
                    <div>
                        <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <select name="anggota_id" id="anggota_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $member)
                            <option value="{{ $member->id }}" {{ old('anggota_id', $peminjaman->anggota_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->nama_lengkap }} - {{ $member->nomor_anggota }}
                            </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="dipinjam" {{ old('status', $peminjaman->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ old('status', $peminjaman->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Peminjaman -->
                    <div>
                        <label for="tanggal_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" 
                               value="{{ old('tanggal_peminjaman', $peminjaman->tanggal_peminjaman->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Peminjaman -->
                    <div>
                        <label for="jam_peminjaman" class="block text-sm font-medium text-gray-700 mb-2">Jam Peminjaman</label>
                        <input type="time" name="jam_peminjaman" id="jam_peminjaman" 
                               value="{{ old('jam_peminjaman', $peminjaman->jam_peminjaman ? $peminjaman->jam_peminjaman->format('H:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('jam_peminjaman')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Harus Kembali -->
                    <div>
                        <label for="tanggal_harus_kembali" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Harus Kembali</label>
                        <input type="date" name="tanggal_harus_kembali" id="tanggal_harus_kembali" 
                               value="{{ old('tanggal_harus_kembali', $peminjaman->tanggal_harus_kembali->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal_harus_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Kembali -->
                    <div>
                        <label for="jam_kembali" class="block text-sm font-medium text-gray-700 mb-2">Jam Kembali</label>
                        <input type="time" name="jam_kembali" id="jam_kembali" 
                               value="{{ old('jam_kembali', $peminjaman->jam_kembali ? $peminjaman->jam_kembali->format('H:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('jam_kembali')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Catatan tambahan...">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                        @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Books -->
                <div class="mt-8">
                    <h4 class="font-semibold text-gray-700 mb-4">Buku yang Saat Ini Dipinjam</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($peminjaman->detailPeminjaman as $detail)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    @if($detail->buku->gambar_sampul)
                                        <img src="{{ asset('uploads/' . $detail->buku->gambar_sampul) }}" 
                                             alt="{{ $detail->buku->judul_buku }}" 
                                             class="w-full h-full object-cover rounded">
                                    @else
                                        <i class="fas fa-book text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold text-sm">{{ $detail->buku->judul_buku }}</h5>
                                    <p class="text-xs text-gray-500">{{ $detail->buku->penulis->nama_penulis ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">ISBN: {{ $detail->buku->isbn ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            <i class="fas fa-book-open text-4xl mb-4"></i>
                            <p>Tidak ada buku dipinjam</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('peminjaman.show', $peminjaman->id) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-save mr-2"></i>Update Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center">
    <i class="fas fa-exclamation-circle mr-2"></i>
    {{ session('error') }}
</div>
@endif

<script>
// Auto hide messages after 5 seconds
setTimeout(function() {
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    if (successMessage) {
        successMessage.style.opacity = '0';
        setTimeout(() => successMessage.remove(), 500);
    }
    
    if (errorMessage) {
        errorMessage.style.opacity = '0';
        setTimeout(() => errorMessage.remove(), 500);
    }
}, 5000);
</script>
@endsection 