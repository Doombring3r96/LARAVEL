<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        // Solo CEO puede ver la lista de usuarios
        return $user->isCeo();
    }

    public function view(User $user, User $model): bool
    {
        // CEO puede ver cualquier usuario
        if ($user->isCeo()) {
            return true;
        }
        
        // Los usuarios pueden verse a sí mismos
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        // Solo CEO puede crear usuarios
        return $user->isCeo();
    }

    public function update(User $user, User $model): bool
    {
        // CEO puede actualizar cualquier usuario
        if ($user->isCeo()) {
            return true;
        }
        
        // Los usuarios pueden actualizarse a sí mismos
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        // Solo CEO puede eliminar usuarios (y no puede eliminarse a sí mismo)
        return $user->isCeo() && $user->id !== $model->id;
    }
}