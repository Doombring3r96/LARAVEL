<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard del Trabajador</h2>
    </x-slot>
    
    <div class="row">
        <!-- Estadísticas Rápidas -->
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $tareasStats['total'] }}</h4>
                            <p class="card-text">Total Tareas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $tareasStats['completadas'] }}</h4>
                            <p class="card-text">Completadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $tareasStats['en_proceso'] }}</h4>
                            <p class="card-text">En Proceso</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ $tareasStats['retrasadas'] }}</h4>
                            <p class="card-text">Retrasadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Tareas por Servicio -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Tareas por Servicio</h5>
                </div>
                <div class="card-body">
                    <canvas id="tasksByServiceChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Tareas Recientes -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Tareas Recientes</h5>
                </div>
                <div class="card-body">
                    @foreach($tareas->take(5) as $tarea)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                        <div>
                            <h6 class="mb-1">{{ Str::limit($tarea->titulo, 25) }}</h6>
                            <small class="text-muted">{{ $tarea->actividad->servicio->tipo }}</small>
                        </div>
                        <span class="badge bg-{{ $tarea->prioridad == 'alta' ? 'danger' : ($tarea->prioridad == 'media' ? 'warning' : 'success') }}">
                            {{ $tarea->prioridad }}
                        </span>
                    </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('worker.tasks') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list me-1"></i> Ver Todas las Tareas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas que Requieren Atención -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Tareas que Requieren Atención</h5>
                </div>
                <div class="card-body">
                    @php
                        $tareasUrgentes = $tareas->where('prioridad', 'alta')->where('estado', '!=', 'completada');
                    @endphp
                    
                    @if($tareasUrgentes->isEmpty())
                    <p class="text-muted text-center mb-0">No hay tareas urgentes pendientes.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tarea</th>
                                    <th>Servicio</th>
                                    <th>Cliente</th>
                                    <th>Fecha Límite</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tareasUrgentes as $tarea)
                                <tr>
                                    <td>{{ $tarea->titulo }}</td>
                                    <td>{{ $tarea->actividad->servicio->tipo }}</td>
                                    <td>{{ $tarea->actividad->servicio->cliente->nombre_empresa }}</td>
                                    <td>{{ $tarea->fecha_fin_estimada->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('worker.task.detail', $tarea) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i> Trabajar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de tareas por servicio
            const ctx = document.getElementById('tasksByServiceChart').getContext('2d');
            const tasksByServiceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($tareasPorServicio->keys()) !!},
                    datasets: [{
                        label: 'Tareas por Servicio',
                        data: {!! json_encode($tareasPorServicio->values()) !!},
                        backgroundColor: [
                            '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>