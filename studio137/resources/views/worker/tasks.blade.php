<x-app-layout>
    <x-slot name="header">
        <h2>Mis Tareas</h2>
    </x-slot>

    <div class="row">
        <!-- Filtros -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Prioridad</label>
                            <select name="prioridad" class="form-select">
                                <option value="">Todas las prioridades</option>
                                <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                                <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en curso" {{ request('estado') == 'en curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="en revisión" {{ request('estado') == 'en revisión' ? 'selected' : '' }}>En Revisión</option>
                                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="retrasada" {{ request('estado') == 'retrasada' ? 'selected' : '' }}>Retrasada</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Tareas -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Tareas Asignadas</h5>
                </div>
                <div class="card-body">
                    @if($tareas->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No se encontraron tareas con los filtros aplicados.</p>
                    </div>
                    @else
                    <div class="row">
                        @foreach($tareas as $tarea)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm priority-{{ $tarea->prioridad }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title">{{ $tarea->titulo }}</h6>
                                        <span class="badge bg-{{ $tarea->prioridad == 'alta' ? 'danger' : ($tarea->prioridad == 'media' ? 'warning' : 'success') }}">
                                            {{ $tarea->prioridad }}
                                        </span>
                                    </div>
                                    
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Servicio:</strong> {{ $tarea->actividad->servicio->tipo }}<br>
                                        <strong>Cliente:</strong> {{ $tarea->actividad->servicio->cliente->nombre_empresa }}<br>
                                        <strong>Actividad:</strong> {{ $tarea->actividad->nombre }}
                                    </p>
                                    
                                    <p class="card-text mb-3">{{ Str::limit($tarea->descripcion, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge 
                                            @if($tarea->estado == 'completada') bg-success
                                            @elseif(in_array($tarea->estado, ['en curso', 'en revisión'])) bg-primary
                                            @elseif($tarea->estado == 'retrasada') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $tarea->estado }}
                                        </span>
                                        
                                        @if($tarea->fecha_fin_estimada)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($tarea->fecha_fin_estimada)->format('d/m/Y') }}
                                        </small>
                                        @endif
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('worker.task.detail', $tarea) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>