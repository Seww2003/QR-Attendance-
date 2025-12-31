<?php
// app/Http/Middleware/StudentMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'You do not have permission to access this page.');
    }
}