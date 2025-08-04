@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Pengaturan Website</h3>
        <p class="text-sm text-gray-600">Kelola informasi dan tampilan website perpustakaan</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Website -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Informasi Website</h4>
                    
                    <div>
                        <label for="nama_website" class="block text-sm font-medium text-gray-700">Nama Website</label>
                        <input type="text" name="nama_website" id="nama_website" value="{{ $pengaturan->nama_website }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="deskripsi_website" class="block text-sm font-medium text-gray-700">Deskripsi Website</label>
                        <textarea name="deskripsi_website" id="deskripsi_website" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->deskripsi_website }}</textarea>
                    </div>
                    
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo Website</label>
                        <input type="file" name="logo" id="logo" accept="image/*" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @if($pengaturan->logo)
                            <p class="mt-1 text-sm text-gray-500">Logo saat ini: {{ $pengaturan->logo }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                        <input type="file" name="favicon" id="favicon" accept="image/*" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @if($pengaturan->favicon)
                            <p class="mt-1 text-sm text-gray-500">Favicon saat ini: {{ $pengaturan->favicon }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Informasi Sekolah -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Informasi Sekolah</h4>
                    
                    <div>
                        <label for="alamat_sekolah" class="block text-sm font-medium text-gray-700">Alamat Sekolah</label>
                        <textarea name="alamat_sekolah" id="alamat_sekolah" rows="2" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->alamat_sekolah }}</textarea>
                    </div>
                    
                    <div>
                        <label for="telepon_sekolah" class="block text-sm font-medium text-gray-700">Telepon Sekolah</label>
                        <input type="text" name="telepon_sekolah" id="telepon_sekolah" value="{{ $pengaturan->telepon_sekolah }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email_sekolah" class="block text-sm font-medium text-gray-700">Email Sekolah</label>
                        <input type="email" name="email_sekolah" id="email_sekolah" value="{{ $pengaturan->email_sekolah }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="nama_kepala_sekolah" class="block text-sm font-medium text-gray-700">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepala_sekolah" id="nama_kepala_sekolah" value="{{ $pengaturan->nama_kepala_sekolah }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="jam_operasional" class="block text-sm font-medium text-gray-700">Jam Operasional</label>
                        <input type="text" name="jam_operasional" id="jam_operasional" value="{{ $pengaturan->jam_operasional }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Visi, Misi, dan Sejarah -->
            <div class="mt-8 space-y-6">
                <div>
                    <label for="visi_sekolah" class="block text-sm font-medium text-gray-700">Visi Sekolah</label>
                    <textarea name="visi_sekolah" id="visi_sekolah" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->visi_sekolah }}</textarea>
                </div>
                
                <div>
                    <label for="misi_sekolah" class="block text-sm font-medium text-gray-700">Misi Sekolah</label>
                    <textarea name="misi_sekolah" id="misi_sekolah" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->misi_sekolah }}</textarea>
                </div>
                
                <div>
                    <label for="sejarah_sekolah" class="block text-sm font-medium text-gray-700">Sejarah Sekolah</label>
                    <textarea name="sejarah_sekolah" id="sejarah_sekolah" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->sejarah_sekolah }}</textarea>
                </div>
                
                <div>
                    <label for="kebijakan_perpustakaan" class="block text-sm font-medium text-gray-700">Kebijakan Perpustakaan</label>
                    <textarea name="kebijakan_perpustakaan" id="kebijakan_perpustakaan" rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $pengaturan->kebijakan_perpustakaan }}</textarea>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 