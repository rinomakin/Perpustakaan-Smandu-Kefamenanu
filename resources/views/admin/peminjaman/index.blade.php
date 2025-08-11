@extends('layouts.admin')

@section('title', 'Peminjaman Buku')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exchange-alt text-white text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Daftar Peminjaman</h3>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('peminjaman.create') }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah Data
                        </a>
                        <a href="{{ route('riwayat-peminjaman.index') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                            <i class="fas fa-history mr-1"></i>Riwayat Peminjaman
                        </a>
                        <a href="{{ route('riwayat-pengembalian.index') }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                            <i class="fas fa-undo-alt mr-1"></i>Riwayat Pengembalian
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nomor Peminjaman</th>
                                <th scope="col" class="px-6 py-3">Anggota</th>
                                <th scope="col" class="px-6 py-3">Jumlah Buku</th>
                                <th scope="col" class="px-6 py-3">Tanggal Pinjam</th>
                                <th scope="col" class="px-6 py-3">Batas Kembali</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $loan)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $loan->nomor_peminjaman }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $loan->anggota->nama_lengkap }}</div>
                                    <div class="text-gray-500 text-xs">{{ $loan->anggota->nomor_anggota }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $loan->jumlah_buku }} Buku
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        {{ $loan->tanggal_peminjaman ? $loan->tanggal_peminjaman->format('d M Y') : 'N/A' }}
                                        @if($loan->jam_peminjaman)
                                            <div class="text-xs text-gray-500">Jam {{ $loan->jam_peminjaman->format('H:i') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $loan->tanggal_harus_kembali ? $loan->tanggal_harus_kembali->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($loan->status == 'dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Dipinjam</span>
                                    @elseif($loan->status == 'dikembalikan')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Dikembalikan</span>
                                    @elseif($loan->status == 'terlambat')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Terlambat</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('peminjaman.show', $loan->id) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        <a href="{{ route('peminjaman.edit', $loan->id) }}" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $loan->id }})" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data peminjaman
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($peminjaman->hasPages())
                <div class="mt-6">
                    {{ $peminjaman->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 notifications are handled by layout -->

<script>
function confirmDelete(peminjamanId) {
    showConfirmDialog(
        'Apakah Anda yakin ingin menghapus peminjaman ini? Tindakan ini tidak dapat dibatalkan.',
        'Konfirmasi Hapus',
        function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/peminjaman/${peminjamanId}`;
            
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
    );
}
</script>
@endsection 