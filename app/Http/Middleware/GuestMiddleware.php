<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->userType == 'Guest') {
            return $next($request);
        }

        return redirect('/home')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}
