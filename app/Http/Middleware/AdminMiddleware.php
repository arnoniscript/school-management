<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado');
        }

        return $next($request);
    }
}

