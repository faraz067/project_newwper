<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek apakah user login DAN role-nya adalah staff atau admin
    if (auth()->check() && (auth()->user()->role == 'staff' || auth()->user()->role == 'admin')) {
        return $next($request);
    }

    // Kalau bukan staff, tendang balik ke halaman awal
    return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}
}
