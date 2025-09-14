<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionDesempeno;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluacionDesempenoController extends Controller
{
    public function index()
    {
        $evaluaciones = EvaluacionDesempeno::with('trabajador')->paginate(10);
        return view('evaluaciones-desempeno.index', compact('evaluaciones'));
    }

    public function create()
    {
        $trabajadores = Trabajador::all();
        return view('evaluaciones-desempeno.create', compact('trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2000|max:2100',
            'total_tareas' => 'required|integer|min:0',
            'tareas_a_tiempo' => 'required|integer|min:0|lte:total_tareas',
            'tareas_retrasadas' => 'required|integer|min:0|lte:total_tareas',
            'porcentaje_cumplimiento' => 'nullable|numeric|min:0|max:100',
            'calificacion' => ['required', Rule::in(EvaluacionDesempeno::CALIFICACIONES)],
            'observaciones' => 'nullable|string'
        ]);

        EvaluacionDesempeno::create($request->all());

        return redirect()->route('evaluaciones-desempeno.index')->with('success', 'Evaluación de desempeño creada exitosamente.');
    }

    public function show(EvaluacionDesempeno $evaluacionDesempeno)
    {
        $evaluacionDesempeno->load('trabajador');
        return view('evaluaciones-desempeno.show', compact('evaluacionDesempeno'));
    }

    public function edit(EvaluacionDesempeno $evaluacionDesempeno)
    {
        $trabajadores = Trabajador::all();
        return view('evaluaciones-desempeno.edit', compact('evaluacionDesempeno', 'trabajadores'));
    }

    public function update(Request $request, EvaluacionDesempeno $evaluacionDesempeno)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2000|max:2100',
            'total_tareas' => 'required|integer|min:0',
            'tareas_a_tiempo' => 'required|integer|min:0|lte:total_tareas',
            'tareas_retrasadas' => 'required|integer|min:0|lte:total_tareas',
            'porcentaje_cumplimiento' => 'nullable|numeric|min:0|max:100',
            'calificacion' => ['required', Rule::in(EvaluacionDesempeno::CALIFICACIONES)],
            'observaciones' => 'nullable|string'
        ]);

        $evaluacionDesempeno->update($request->all());

        return redirect()->route('evaluaciones-desempeno.index')->with('success', 'Evaluación de desempeño actualizada exitosamente.');
    }

    public function destroy(EvaluacionDesempeno $evaluacionDesempeno)
    {
        $evaluacionDesempeno->delete();
        return redirect()->route('evaluaciones-desempeno.index')->with('success', 'Evaluación de desempeño eliminada exitosamente.');
    }
}