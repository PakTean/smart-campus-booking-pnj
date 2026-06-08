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
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user belum login, tendang ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika role user tidak ada dalam daftar role yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses ditolak! Kamu tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}