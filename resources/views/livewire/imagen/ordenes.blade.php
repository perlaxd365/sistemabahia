<div>
    <div class="card shadow-sm border-0">

        <!-- CABECERA -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-primary">
                        🔬 Órdenes de Imágenes
                    </h5>
                    <small class="text-muted">
                        Gestión clínica de exámenes
                    </small>
                </div>

                <div class="w-25">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm"
                        placeholder="🔍 Paciente / DNI / H.C.">
                </div>
            </div>
        </div>

        <!-- TABLA -->
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small">

                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Área</th>
                        <th>Estudios</th>
                        <th>Diagnóstico</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id_orden_imagen }}</td>
                            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $orden->atencion->paciente->name }}</td>
                            <td>
                                {{ $orden->detalles->first()->estudio->area->nombre ?? '-' }}
                            </td>
                            <td>{{ $orden->detalles->count() }}</td>
                            <td>{{ $orden->diagnostico ?? '-' }}</td>
                            <td>
                                @if ($orden->estado === 'PENDIENTE')
                                    <span class="badge bg-warning">Pendiente</span>
                                @else
                                    <span class="badge bg-success">Informado</span>
                                @endif
                            </td>
                            <td>
                                @if ($orden->estado === 'PENDIENTE')
                                    <a href="{{ route('imagen.resultados', $orden->id_orden_imagen) }}"
                                        class="btn btn-sm btn-secondary">
                                        🖼️ Subir resultados
                                    </a>
                                @elseif($orden->estado === 'INFORMADO')
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        🔒 Cerrado
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
