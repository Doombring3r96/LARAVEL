<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard del Trabajador</h2>
    </x-slot>

    <div class="row">
        <!-- Resumen de Tareas -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Mis Tareas</h5>
                    <a href="{{ route('worker.tasks') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list me-1"></i> Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($tareas->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes tareas asignadas en este momento.</p>
                    </div>
                    @else
                    <div class="row">
                        @foreach($tareas->take(6) as $tarea)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm priority-{{ $tarea->prioridad }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ Str::limit($tarea->titulo, 30) }}</h6>
                                        <span class="badge bg-{{ $tarea->prioridad == 'alta' ? 'danger' : ($tarea->prioridad == 'media' ? 'warning' : 'success') }}">
                                            {{ $tarea->prioridad }}
                                        </span>
                                    </div>
                                    <p class="card-text small text-muted mb-2">
                                        {{ $tarea->actividad->servicio->tipo }} - 
                                        {{ $tarea->actividad->servicio->cliente->nombre_empresa }}
                                    </p>
                                    <p class="card-text small">
                                        <span class="badge 
                                            @if($tarea->estado == 'completada') bg-success
                                            @elseif(in_array($tarea->estado, ['en curso', 'en revisión'])) bg-primary
                                            @elseif($tarea->estado == 'retrasada') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $tarea->estado }}
                                        </span>
                                    </p>
                                    @if($tarea->fecha_fin_estimada)
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($tarea->fecha_fin_estimada)->format('d/m/Y') }}
                                    </p>
                                    @endif
                                    <a href="{{ route('worker.task.detail', $tarea) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas y Gráfico -->
        <div class="col-md-4 mb-4">
            <!-- Estadísticas Rápidas -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-primary">{{ $tareasStats['total'] }}</h4>
                                <small class="text-muted">Total Tareas</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-success">{{ $tareasStats['completadas'] }}</h4>
                                <small class="text-muted">Completadas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-warning">{{ $tareasStats['en_proceso'] }}</h4>
                                <small class="text-muted">En Proceso</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-danger">{{ $tareasStats['retrasadas'] }}</h4>
                                <small class="text-muted">Retrasadas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Tareas por Servicio -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Tareas por Servicio</h5>
                </div>
                <div class="card-body">
                    <canvas id="servicesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de tareas por servicio
            const ctx = document.getElementById('servicesChart').getContext('2d');
            const servicesChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($tareasPorServicio->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($tareasPorServicio->values()) !!},
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                            '#e83e8c', '#fd7e14', '#20c997', '#6610f2', '#6c757d'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>