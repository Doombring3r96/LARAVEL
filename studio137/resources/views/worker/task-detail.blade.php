<x-app-layout>
    <x-slot name="header">
        <h2>Detalle de Tarea - {{ $tarea->titulo }}</h2>
    </x-slot>

    <div class="row">
        <!-- Información de la Tarea -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Información de la Tarea</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Prioridad:</strong> 
                                <span class="badge bg-{{ $tarea->prioridad == 'alta' ? 'danger' : ($tarea->prioridad == 'media' ? 'warning' : 'success') }}">
                                    {{ $tarea->prioridad }}
                                </span>
                            </p>
                            <p><strong>Estado:</strong> 
                                <span class="badge 
                                    @if($tarea->estado == 'completada') bg-success
                                    @elseif(in_array($tarea->estado, ['en curso', 'en revisión'])) bg-primary
                                    @elseif($tarea->estado == 'retrasada') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ $tarea->estado }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Servicio:</strong> {{ $tarea->actividad->servicio->tipo }}</p>
                            <p><strong>Cliente:</strong> {{ $tarea->actividad->servicio->cliente->nombre_empresa }}</p>
                            @if($tarea->fecha_fin_estimada)
                            <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_fin_estimada)->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>

                    <h6>Descripción:</h6>
                    <p class="mb-4">{{ $tarea->descripcion ?? 'Sin descripción' }}</p>

                    <!-- Piezas Gráficas (para diseñadores) -->
                    @if(auth()->user()->isDisenadorGrafico() && $tarea->piezasGraficas->isNotEmpty())
                    <h6>Archivos Subidos:</h6>
                    <div class="mb-4">
                        @foreach($tarea->piezasGraficas as $pieza)
                        <div class="alert alert-info py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-file me-2"></i>
                                    {{ basename($pieza->url_archivo) }}
                                </span>
                                <a href="{{ Storage::url($pieza->url_archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Formulario para actualizar tarea -->
                    @if(!in_array($tarea->estado, ['completada']))
                    <div class="mt-4">
                        <h6>Actualizar Tarea:</h6>
                        <form action="{{ route('worker.task.update', $tarea) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nuevo Estado</label>
                                    <select name="estado" class="form-select" required>
                                        <option value="en curso" {{ $tarea->estado == 'en curso' ? 'selected' : '' }}>En Curso</option>
                                        <option value="en revisión" {{ $tarea->estado == 'en revisión' ? 'selected' : '' }}>En Revisión</option>
                                        <option value="completada">Completada</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Subir Archivo</label>
                                    <input type="file" name="archivo" class="form-control">
                                    <small class="form-text text-muted">
                                        @if(auth()->user()->isDisenadorGrafico())
                                        Sube la pieza gráfica terminada
                                        @else
                                        Sube el calendario de publicaciones
                                        @endif
                                    </small>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Comentarios</label>
                                    <textarea name="comentarios" rows="3" class="form-control" placeholder="Agrega comentarios sobre el progreso..."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Actualizar Tarea
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del Servicio -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Información del Servicio</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $tarea->actividad->servicio->tipo }}</h6>
                    <p class="small text-muted">{{ $tarea->actividad->servicio->descripcion }}</p>
                    
                    <p class="mb-1"><strong>Cliente:</strong> {{ $tarea->actividad->servicio->cliente->nombre_empresa }}</p>
                    <p class="mb-1"><strong>Contacto:</strong> {{ $tarea->actividad->servicio->cliente->persona_contacto }}</p>
                    <p class="mb-0"><strong>Teléfono:</strong> {{ $tarea->actividad->servicio->cliente->telefono_contacto }}</p>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('worker.tasks') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Volver a Tareas
                        </a>
                        @if($tarea->actividad->servicio->cliente->telefono_contacto)
                        <a href="tel:{{ $tarea->actividad->servicio->cliente->telefono_contacto }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-1"></i> Llamar al Cliente
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>