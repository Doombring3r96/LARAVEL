<?php

namespace App\Http\Controllers;

use App\Models\PiezaGrafica;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PiezaGraficaController extends Controller
{
    public function index()
    {
        $piezas = PiezaGrafica::with('tarea')->paginate(10);
        return view('piezas-graficas.index', compact('piezas'));
    }

    public function create()
    {
        $tareas = Tarea::all();
        return view('piezas-graficas.create', compact('tareas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'tipo' => ['required', Rule::in(PiezaGrafica::TIPOS)],
            'titulo' => 'nullable|string|max:100',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'estado' => ['required', Rule::in(PiezaGrafica::ESTADOS)],
            'url_archivo' => 'nullable|url',
            'fecha_entrega_estimada' => 'nullable|date',
            'fecha_entrega_real' => 'nullable|date|after_or_equal:fecha_entrega_estimada'
        ]);

        PiezaGrafica::create($request->all());

        return redirect()->route('piezas-graficas.index')->with('success', 'Pieza gráfica creada exitosamente.');
    }

    public function show(PiezaGrafica $piezaGrafica)
    {
        $piezaGrafica->load('tarea');
        return view('piezas-graficas.show', compact('piezaGrafica'));
    }

    public function edit(PiezaGrafica $piezaGrafica)
    {
        $tareas = Tarea::all();
        return view('piezas-graficas.edit', compact('piezaGrafica', 'tareas'));
    }

    public function update(Request $request, PiezaGrafica $piezaGrafica)
    {
        $request->validate([
            'tarea_id' => 'required|exists:tareas,id',
            'tipo' => ['required', Rule::in(PiezaGrafica::TIPOS)],
            'titulo' => 'nullable|string|max:100',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'estado' => ['required', Rule::in(PiezaGrafica::ESTADOS)],
            'url_archivo' => 'nullable|url',
            'fecha_entrega_estimada' => 'nullable|date',
            'fecha_entrega_real' => 'nullable|date|after_or_equal:fecha_entrega_estimada'
        ]);

        $piezaGrafica->update($request->all());

        return redirect()->route('piezas-graficas.index')->with('success', 'Pieza gráfica actualizada exitosamente.');
    }

    public function destroy(PiezaGrafica $piezaGrafica)
    {
        $piezaGrafica->delete();
        return redirect()->route('piezas-graficas.index')->with('success', 'Pieza gráfica eliminada exitosamente.');
    }
}