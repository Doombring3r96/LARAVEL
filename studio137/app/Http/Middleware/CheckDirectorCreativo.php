<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDirectorCreativo
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tipo === 'administrador') {
            $subtipo = Auth::user()->subtipo ?? null;
            if ($subtipo === 'director_creativo') {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos de Director Creativo para acceder a esta página.');
    }
}