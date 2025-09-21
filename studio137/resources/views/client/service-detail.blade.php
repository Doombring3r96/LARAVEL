<x-app-layout>
    <x-slot name="header">
        <h2>Detalle del Servicio - {{ $servicio->tipo }}</h2>
    </x-slot>

    <div class="row">
        <!-- Información del Servicio -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Información del Servicio</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                <span class="badge 
                                    @if($servicio->estado == 'finalizado') bg-success
                                    @elseif($servicio->estado == 'en proceso') bg-primary
                                    @elseif($servicio->estado == 'cancelado') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ $servicio->estado }}
                                </span>
                            </p>
                            <p><strong>Fecha inicio:</strong> {{ $servicio->fecha_inicio->format('d/m/Y') }}</p>
                            @if($servicio->fecha_fin_estimada)
                            <p><strong>Fecha estimada fin:</strong> {{ $servicio->fecha_fin_estimada->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Descripción:</strong></p>
                            <p>{{ $servicio->descripcion ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividades y Tareas -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Progreso del Proyecto</h5>
                </div>
                <div class="card-body">
                    @foreach($servicio->actividades as $actividad)
                    <div class="mb-4">
                        <h6 class="text-primary">{{ $actividad->nombre }}</h6>
                        <p class="text-muted">{{ $actividad->descripcion }}</p>
                        
                        <div class="row">
                            @foreach($actividad->tareas as $tarea)
                            <div class="col-md-6 mb-3">
                                <div id="tarea-{{ $tarea->id }}" class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $tarea->titulo }}</h6>
                                            <span class="badge 
                                                @if($tarea->estado == 'completada') bg-success
                                                @elseif(in_array($tarea->estado, ['en curso', 'en revisión'])) bg-primary
                                                @elseif($tarea->estado == 'retrasada') bg-danger
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ $tarea->estado }}
                                            </span>
                                        </div>
                                        
                                        <p class="card-text small text-muted">{{ $tarea->descripcion }}</p>
                                        
                                        @if($tarea->fecha_fin_estimada)
                                        <p class="card-text small">
                                            <strong>Fecha límite:</strong> {{ $tarea->fecha_fin_estimada->format('d/m/Y') }}
                                        </p>
                                        @endif
                                        
                                        @if(in_array($tarea->titulo, ['Briefing del cliente', 'Revisión inicial del calendario', 'Revisión final del calendario']) 
                                            && in_array($tarea->estado, ['pendiente', 'en revisión']))
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-{{ $tarea->id }}">
                                            <i class="fas fa-check me-1"></i> Completar
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para completar tarea -->
                            @if(in_array($tarea->titulo, ['Briefing del cliente', 'Revisión inicial del calendario', 'Revisión final del calendario']))
                            <div class="modal fade" id="modal-{{ $tarea->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Completar: {{ $tarea->titulo }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('client.task.update', ['servicio' => $servicio, 'tarea' => $tarea]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Estado</label>
                                                    <select name="estado" class="form-select">
                                                        <option value="completada">Completada</option>
                                                        <option value="con correcciones">Con Correcciones</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Archivo</label>
                                                    <input type="file" name="archivo" class="form-control">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Comentarios</label>
                                                    <textarea name="comentarios" rows="3" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>