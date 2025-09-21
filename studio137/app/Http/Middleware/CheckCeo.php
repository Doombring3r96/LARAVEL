<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCeo
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tipo === 'administrador') {
            // Verificar si es CEO (podrías tener un campo adicional en users para el subtipo)
            $subtipo = Auth::user()->subtipo ?? null;
            if ($subtipo === 'CEO') {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos de CEO para acceder a esta página.');
    }
}