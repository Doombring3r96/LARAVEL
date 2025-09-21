<?php

namespace App\Policies;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicioPolicy
{
    public function viewAny(User $user): bool
    {
        // CEO, directores y clientes (solo sus servicios) pueden ver servicios
        return $user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo() || $user->isCliente();
    }

    public function view(User $user, Servicio $servicio): bool
    {
        // CEO y directores pueden ver todos los servicios
        if ($user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo()) {
            return true;
        }
        
        // Clientes solo pueden ver sus propios servicios
        if ($user->isCliente() && $user->cliente && $servicio->cliente_id === $user->cliente->id) {
            return true;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        // Solo CEO y Director de Cuentas pueden crear servicios
        return $user->isCeo() || $user->isDirectorCuentas();
    }

    public function update(User $user, Servicio $servicio): bool
    {
        // Solo CEO y Director de Cuentas pueden actualizar servicios
        return $user->isCeo() || $user->isDirectorCuentas();
    }

    public function delete(User $user, Servicio $servicio): bool
    {
        // Solo CEO puede eliminar servicios
        return $user->isCeo();
    }
}