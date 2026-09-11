<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Only allow users with "admin" role OR a specific admin email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ✅ Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $user = Auth::user();

        // ✅ Check role column (preferred)
        if ($user->role === 'admin') {
            return $next($request);
        }

        // ✅ OR fallback check by email
        if ($user->email === 'dhaval@gmail.com') {
            return $next($request);
        }

        // 🚫 If not admin → redirect to home
        return redirect()->route('home')->with('error', 'You are not authorized to access admin panel.');
    }
}
