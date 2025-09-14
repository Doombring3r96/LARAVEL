<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Actividad;
use App\Models\Trabajador;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::with('actividad', 'trabajador', 'etiquetas')->paginate(10);
        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        $actividades = Actividad::all();
        $trabajadores = Trabajador::all();
        $etiquetas = Etiqueta::all();
        return view('tareas.create', compact('actividades', 'trabajadores', 'etiquetas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'trabajador_id' => 'nullable|exists:trabajadores,id',
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'prioridad' => ['required', Rule::in(Tarea::PRIORIDADES)],
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio',
            'fecha_fin_real' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => ['required', Rule::in(Tarea::ESTADOS)],
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id'
        ]);

        $tarea = Tarea::create($request->except('etiquetas'));
        
        if ($request->has('etiquetas')) {
            $tarea->etiquetas()->sync($request->etiquetas);
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function show(Tarea $tarea)
    {
        $tarea->load('actividad', 'trabajador', 'etiquetas', 'piezasGraficas');
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $actividades = Actividad::all();
        $trabajadores = Trabajador::all();
        $etiquetas = Etiqueta::all();
        $tarea->load('etiquetas');
        return view('tareas.edit', compact('tarea', 'actividades', 'trabajadores', 'etiquetas'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'trabajador_id' => 'nullable|exists:trabajadores,id',
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'prioridad' => ['required', Rule::in(Tarea::PRIORIDADES)],
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio',
            'fecha_fin_real' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => ['required', Rule::in(Tarea::ESTADOS)],
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id'
        ]);

        $tarea->update($request->except('etiquetas'));
        
        if ($request->has('etiquetas')) {
            $tarea->etiquetas()->sync($request->etiquetas);
        } else {
            $tarea->etiquetas()->detach();
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente.');
    }
}