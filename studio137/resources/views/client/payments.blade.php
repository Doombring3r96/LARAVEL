<x-app-layout>
    <x-slot name="header">
        <h2>Gestión de Pagos</h2>
    </x-slot>

    <div class="row">
        <!-- Resumen de Deuda -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave me-2"></i>Resumen de Deuda</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-danger">${{ number_format($deudaTotal, 2) }}</h3>
                    <p class="text-muted">Deuda total pendiente</p>
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
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos as $pago)
                                <tr>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td>${{ number_format($pago->monto, 2) }}</td>
                                    <td>{{ $pago->descripcion ?? 'Sin descripción' }}</td>
                                    <td>
                                        @if($pago->url_comprobante)
                                        <span class="badge bg-success">Verificado</span>
                                        @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$pago->url_comprobante)
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal-{{ $pago->id }}">
                                            <i class="fas fa-upload me-1"></i> Subir Comprobante
                                        </button>
                                        @else
                                        <a href="{{ Storage::url($pago->url_comprobante) }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye me-1"></i> Ver Comprobante
                                        </a>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal para subir comprobante -->
                                <div class="modal fade" id="uploadModal-{{ $pago->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Subir Comprobante de Pago</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('client.payment.upload', $pago) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Comprobante de Pago</label>
                                                        <input type="file" name="comprobante" class="form-control" accept=".pdf,.jpg,.png" required>
                                                        <div class="form-text">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Monto: ${{ number_format($pago->monto, 2) }}</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Subir Comprobante</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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