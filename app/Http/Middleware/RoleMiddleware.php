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
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                case 'seller':
                    return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                case 'buyer':
                    return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                default:
                    return redirect()->route('login')->with('error', 'Role tidak valid.');
            }
        }

        return $next($request);
    }
}
