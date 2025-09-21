<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDirectorCuentas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tipo === 'administrador') {
            $subtipo = Auth::user()->subtipo ?? null;
            if ($subtipo === 'director_cuentas') {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos de Director de Cuentas para acceder a esta página.');
    }
}