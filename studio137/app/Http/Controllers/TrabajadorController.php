<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Pago;
use App\Models\PiezaGrafica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrabajadorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $trabajador = $user->trabajador;
        
        if (!$trabajador) {
            abort(404, 'Trabajador no encontrado');
        }
        
        // Obtener tareas asignadas al trabajador
        $tareas = Tarea::with(['actividad.servicio.cliente', 'piezasGraficas'])
            ->where('trabajador_id', $trabajador->id)
            ->orderBy('prioridad', 'desc')
            ->orderBy('fecha_fin_estimada')
            ->get();
        
        // Calcular estadísticas de tareas
        $tareasStats = [
            'total' => $tareas->count(),
            'completadas' => $tareas->where('estado', 'completada')->count(),
            'en_proceso' => $tareas->whereIn('estado', ['en curso', 'en revisión'])->count(),
            'pendientes' => $tareas->where('estado', 'pendiente')->count(),
            'retrasadas' => $tareas->where('estado', 'retrasada')->count()
        ];
        
        // Agrupar tareas por servicio para el gráfico
        $tareasPorServicio = $tareas->groupBy('actividad.servicio.tipo')->map->count();
        
        // Obtener pagos del trabajador
        $pagos = Pago::where('usuario_id', $user->id)
            ->where('tipo_pago', 'trabajador')
            ->orderBy('fecha_pago', 'desc')
            ->limit(5)
            ->get();
        
        return view('worker.dashboard', compact('tareas', 'tareasStats', 'tareasPorServicio', 'pagos'));
    }

    public function tasks(Request $request)
    {
        $user = Auth::user();
        $trabajador = $user->trabajador;
        
        $query = Tarea::with(['actividad.servicio.cliente', 'piezasGraficas'])
            ->where('trabajador_id', $trabajador->id);
        
        // Filtros
        if ($request->has('prioridad') && $request->prioridad) {
            $query->where('prioridad', $request->prioridad);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        $tareas = $query->orderBy('fecha_fin_estimada')->get();
        
        return view('worker.tasks', compact('tareas'));
    }

    public function taskDetail(Tarea $tarea)
    {
        // Verificar que la tarea pertenece al trabajador
        $user = Auth::user();
        if ($tarea->trabajador_id !== $user->trabajador->id) {
            abort(403, 'No tienes acceso a esta tarea');
        }
        
        $tarea->load(['actividad.servicio.cliente', 'piezasGraficas']);
        
        return view('worker.task-detail', compact('tarea'));
    }

    public function updateTask(Request $request, Tarea $tarea)
    {
        // Verificar permisos
        $user = Auth::user();
        if ($tarea->trabajador_id !== $user->trabajador->id) {
            abort(403, 'No tienes acceso a esta tarea');
        }
        
        $request->validate([
            'estado' => 'required|in:en curso,en revisión,completada',
            'archivo' => 'sometimes|file|max:10240',
            'comentarios' => 'sometimes|string|max:500'
        ]);
        
        // Actualizar la tarea
        $tarea->estado = $request->estado;
        
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('worker-files/' . $user->id);
            
            // Crear o actualizar pieza gráfica si es diseñador
            if ($user->isDisenadorGrafico()) {
                $pieza = PiezaGrafica::where('tarea_id', $tarea->id)->first();
                if (!$pieza) {
                    $pieza = new PiezaGrafica();
                    $pieza->tarea_id = $tarea->id;
                    $pieza->tipo = 'arte publicitario'; // Valor por defecto
                    $pieza->estado = 'en revisión';
                }
                $pieza->url_archivo = $path;
                $pieza->save();
            }
            
            $tarea->descripcion .= "\n\nArchivo subido: " . $path;
        }
        
        if ($request->filled('comentarios')) {
            $tarea->descripcion .= "\n\nComentarios del trabajador: " . $request->comentarios;
        }
        
        $tarea->save();
        
        return redirect()->back()->with('success', 'Tarea actualizada correctamente');
    }

    public function payments()
    {
        $user = Auth::user();
        
        $pagos = Pago::where('usuario_id', $user->id)
            ->where('tipo_pago', 'trabajador')
            ->orderBy('fecha_pago', 'desc')
            ->get();
            
        $totalPagado = $pagos->where('url_comprobante', '!=', null)->sum('monto');
        $pendiente = $pagos->where('url_comprobante', null)->sum('monto');
        
        return view('worker.payments', compact('pagos', 'totalPagado', 'pendiente'));
    }
}