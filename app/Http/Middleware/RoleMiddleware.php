<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($user->role !== $role) {
            // Redirect based on user's actual role instead of throwing 403
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'Akses tidak diizinkan');
                case 'pembimbing':
                    return redirect()->route('pembimbing.dashboard')->with('error', 'Akses tidak diizinkan');
                case 'user':
                default:
                    return redirect()->route('dashboard')->with('error', 'Akses tidak diizinkan');
            }
        }

        return $next($request);
    }
}
