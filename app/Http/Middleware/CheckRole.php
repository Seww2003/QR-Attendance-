<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        // Check user role
        $user = Auth::user();
        
        if ($user->role !== $role) {
            return redirect('/dashboard')->with('error', 'Access denied. You do not have permission.');
        }

        return $next($request);
    }
}