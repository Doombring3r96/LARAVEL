<?php

namespace App\Http\Controllers;

use App\Models\CampanaPublicitaria;
use App\Models\Servicio;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampanaPublicitariaController extends Controller
{
    public function index()
    {
        $campanas = CampanaPublicitaria::with('servicio', 'responsableMarketing')->paginate(10);
        return view('campanas-publicitarias.index', compact('campanas'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $trabajadores = Trabajador::all();
        return view('campanas-publicitarias.create', compact('servicios', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'responsable_marketing_id' => 'nullable|exists:trabajadores,id',
            'nombre_campana' => 'required|string|max:100',
            'objetivo' => 'nullable|string',
            'plataforma' => ['required', Rule::in(CampanaPublicitaria::PLATAFORMAS)],
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => ['required', Rule::in(CampanaPublicitaria::ESTADOS)],
            'url_archivo' => 'nullable|url'
        ]);

        CampanaPublicitaria::create($request->all());

        return redirect()->route('campanas-publicitarias.index')->with('success', 'Campaña publicitaria creada exitosamente.');
    }

    public function show(CampanaPublicitaria $campanaPublicitaria)
    {
        $campanaPublicitaria->load('servicio', 'responsableMarketing');
        return view('campanas-publicitarias.show', compact('campanaPublicitaria'));
    }

    public function edit(CampanaPublicitaria $campanaPublicitaria)
    {
        $servicios = Servicio::all();
        $trabajadores = Trabajador::all();
        return view('campanas-publicitarias.edit', compact('campanaPublicitaria', 'servicios', 'trabajadores'));
    }

    public function update(Request $request, CampanaPublicitaria $campanaPublicitaria)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'responsable_marketing_id' => 'nullable|exists:trabajadores,id',
            'nombre_campana' => 'required|string|max:100',
            'objetivo' => 'nullable|string',
            'plataforma' => ['required', Rule::in(CampanaPublicitaria::PLATAFORMAS)],
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => ['required', Rule::in(CampanaPublicitaria::ESTADOS)],
            'url_archivo' => 'nullable|url'
        ]);

        $campanaPublicitaria->update($request->all());

        return redirect()->route('campanas-publicitarias.index')->with('success', 'Campaña publicitaria actualizada exitosamente.');
    }

    public function destroy(CampanaPublicitaria $campanaPublicitaria)
    {
        $campanaPublicitaria->delete();
        return redirect()->route('campanas-publicitarias.index')->with('success', 'Campaña publicitaria eliminada exitosamente.');
    }
}