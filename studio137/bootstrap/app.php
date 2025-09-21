<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.ceo' => \App\Http\Middleware\CheckCeo::class,
            'check.director.cuentas' => \App\Http\Middleware\CheckDirectorCuentas::class,
            'check.director.creativo' => \App\Http\Middleware\CheckDirectorCreativo::class,
            'check.disenador.grafico' => \App\Http\Middleware\CheckDisenadorGrafico::class,
            'check.marketing' => \App\Http\Middleware\CheckMarketing::class,
            'check.cliente' => \App\Http\Middleware\CheckCliente::class,
            'check.admin' => \App\Http\Middleware\CheckAdmin::class,
            'check.trabajador' => \App\Http\Middleware\CheckTrabajador::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
