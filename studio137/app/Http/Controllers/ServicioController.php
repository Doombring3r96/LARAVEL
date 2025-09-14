<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('cliente')->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('servicios.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => ['required', Rule::in(Servicio::TIPOS)],
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => ['required', Rule::in(Servicio::ESTADOS)]
        ]);

        Servicio::create($request->all());

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function show(Servicio $servicio)
    {
        $servicio->load('cliente', 'actividades', 'calendariosPublicacion', 'campanasPublicitarias');
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $clientes = Cliente::all();
        return view('servicios.edit', compact('servicio', 'clientes'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => ['required', Rule::in(Servicio::TIPOS)],
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => ['required', Rule::in(Servicio::ESTADOS)]
        ]);

        $servicio->update($request->all());

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}