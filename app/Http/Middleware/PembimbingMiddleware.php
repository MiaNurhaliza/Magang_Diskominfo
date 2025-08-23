<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PembimbingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user has pembimbing role
        if (Auth::user()->role !== 'pembimbing') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai pembimbing.');
        }

        // Check if user has associated pembimbing record
        if (!Auth::user()->pembimbing) {
            abort(403, 'Data pembimbing tidak ditemukan. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
