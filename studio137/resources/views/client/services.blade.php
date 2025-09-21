<x-app-layout>
    <x-slot name="header">
        <h2>Servicios Activos</h2>
    </x-slot>

    <div class="row">
        @if($servicios->isEmpty())
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tienes servicios activos en este momento.</h5>
                </div>
            </div>
        </div>
        @else
        @foreach($servicios as $servicio)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $servicio->tipo }}</h5>
                    <span class="badge 
                        @if($servicio->estado == 'finalizado') bg-success
                        @elseif($servicio->estado == 'en proceso') bg-primary
                        @elseif($servicio->estado == 'cancelado') bg-danger
                        @else bg-warning text-dark
                        @endif">
                        {{ $servicio->estado }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Inicio:</strong> {{ $servicio->fecha_inicio->format('d/m/Y') }}</p>
                            @if($servicio->fecha_fin_estimada)
                            <p class="mb-1"><strong>Estimado:</strong> {{ $servicio->fecha_fin_estimada->format('d/m/Y') }}</p>
                            @endif
                            <p class="mb-0"><strong>Descripción:</strong> {{ $servicio->descripcion ?? 'Sin descripción' }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('client.service.detail', $servicio) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> Ver Detalles
                            </a>
                        </div>
                    </div>

                    <!-- Tareas que requieren acción -->
                    @php
                        $tareasPendientes = [];
                        foreach ($servicio->actividades as $actividad) {
                            foreach ($actividad->tareas as $tarea) {
                                if (in_array($tarea->titulo, ['Briefing del cliente', 'Revisión inicial del calendario', 'Revisión final del calendario']) 
                                    && in_array($tarea->estado, ['pendiente', 'en revisión'])) {
                                    $tareasPendientes[] = $tarea;
                                }
                            }
                        }
                    @endphp

                    @if(!empty($tareasPendientes))
                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-1"></i>Acciones Requeridas</h6>
                        <ul class="mb-0 mt-2">
                            @foreach($tareasPendientes as $tarea)
                            <li>
                                {{ $tarea->titulo }} - 
                                <a href="{{ route('client.service.detail', $servicio) }}#tarea-{{ $tarea->id }}" 
                                   class="alert-link">
                                    Completar ahora
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</x-app-layout>