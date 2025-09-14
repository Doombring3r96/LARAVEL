<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use Illuminate\Http\Request;

class EtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::paginate(10);
        return view('etiquetas.index', compact('etiquetas'));
    }

    public function create()
    {
        return view('etiquetas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:etiquetas,nombre',
            'color' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string'
        ]);

        Etiqueta::create($request->all());

        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta creada exitosamente.');
    }

    public function show(Etiqueta $etiqueta)
    {
        $etiqueta->load('tareas');
        return view('etiquetas.show', compact('etiqueta'));
    }

    public function edit(Etiqueta $etiqueta)
    {
        return view('etiquetas.edit', compact('etiqueta'));
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:etiquetas,nombre,' . $etiqueta->id,
            'color' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string'
        ]);

        $etiqueta->update($request->all());

        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta actualizada exitosamente.');
    }

    public function destroy(Etiqueta $etiqueta)
    {
        $etiqueta->delete();
        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta eliminada exitosamente.');
    }
}