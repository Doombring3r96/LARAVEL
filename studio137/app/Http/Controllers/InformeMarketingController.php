<?php

namespace App\Http\Controllers;

use App\Models\InformeMarketing;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InformeMarketingController extends Controller
{
    public function index()
    {
        $informes = InformeMarketing::with('servicio', 'creadoPor')->paginate(10);
        return view('informes-marketing.index', compact('informes'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $usuarios = User::all();
        return view('informes-marketing.create', compact('servicios', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'url_archivo' => 'required|url',
            'tipo' => ['required', Rule::in(InformeMarketing::TIPOS)],
            'visible_para_cliente' => 'required|boolean',
            'creado_por' => 'nullable|exists:users,id'
        ]);

        InformeMarketing::create($request->all());

        return redirect()->route('informes-marketing.index')->with('success', 'Informe de marketing creado exitosamente.');
    }

    public function show(InformeMarketing $informeMarketing)
    {
        $informeMarketing->load('servicio', 'creadoPor');
        return view('informes-marketing.show', compact('informeMarketing'));
    }

    public function edit(InformeMarketing $informeMarketing)
    {
        $servicios = Servicio::all();
        $usuarios = User::all();
        return view('informes-marketing.edit', compact('informeMarketing', 'servicios', 'usuarios'));
    }

    public function update(Request $request, InformeMarketing $informeMarketing)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'url_archivo' => 'required|url',
            'tipo' => ['required', Rule::in(InformeMarketing::TIPOS)],
            'visible_para_cliente' => 'required|boolean',
            'creado_por' => 'nullable|exists:users,id'
        ]);

        $informeMarketing->update($request->all());

        return redirect()->route('informes-marketing.index')->with('success', 'Informe de marketing actualizado exitosamente.');
    }

    public function destroy(InformeMarketing $informeMarketing)
    {
        $informeMarketing->delete();
        return redirect()->route('informes-marketing.index')->with('success', 'Informe de marketing eliminado exitosamente.');
    }
}