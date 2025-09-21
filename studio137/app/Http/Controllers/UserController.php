<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        // Resto del código...
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        // Resto del código...
    }

    public function create()
    {
        $this->authorize('create', User::class);
        // Resto del código...
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        // Resto del código...
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        // Resto del código...
    }
}
