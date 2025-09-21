<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard del Cliente</h2>
    </x-slot>

    <div class="row">
        <!-- Estado del Servicio -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Estado de tus Servicios</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($servicios as $servicio)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $servicio->tipo }}</h6>
                                    <p class="card-text mb-1">
                                        <small class="text-muted">Estado:</small>
                                        <span class="badge 
                                            @if($servicio->estado == 'finalizado') bg-success
                                            @elseif($servicio->estado == 'en proceso') bg-primary
                                            @elseif($servicio->estado == 'cancelado') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $servicio->estado }}
                                        </span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <small class="text-muted">Inicio:</small>
                                        {{ $servicio->fecha_inicio->format('d/m/Y') }}
                                    </p>
                                    @if($servicio->fecha_fin_estimada)
                                    <p class="card-text mb-0">
                                        <small class="text-muted">Estimado:</small>
                                        {{ $servicio->fecha_fin_estimada->format('d/m/Y') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Progreso -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Progreso de Tareas</h5>
                </div>
                <div class="card-body">
                    <canvas id="tasksChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Informes y Pagos -->
        <div class="col-lg-4 mb-4">
            <!-- Informes -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-file-alt me-2"></i>Informes de Campañas</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Accede a los informes de tus campañas publicitarias anteriores.</p>
                    <a href="{{ route('client.reports') }}" class="btn btn-success">
                        <i class="fas fa-eye me-1"></i> Ver Informes
                    </a>
                </div>
            </div>

            <!-- Estado de Pagos -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-credit-card me-2"></i>Estado de Pagos</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-2">
                        Deuda actual: 
                        <span class="fw-bold text-danger">${{ number_format($deudaTotal, 2) }}</span>
                    </p>
                    @if($proximoPago)
                    <p class="card-text mb-3">
                        Próximo pago: {{ $proximoPago->fecha_pago->format('d/m/Y') }}
                    </p>
                    @endif
                    <a href="{{ route('client.payments') }}" class="btn btn-warning">
                        <i class="fas fa-cog me-1"></i> Gestionar Pagos
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('tasksChart').getContext('2d');
            const tasksChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completadas', 'En Proceso', 'Pendientes'],
                    datasets: [{
                        data: [
                            {{ $tareasStats['completadas'] }},
                            {{ $tareasStats['en_proceso'] }},
                            {{ $tareasStats['pendientes'] }}
                        ],
                        backgroundColor: [
                            '#28a745',
                            '#007bff',
                            '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Tareas'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>