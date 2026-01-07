<div>
    <div class="card shadow-sm border-0">

        <!-- CABECERA -->
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-primary">
                        🔬 Órdenes de Laboratorio
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
                        <th>Paciente</th>
                        <th>Historia</th>
                        <th>Fecha</th>
                        <th>Exámenes</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($ordenes as $orden)
                        <tr>
                            <!-- PACIENTE -->
                            <td>
                                <strong class="text-clinico">
                                    {{ $orden->atencion->paciente->nombres }}
                                </strong>
                                <div class="small text-muted">
                                    DNI: {{ $orden->atencion->paciente->dni }}
                                </div>
                            </td>

                            <!-- HISTORIA -->
                            <td>
                                {{ $orden->atencion->historia->nro_historia }}
                            </td>

                            <!-- FECHA -->
                            <td>
                                {{ $orden->created_at->format('d/m/Y') }}
                                <div class="small text-muted">
                                    {{ $orden->created_at->format('H:i') }}
                                </div>
                            </td>

                            <!-- EXÁMENES -->
                            <td>
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($orden->detalles as $det)
                                        <li>
                                            {{ $det->examenes->nombre ?? 'Examen manual' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                @if ($orden->estado === 'PENDIENTE')
                                    <span class="badge bg-warning text-dark">
                                        PENDIENTE
                                    </span>
                                @elseif ($orden->estado === 'PROCESO')
                                    <span class="badge bg-info">
                                        EN PROCESO
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        FINALIZADO
                                    </span>
                                @endif
                            </td>

                            <!-- ACCIÓN -->
                            <td class="text-end">
                                @if ($orden->estado === 'PENDIENTE')
                                    <a href="{{ route('laboratorio.resultados', $orden->id_orden) }}"
                                        class="btn btn-sm btn-primary">
                                        🧪 Subir resultados
                                    </a>
                                @elseif($orden->estado === 'PROCESO')
                                    <a href="{{ route('laboratorio.resultados', $orden->id_orden) }}"
                                        class="btn btn-sm btn-primary">
                                        🧪 Editar resultados
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        🔒 Cerrado
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No se encontraron órdenes
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>
</div>
