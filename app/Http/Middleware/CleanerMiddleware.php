<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CleanerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->userType == 'Cleaner') {
            return $next($request);
        }

        return redirect('/home')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}
