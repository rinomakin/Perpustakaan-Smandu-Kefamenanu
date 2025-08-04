@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Laporan</h2>
        <p class="text-gray-600 mt-2">Pilih jenis laporan yang ingin Anda lihat</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Laporan Anggota -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 p-3 rounded-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Anggota</h3>
                    <p class="text-gray-600 text-sm">Data anggota perpustakaan</p>
                </div>
            </div>
            <a href="{{ route('admin.laporan.anggota') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-eye mr-2"></i>
                Lihat Laporan
            </a>
        </div>

        <!-- Laporan Buku -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-green-500 p-3 rounded-lg">
                    <i class="fas fa-book text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Buku</h3>
                    <p class="text-gray-600 text-sm">Data koleksi buku</p>
                </div>
            </div>
            <a href="{{ route('admin.laporan.buku') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <i class="fas fa-eye mr-2"></i>
                Lihat Laporan
            </a>
        </div>

        <!-- Laporan Kas -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-yellow-500 p-3 rounded-lg">
                    <i class="fas fa-money-bill text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Laporan Kas</h3>
                    <p class="text-gray-600 text-sm">Laporan keuangan denda</p>
                </div>
            </div>
            <a href="{{ route('admin.laporan.kas') }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                <i class="fas fa-eye mr-2"></i>
                Lihat Laporan
            </a>
        </div>
    </div>
</div>
@endsection 