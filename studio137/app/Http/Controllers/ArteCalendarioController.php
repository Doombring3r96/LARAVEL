<?php

namespace App\Http\Controllers;

use App\Models\ArteCalendario;
use App\Models\CalendarioPublicacion;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArteCalendarioController extends Controller
{
    public function index()
    {
        $artes = ArteCalendario::with('calendario', 'disenador')->paginate(10);
        return view('artes-calendario.index', compact('artes'));
    }

    public function create()
    {
        $calendarios = CalendarioPublicacion::all();
        $trabajadores = Trabajador::all();
        return view('artes-calendario.create', compact('calendarios', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'calendario_id' => 'required|exists:calendarios_publicacion,id',
            'disenador_id' => 'nullable|exists:trabajadores,id',
            'titulo' => 'nullable|string|max:100',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'fecha_publicacion_programada' => 'nullable|date',
            'url_arte' => 'nullable|url',
            'estado' => ['required', Rule::in(ArteCalendario::ESTADOS)]
        ]);

        ArteCalendario::create($request->all());

        return redirect()->route('artes-calendario.index')->with('success', 'Arte de calendario creado exitosamente.');
    }

    public function show(ArteCalendario $arteCalendario)
    {
        $arteCalendario->load('calendario', 'disenador');
        return view('artes-calendario.show', compact('arteCalendario'));
    }

    public function edit(ArteCalendario $arteCalendario)
    {
        $calendarios = CalendarioPublicacion::all();
        $trabajadores = Trabajador::all();
        return view('artes-calendario.edit', compact('arteCalendario', 'calendarios', 'trabajadores'));
    }

    public function update(Request $request, ArteCalendario $arteCalendario)
    {
        $request->validate([
            'calendario_id' => 'required|exists:calendarios_publicacion,id',
            'disenador_id' => 'nullable|exists:trabajadores,id',
            'titulo' => 'nullable|string|max:100',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'fecha_publicacion_programada' => 'nullable|date',
            'url_arte' => 'nullable|url',
            'estado' => ['required', Rule::in(ArteCalendario::ESTADOS)]
        ]);

        $arteCalendario->update($request->all());

        return redirect()->route('artes-calendario.index')->with('success', 'Arte de calendario actualizado exitosamente.');
    }

    public function destroy(ArteCalendario $arteCalendario)
    {
        $arteCalendario->delete();
        return redirect()->route('artes-calendario.index')->with('success', 'Arte de calendario eliminado exitosamente.');
    }
}