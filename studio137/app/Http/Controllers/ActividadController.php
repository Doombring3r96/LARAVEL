<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = Actividad::with('servicio')->paginate(10);
        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('actividades.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'fecha_inicio_estimada' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio_estimada',
            'fecha_inicio_real' => 'nullable|date',
            'fecha_fin_real' => 'nullable|date|after_or_equal:fecha_inicio_real',
            'estado' => ['required', Rule::in(Actividad::ESTADOS)]
        ]);

        Actividad::create($request->all());

        return redirect()->route('actividades.index')->with('success', 'Actividad creada exitosamente.');
    }

    public function show(Actividad $actividad)
    {
        $actividad->load('servicio', 'tareas');
        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        $servicios = Servicio::all();
        return view('actividades.edit', compact('actividad', 'servicios'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'fecha_inicio_estimada' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio_estimada',
            'fecha_inicio_real' => 'nullable|date',
            'fecha_fin_real' => 'nullable|date|after_or_equal:fecha_inicio_real',
            'estado' => ['required', Rule::in(Actividad::ESTADOS)]
        ]);

        $actividad->update($request->all());

        return redirect()->route('actividades.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->delete();
        return redirect()->route('actividades.index')->with('success', 'Actividad eliminada exitosamente.');
    }
}