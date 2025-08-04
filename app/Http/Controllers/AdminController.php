<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengaturanWebsite;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function pengaturanWebsite()
    {
        $pengaturan = PengaturanWebsite::first();
        return view('admin.pengaturan-website', compact('pengaturan'));
    }

    public function updatePengaturanWebsite(Request $request)
    {
        $request->validate([
            'nama_website' => 'required|string|max:255',
            'alamat_sekolah' => 'required|string',
            'telepon_sekolah' => 'required|string',
            'email_sekolah' => 'required|email',
            'nama_kepala_sekolah' => 'required|string',
            'visi_sekolah' => 'required|string',
            'misi_sekolah' => 'required|string',
            'sejarah_sekolah' => 'required|string',
            'jam_operasional' => 'required|string',
            'kebijakan_perpustakaan' => 'nullable|string',
        ]);

        $pengaturan = PengaturanWebsite::first();
        
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/logo'), $logoName);
            $pengaturan->logo = 'uploads/logo/' . $logoName;
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/favicon'), $faviconName);
            $pengaturan->favicon = 'uploads/favicon/' . $faviconName;
        }

        $pengaturan->update($request->except(['logo', 'favicon']));

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui');
    }
} 