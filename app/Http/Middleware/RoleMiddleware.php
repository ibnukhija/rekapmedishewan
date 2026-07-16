<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan rolenya sesuai dengan parameter di route
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Jika gagal/bukan admin, kembalikan ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk membuka halaman ini.');
    }
}