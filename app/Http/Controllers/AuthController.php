<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect berdasarkan peran
            $kodePeran = $user->role ? $user->role->kode_peran : null;
            switch ($kodePeran) {
                case 'ADMIN':
                    return redirect()->intended('/'); // Redirect ke root untuk admin
                case 'KEPALA_SEKOLAH':
                    return redirect()->intended('/'); // Redirect ke root untuk kepala sekolah
                case 'PETUGAS':
                    return redirect()->intended('/petugas/dashboard');
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
} 