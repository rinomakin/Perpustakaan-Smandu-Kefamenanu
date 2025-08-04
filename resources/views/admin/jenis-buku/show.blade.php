@extends('layouts.admin')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Detail Jenis Buku
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Nama Jenis</p>
            <p class="text-gray-900">{{ $jenis->nama_jenis }}</p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Kode Jenis</p>
            <p class="text-gray-900">{{ $jenis->kode_jenis }}</p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Deskripsi</p>
            <p class="text-gray-900">{{ $jenis->deskripsi ?: 'Tidak ada deskripsi' }}</p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Status</p>
            <p>
                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $jenis->status ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                    {{ $jenis->status ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Jumlah Buku</p>
            <p class="text-gray-900">{{ $jenis->buku->count() }} buku</p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Tanggal Dibuat</p>
            <p class="text-gray-900">{{ $jenis->created_at->format('d-m-Y H:i:s') }}</p>
        </div>

        <div class="mb-4">
            <p class="block text-sm text-gray-700 font-medium">Terakhir Diperbarui</p>
            <p class="text-gray-900">{{ $jenis->updated_at->format('d-m-Y H:i:s') }}</p>
        </div>

        @if($jenis->buku->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Daftar Buku</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerbit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jenis->buku->take(5) as $buku)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $buku->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $buku->penulis->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $buku->penerbit->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $buku->tahun_terbit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $buku->status ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                    {{ $buku->status ? 'Tersedia' : 'Dipinjam' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($jenis->buku->count() > 5)
            <p class="text-sm text-gray-500 mt-2">Menampilkan 5 dari {{ $jenis->buku->count() }} buku</p>
            @endif
        </div>
        @endif

        <div class="flex mt-6 space-x-3">
            <a href="{{ route('jenis-buku.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button onclick="editJenisBuku({{ $jenis->id }})" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-lg active:bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow">
                <i class="fas fa-edit mr-1"></i> Edit
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editJenisBuku(id) {
        // Redirect ke halaman index dengan modal edit terbuka
        window.location.href = '{{ route("jenis-buku.index") }}?edit=' + id;
    }
</script>
@endpush
@endsection