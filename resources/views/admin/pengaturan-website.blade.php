@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cog text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pengaturan Website</h1>
                    <p class="text-gray-600 mt-1">Kelola informasi dan tampilan website perpustakaan</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informasi Website Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-globe text-white text-xl"></i>
                            <h3 class="text-lg font-semibold text-white">Informasi Website</h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label for="nama_website" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-tag text-blue-500 mr-2"></i>
                                Nama Website
                            </label>
                            <input type="text" name="nama_website" id="nama_website" value="{{ $pengaturan->nama_website }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="deskripsi_website" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-align-left text-blue-500 mr-2"></i>
                                Deskripsi Website
                            </label>
                            <textarea name="deskripsi_website" id="deskripsi_website" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">{{ $pengaturan->deskripsi_website }}</textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="logo" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-image text-blue-500 mr-2"></i>
                                Logo Website
                            </label>
                            <div class="relative">
                                <input type="file" name="logo" id="logo" accept="image/*" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @if($pengaturan->logo)
                                <div class="mt-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm text-green-700 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Logo saat ini: {{ $pengaturan->logo }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <label for="favicon" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-star text-blue-500 mr-2"></i>
                                Favicon
                            </label>
                            <div class="relative">
                                <input type="file" name="favicon" id="favicon" accept="image/*" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @if($pengaturan->favicon)
                                <div class="mt-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm text-green-700 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Favicon saat ini: {{ $pengaturan->favicon }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Sekolah Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-school text-white text-xl"></i>
                            <h3 class="text-lg font-semibold text-white">Informasi Sekolah</h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label for="alamat_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                Alamat Sekolah
                            </label>
                            <textarea name="alamat_sekolah" id="alamat_sekolah" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">{{ $pengaturan->alamat_sekolah }}</textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="telepon_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                Telepon Sekolah
                            </label>
                            <input type="text" name="telepon_sekolah" id="telepon_sekolah" value="{{ $pengaturan->telepon_sekolah }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="email_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-envelope text-green-500 mr-2"></i>
                                Email Sekolah
                            </label>
                            <input type="email" name="email_sekolah" id="email_sekolah" value="{{ $pengaturan->email_sekolah }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="nama_kepala_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-user-tie text-green-500 mr-2"></i>
                                Nama Kepala Sekolah
                            </label>
                            <input type="text" name="nama_kepala_sekolah" id="nama_kepala_sekolah" value="{{ $pengaturan->nama_kepala_sekolah }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="jam_operasional" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                Jam Operasional
                            </label>
                            <input type="text" name="jam_operasional" id="jam_operasional" value="{{ $pengaturan->jam_operasional }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visi, Misi, dan Sejarah Section -->
            <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-book-open text-white text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Visi, Misi & Informasi Sekolah</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="visi_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-eye text-purple-500 mr-2"></i>
                                Visi Sekolah
                            </label>
                            <textarea name="visi_sekolah" id="visi_sekolah" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">{{ $pengaturan->visi_sekolah }}</textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="misi_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-bullseye text-purple-500 mr-2"></i>
                                Misi Sekolah
                            </label>
                            <textarea name="misi_sekolah" id="misi_sekolah" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">{{ $pengaturan->misi_sekolah }}</textarea>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="sejarah_sekolah" class="block text-sm font-medium text-gray-700 flex items-center">
                            <i class="fas fa-history text-purple-500 mr-2"></i>
                            Sejarah Sekolah
                        </label>
                        <textarea name="sejarah_sekolah" id="sejarah_sekolah" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">{{ $pengaturan->sejarah_sekolah }}</textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="kebijakan_perpustakaan" class="block text-sm font-medium text-gray-700 flex items-center">
                            <i class="fas fa-gavel text-purple-500 mr-2"></i>
                            Kebijakan Perpustakaan
                        </label>
                        <textarea name="kebijakan_perpustakaan" id="kebijakan_perpustakaan" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">{{ $pengaturan->kebijakan_perpustakaan }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" onclick="window.history.back()" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </button>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 flex items-center shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
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