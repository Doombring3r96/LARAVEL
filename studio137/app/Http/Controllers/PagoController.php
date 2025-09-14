<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('usuario', 'servicio')->paginate(10);
        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $usuarios = User::all();
        $servicios = Servicio::all();
        return view('pagos.create', compact('usuarios', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo_pago' => ['required', Rule::in(Pago::TIPOS_PAGO)],
            'servicio_id' => 'nullable|exists:servicios,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'url_comprobante' => 'nullable|url',
            'descripcion' => 'nullable|string'
        ]);

        Pago::create($request->all());

        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load('usuario', 'servicio');
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $usuarios = User::all();
        $servicios = Servicio::all();
        return view('pagos.edit', compact('pago', 'usuarios', 'servicios'));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo_pago' => ['required', Rule::in(Pago::TIPOS_PAGO)],
            'servicio_id' => 'nullable|exists:servicios,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'url_comprobante' => 'nullable|url',
            'descripcion' => 'nullable|string'
        ]);

        $pago->update($request->all());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado exitosamente.');
    }
}