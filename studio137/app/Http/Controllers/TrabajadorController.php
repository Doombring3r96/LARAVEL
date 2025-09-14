<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::with('usuario')->paginate(10);
        return view('trabajadores.index', compact('trabajadores'));
    }

    public function create()
    {
        return view('trabajadores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|unique:users,email',
            'contrasena' => 'required|min:8',
            'nombre_completo' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'puesto' => 'nullable|string|max:50',
            'sueldo' => 'nullable|numeric|min:0'
        ]);

        // Crear usuario primero
        $user = User::create([
            'email' => $request->correo,
            'password' => Hash::make($request->contrasena),
            'tipo' => 'trabajador',
            'estado' => 'activo'
        ]);

        // Crear trabajador
        Trabajador::create([
            'usuario_id' => $user->id,
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'puesto' => $request->puesto,
            'sueldo' => $request->sueldo
        ]);

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador creado exitosamente.');
    }

    public function show(Trabajador $trabajador)
    {
        $trabajador->load('usuario', 'tareas', 'evaluacionesDesempeno');
        return view('trabajadores.show', compact('trabajador'));
    }

    public function edit(Trabajador $trabajador)
    {
        $trabajador->load('usuario');
        return view('trabajadores.edit', compact('trabajador'));
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $request->validate([
            'correo' => 'required|email|unique:users,email,' . $trabajador->usuario_id,
            'nombre_completo' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'puesto' => 'nullable|string|max:50',
            'sueldo' => 'nullable|numeric|min:0'
        ]);

        // Actualizar usuario
        $user = User::find($trabajador->usuario_id);
        $user->email = $request->correo;
        if ($request->has('contrasena') && $request->contrasena) {
            $user->password = Hash::make($request->contrasena);
        }
        $user->save();

        // Actualizar trabajador
        $trabajador->update([
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'puesto' => $request->puesto,
            'sueldo' => $request->sueldo
        ]);

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador actualizado exitosamente.');
    }

    public function destroy(Trabajador $trabajador)
    {
        $trabajador->delete();
        return redirect()->route('trabajadores.index')->with('success', 'Trabajador eliminado exitosamente.');
    }
}