<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
