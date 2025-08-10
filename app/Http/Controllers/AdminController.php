<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PengaturanWebsite;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Mengubah middleware untuk mengizinkan akses dari ADMIN, KEPALA_SEKOLAH, dan PETUGAS
        $this->middleware('role:ADMIN,KEPALA_SEKOLAH,PETUGAS');
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

    public function profil()
    {
        $user = auth()->user();
        
        // Validasi berdasarkan role user yang login
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Pastikan user hanya bisa melihat profilnya sendiri
        // Tidak perlu validasi tambahan karena menggunakan auth()->user()
        
        // Tentukan view berdasarkan role user
        if ($user->isPetugas()) {
            return view('petugas.profil', compact('user'));
        } elseif ($user->isKepalaSekolah()) {
            return view('kepsek.profil', compact('user'));
        } else {
            return view('admin.profil', compact('user'));
        }
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $data = $request->except('foto');

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
                unlink(public_path('storage/' . $user->foto));
            }

            // Upload foto baru
            $foto = $request->file('foto');
            $fotoName = 'profil_' . $user->id . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('storage/foto-profil'), $fotoName);
            $data['foto'] = 'foto-profil/' . $fotoName;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function hapusFoto(Request $request)
    {
        $user = auth()->user();
        
        if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
            unlink(public_path('storage/' . $user->foto));
        }
        
        $user->update(['foto' => null]);
        
        return redirect()->back()->with('success', 'Foto profil berhasil dihapus');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama harus diisi',
            'password_baru.required' => 'Password baru harus diisi',
            'password_baru.min' => 'Password baru minimal 8 karakter',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai');
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
} 