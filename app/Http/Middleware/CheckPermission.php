<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Admin always has access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Check if user has the required permission
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // If AJAX request, return JSON error
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk melakukan aksi ini.'
            ], 403);
        }

        // Redirect with error message
        return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengakses halaman ini.');
    }
}
