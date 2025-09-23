<x-app-layout>
    <x-slot name="header">
        <h2>Mis Pagos</h2>
    </x-slot>

    <div class="row">
        <!-- Resumen de Pagos -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i>Resumen de Pagos</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <h3 class="text-success">${{ number_format($totalPagado, 2) }}</h3>
                        <p class="text-muted">Total Pagado</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-warning">${{ number_format($pendiente, 2) }}</h3>
                        <p class="text-muted">Pendiente de Pago</p>
                    </div>
                    <div>
                        <h3 class="text-primary">${{ number_format($totalPagado + $pendiente, 2) }}</h3>
                        <p class="text-muted">Total Generado</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Pagos -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Historial de Pagos</h5>
                </div>
                <div class="card-body">
                    @if($pagos->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay registros de pagos.</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Descripción</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos as $pago)
                                <tr>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td class="fw-bold">${{ number_format($pago->monto, 2) }}</td>
                                    <td>{{ $pago->descripcion ?? 'Pago por servicios' }}</td>
                                    <td>
                                        @if($pago->servicio)
                                        {{ $pago->servicio->tipo }}
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pago->url_comprobante)
                                        <span class="badge bg-success">Pagado</span>
                                        @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @endif
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
</x-app-layout>