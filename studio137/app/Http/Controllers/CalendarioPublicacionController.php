<?php

namespace App\Http\Controllers;

use App\Models\CalendarioPublicacion;
use App\Models\Servicio;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalendarioPublicacionController extends Controller
{
    public function index()
    {
        $calendarios = CalendarioPublicacion::with('servicio', 'responsableMarketing')->paginate(10);
        return view('calendarios-publicacion.index', compact('calendarios'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $trabajadores = Trabajador::all();
        return view('calendarios-publicacion.create', compact('servicios', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'responsable_marketing_id' => 'nullable|exists:trabajadores,id',
            'mes_publicacion' => 'required|string|max:20',
            'anio_publicacion' => 'required|integer|min:2000|max:2100',
            'url_documento' => 'nullable|url',
            'estado' => ['required', Rule::in(CalendarioPublicacion::ESTADOS)],
            'fecha_creacion' => 'nullable|date',
            'fecha_aprobacion' => 'nullable|date',
            'fecha_publicacion' => 'nullable|date'
        ]);

        CalendarioPublicacion::create($request->all());

        return redirect()->route('calendarios-publicacion.index')->with('success', 'Calendario de publicación creado exitosamente.');
    }

    public function show(CalendarioPublicacion $calendarioPublicacion)
    {
        $calendarioPublicacion->load('servicio', 'responsableMarketing', 'artes');
        return view('calendarios-publicacion.show', compact('calendarioPublicacion'));
    }

    public function edit(CalendarioPublicacion $calendarioPublicacion)
    {
        $servicios = Servicio::all();
        $trabajadores = Trabajador::all();
        return view('calendarios-publicacion.edit', compact('calendarioPublicacion', 'servicios', 'trabajadores'));
    }

    public function update(Request $request, CalendarioPublicacion $calendarioPublicacion)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'responsable_marketing_id' => 'nullable|exists:trabajadores,id',
            'mes_publicacion' => 'required|string|max:20',
            'anio_publicacion' => 'required|integer|min:2000|max:2100',
            'url_documento' => 'nullable|url',
            'estado' => ['required', Rule::in(CalendarioPublicacion::ESTADOS)],
            'fecha_creacion' => 'nullable|date',
            'fecha_aprobacion' => 'nullable|date',
            'fecha_publicacion' => 'nullable|date'
        ]);

        $calendarioPublicacion->update($request->all());

        return redirect()->route('calendarios-publicacion.index')->with('success', 'Calendario de publicación actualizado exitosamente.');
    }

    public function destroy(CalendarioPublicacion $calendarioPublicacion)
    {
        $calendarioPublicacion->delete();
        return redirect()->route('calendarios-publicacion.index')->with('success', 'Calendario de publicación eliminado exitosamente.');
    }
}