<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('usuario')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|unique:users,email',
            'contrasena' => 'required|min:8',
            'nombre_empresa' => 'nullable|string|max:100',
            'persona_contacto' => 'nullable|string|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
            'deuda' => 'nullable|numeric|min:0'
        ]);

        // Crear usuario primero
        $user = User::create([
            'email' => $request->correo,
            'password' => Hash::make($request->contrasena),
            'tipo' => 'cliente',
            'estado' => 'activo'
        ]);

        // Crear cliente
        Cliente::create([
            'usuario_id' => $user->id,
            'nombre_empresa' => $request->nombre_empresa,
            'persona_contacto' => $request->persona_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'deuda' => $request->deuda ?? 0.00
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('usuario', 'servicios');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('usuario');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'correo' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($cliente->usuario_id)
            ],
            'nombre_empresa' => 'nullable|string|max:100',
            'persona_contacto' => 'nullable|string|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
            'deuda' => 'nullable|numeric|min:0'
        ]);

        // Actualizar usuario
        $user = User::find($cliente->usuario_id);
        $user->email = $request->correo;
        if ($request->has('contrasena') && $request->contrasena) {
            $user->password = Hash::make($request->contrasena);
        }
        $user->save();

        // Actualizar cliente
        $cliente->update([
            'nombre_empresa' => $request->nombre_empresa,
            'persona_contacto' => $request->persona_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'deuda' => $request->deuda ?? $cliente->deuda
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        // Eliminar usuario (se eliminará en cascada por la relación)
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}