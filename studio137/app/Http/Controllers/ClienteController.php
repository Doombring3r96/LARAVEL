<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Tarea;
use App\Models\Pago;
use App\Models\InformeMarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        
        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }
        
        // Obtener servicios del cliente
        $servicios = Servicio::with(['actividades.tareas' => function($query) {
            $query->orderBy('estado');
        }])->where('cliente_id', $cliente->id)->get();
        
        // Calcular estadísticas de tareas
        $tareasStats = [
            'total' => 0,
            'completadas' => 0,
            'en_proceso' => 0,
            'pendientes' => 0
        ];
        
        foreach ($servicios as $servicio) {
            foreach ($servicio->actividades as $actividad) {
                foreach ($actividad->tareas as $tarea) {
                    $tareasStats['total']++;
                    if ($tarea->estado === 'completada') {
                        $tareasStats['completadas']++;
                    } elseif (in_array($tarea->estado, ['en curso', 'en revisión'])) {
                        $tareasStats['en_proceso']++;
                    } else {
                        $tareasStats['pendientes']++;
                    }
                }
            }
        }
        
        // Obtener deuda total
        $deudaTotal = $cliente->deuda;
        
        // Próximo pago (simulado - deberías tener una lógica real para esto)
        $proximoPago = Pago::where('usuario_id', $user->id)
            ->where('tipo_pago', 'cliente')
            ->where('created_at', '>', now()->subDays(30))
            ->orderBy('fecha_pago', 'desc')
            ->first();
        
        return view('client.dashboard', compact('servicios', 'tareasStats', 'deudaTotal', 'proximoPago'));
    }

    public function services()
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        
        $servicios = Servicio::with(['actividades.tareas'])
            ->where('cliente_id', $cliente->id)
            ->orderBy('estado')
            ->get();
            
        return view('client.services', compact('servicios'));
    }

    public function serviceDetail(Servicio $servicio)
    {
        // Verificar que el servicio pertenezca al cliente
        $user = Auth::user();
        if ($servicio->cliente_id !== $user->cliente->id) {
            abort(403, 'No tienes acceso a este servicio');
        }
        
        $servicio->load(['actividades.tareas' => function($query) {
            $query->orderBy('fecha_fin_estimada');
        }]);
        
        return view('client.service-detail', compact('servicio'));
    }

    public function updateTask(Request $request, Servicio $servicio, Tarea $tarea)
    {
        // Verificar permisos
        $user = Auth::user();
        if ($servicio->cliente_id !== $user->cliente->id) {
            abort(403, 'No tienes acceso a este servicio');
        }
        
        // Verificar que la tarea pertenezca al servicio
        $actividad = $tarea->actividad;
        if ($actividad->servicio_id !== $servicio->id) {
            abort(403, 'La tarea no pertenece a este servicio');
        }
        
        // Validar que el cliente puede modificar esta tarea
        $tareasModificables = [
            'Briefing del cliente',
            'Revisión inicial del calendario',
            'Revisión final del calendario'
        ];
        
        if (!in_array($tarea->titulo, $tareasModificables)) {
            abort(403, 'No puedes modificar esta tarea');
        }
        
        $request->validate([
            'estado' => 'required|in:completada,con correcciones',
            'archivo' => 'sometimes|file|max:10240',
            'comentarios' => 'sometimes|string|max:500'
        ]);
        
        // Actualizar la tarea
        $tarea->estado = $request->estado;
        
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('client-files');
            $tarea->descripcion .= "\n\nArchivo subido: " . $path;
        }
        
        if ($request->filled('comentarios')) {
            $tarea->descripcion .= "\n\nComentarios del cliente: " . $request->comentarios;
        }
        
        $tarea->save();
        
        return redirect()->back()->with('success', 'Tarea actualizada correctamente');
    }

    public function reports()
    {
        $user = Auth::user();
        $cliente = $user->cliente;
        
        $informes = InformeMarketing::with('servicio')
            ->whereHas('servicio', function($query) use ($cliente) {
                $query->where('cliente_id', $cliente->id);
            })
            ->where('visible_para_cliente', true)
            ->orderBy('fecha_subida', 'desc')
            ->get();
            
        return view('client.reports', compact('informes'));
    }

    public function payments()
    {
        $user = Auth::user();
        
        $pagos = Pago::where('usuario_id', $user->id)
            ->where('tipo_pago', 'cliente')
            ->orderBy('fecha_pago', 'desc')
            ->get();
            
        $deudaTotal = $user->cliente->deuda;
        
        return view('client.payments', compact('pagos', 'deudaTotal'));
    }

    public function uploadPayment(Request $request, Pago $pago)
    {
        // Verificar que el pago pertenece al usuario
        if ($pago->usuario_id !== Auth::id()) {
            abort(403, 'No tienes acceso a este pago');
        }
        
        $request->validate([
            'comprobante' => 'required|file|mimes:pdf,jpg,png|max:5120'
        ]);
        
        // Subir comprobante
        $path = $request->file('comprobante')->store('payment-proofs');
        
        // Actualizar pago
        $pago->url_comprobante = $path;
        $pago->estado = 'pendiente_verificacion'; // Necesitarías agregar este campo
        $pago->save();
        
        // Reducir deuda del cliente (esto debería ser más sofisticado)
        $cliente = Auth::user()->cliente;
        $cliente->deuda -= $pago->monto;
        $cliente->save();
        
        return redirect()->back()->with('success', 'Comprobante subido correctamente. Espera verificación.');
    }
}