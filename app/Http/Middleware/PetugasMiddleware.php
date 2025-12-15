<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user belum login
        if (!$request->user()) {
            return redirect('/login');
        }

        // Jika role bukan admin
        if ($request->user()->role !== 'Petugas') {
            return redirect('/dashboard')->with('error', 'Anda tidak punya akses.');
        }

        return $next($request);
    }
}
