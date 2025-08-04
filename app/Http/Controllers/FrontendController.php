<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\PengaturanWebsite;

class FrontendController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanWebsite::first();
        $bukuTerbaru = Buku::with(['penulis', 'penerbit', 'kategori'])
            ->where('status', 'tersedia')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        return view('frontend.index', compact('pengaturan', 'bukuTerbaru'));
    }

    public function cariBuku(Request $request)
    {
        $pengaturan = PengaturanWebsite::first();
        $query = $request->get('q');
        $kategori = $request->get('kategori');
        
        $buku = Buku::with(['penulis', 'penerbit', 'kategori', 'jenis'])
            ->where('status', 'tersedia');
            
        if ($query) {
            $buku->where(function($q) use ($query) {
                $q->where('judul_buku', 'like', "%{$query}%")
                  ->orWhere('isbn', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  ->orWhereHas('penulis', function($q) use ($query) {
                      $q->where('nama_penulis', 'like', "%{$query}%");
                  })
                  ->orWhereHas('penerbit', function($q) use ($query) {
                      $q->where('nama_penerbit', 'like', "%{$query}%");
                  });
            });
        }
        
        if ($kategori) {
            $buku->whereHas('kategori', function($q) use ($kategori) {
                $q->where('id', $kategori);
            });
        }
        
        $buku = $buku->paginate(12);
        
        return view('frontend.cari-buku', compact('pengaturan', 'buku', 'query', 'kategori'));
    }

    public function tentang()
    {
        $pengaturan = PengaturanWebsite::first();
        return view('frontend.tentang', compact('pengaturan'));
    }

    public function koleksiBuku()
    {
        $pengaturan = PengaturanWebsite::first();
        $bukuTerbaru = Buku::with(['penulis', 'penerbit', 'kategori'])
            ->where('status', 'tersedia')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
            
        return view('frontend.koleksi', compact('pengaturan', 'bukuTerbaru'));
    }
} 