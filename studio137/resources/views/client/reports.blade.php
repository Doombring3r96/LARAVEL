<x-app-layout>
    <x-slot name="header">
        <h2>Informes de Marketing</h2>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-file-alt me-2"></i>Informes Disponibles</h5>
                </div>
                <div class="card-body">
                    @if($informes->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay informes disponibles en este momento.</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Servicio</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($informes as $informe)
                                <tr>
                                    <td>{{ $informe->titulo }}</td>
                                    <td>{{ $informe->servicio->tipo }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $informe->tipo }}</span>
                                    </td>
                                    <td>{{ $informe->fecha_subida->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ $informe->url_archivo }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="fas fa-download me-1"></i> Descargar
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
</x-app-layout>