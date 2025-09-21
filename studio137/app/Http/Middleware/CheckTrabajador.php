<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTrabajador
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tipo === 'trabajador') {
            return $next($request);
        }

        abort(403, 'No tienes permisos de trabajador para acceder a esta página.');
    }
}