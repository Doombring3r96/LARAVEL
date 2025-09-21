<?php

namespace App\Policies;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TareaPolicy
{
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver tareas (con restricciones)
        return true;
    }

    public function view(User $user, Tarea $tarea): bool
    {
        // CEO y directores pueden ver todas las tareas
        if ($user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo()) {
            return true;
        }
        
        // Trabajadores solo pueden ver sus tareas asignadas
        if (($user->isDisenadorGrafico() || $user->isMarketing()) && 
            $user->trabajador && 
            $tarea->trabajador_id === $user->trabajador->id) {
            return true;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        // Solo CEO y directores pueden crear tareas
        return $user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo();
    }

    public function update(User $user, Tarea $tarea): bool
    {
        // CEO y directores pueden actualizar cualquier tarea
        if ($user->isCeo() || $user->isDirectorCuentas() || $user->isDirectorCreativo()) {
            return true;
        }
        
        // Trabajadores solo pueden actualizar sus tareas asignadas
        if (($user->isDisenadorGrafico() || $user->isMarketing()) && 
            $user->trabajador && 
            $tarea->trabajador_id === $user->trabajador->id) {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Tarea $tarea): bool
    {
        // Solo CEO puede eliminar tareas
        return $user->isCeo();
    }
}