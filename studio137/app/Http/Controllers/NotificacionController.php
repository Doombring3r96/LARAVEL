<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::with('usuario')->paginate(10);
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('notificaciones.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'mensaje' => 'required|string',
            'leido' => 'required|boolean',
            'origen_tipo' => ['required', Rule::in(Notificacion::ORIGEN_TIPOS)],
            'origen_id' => 'required|integer'
        ]);

        Notificacion::create($request->all());

        return redirect()->route('notificaciones.index')->with('success', 'Notificación creada exitosamente.');
    }

    public function show(Notificacion $notificacion)
    {
        $notificacion->load('usuario');
        return view('notificaciones.show', compact('notificacion'));
    }

    public function edit(Notificacion $notificacion)
    {
        $usuarios = User::all();
        return view('notificaciones.edit', compact('notificacion', 'usuarios'));
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'mensaje' => 'required|string',
            'leido' => 'required|boolean',
            'origen_tipo' => ['required', Rule::in(Notificacion::ORIGEN_TIPOS)],
            'origen_id' => 'required|integer'
        ]);

        $notificacion->update($request->all());

        return redirect()->route('notificaciones.index')->with('success', 'Notificación actualizada exitosamente.');
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();
        return redirect()->route('notificaciones.index')->with('success', 'Notificación eliminada exitosamente.');
    }
}