<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDisenadorGrafico
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tipo === 'trabajador') {
            $subtipo = Auth::user()->subtipo ?? null;
            if ($subtipo === 'disenador_grafico') {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos de Diseñador Gráfico para acceder a esta página.');
    }
}