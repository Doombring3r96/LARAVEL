<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Servicio;
use App\Models\Tarea;
use App\Models\User;
use App\Policies\ServicioPolicy;
use App\Policies\TareaPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Servicio::class => ServicioPolicy::class,
        Tarea::class => TareaPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Definir gates adicionales si es necesario
        Gate::define('view-dashboard', function (User $user) {
            return $user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo();
        });
        
        Gate::define('manage-users', function (User $user) {
            return $user->isCeo();
        });
        
        Gate::define('manage-services', function (User $user) {
            return $user->isCeo() || $user->isDirectorCuentas();
        });
        
        Gate::define('view-client-dashboard', function (User $user) {
            return $user->isCliente();
        });
    }
}