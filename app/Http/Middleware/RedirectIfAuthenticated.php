<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role user
                $kodePeran = $user->role ? $user->role->kode_peran : null;
                switch ($kodePeran) {
                    case 'ADMIN':
                        return redirect('/'); // Redirect ke root untuk admin
                    case 'KEPALA_SEKOLAH':
                        return redirect('/'); // Redirect ke root untuk kepala sekolah
                    case 'PETUGAS':
                        return redirect('/petugas/dashboard');
                    default:
                        return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
