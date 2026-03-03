<div class="card border-0 shadow-sm rounded-4">

    <!-- HEADER -->
    <div class="card-body border-bottom bg-white rounded-top-4 py-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h5 class="fw-bold mb-1" style="color:#1e3a8a;">
                    Órdenes de Laboratorio
                </h5>
                <small class="text-muted">
                    Gestión clínica de exámenes
                </small>
            </div>

            <div style="min-width:260px;">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       class="form-control form-control-sm rounded-pill px-3"
                       placeholder="Buscar paciente / DNI / H.C.">
            </div>

        </div>

    </div>


    <!-- CONTENIDO -->
    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead style="background-color:#f8fafc;" class="text-uppercase small text-muted">
                <tr>
                    <th>Atención</th>
                    <th>Solicitante</th>
                    <th>Paciente</th>
                    <th>Historia</th>
                    <th>Fecha</th>
                    <th>Exámenes</th>
                    <th>Estado</th>
                    @can('editar-laboratorio')
                        <th class="text-end">Acción</th>
                    @endcan
                </tr>
            </thead>

            <tbody>

            @forelse ($ordenes as $orden)

                <tr class="border-top">

                    <!-- ATENCIÓN -->
                    <td class="fw-semibold text-secondary">
                        #{{ $orden->atencion->id_atencion }}
                    </td>


                    <!-- SOLICITANTE -->
                    <td>
                        <div class="fw-semibold">
                            {{ UserUtil::getUserByID($orden->solicitante)->name }}
                        </div>
                        <div class="small text-muted">
                            {{ UserUtil::getUserByID($orden->solicitante)->nombre_cargo }}
                        </div>
                    </td>


                    <!-- PACIENTE -->
                    <td>
                        <div class="fw-semibold">
                            {{ $orden->atencion->paciente->name }}
                        </div>
                        <div class="small text-muted">
                            DNI: {{ $orden->atencion->paciente->dni }}
                        </div>
                    </td>


                    <!-- HISTORIA -->
                    <td class="fw-semibold">
                        {{ $orden->atencion->historia->nro_historia }}
                    </td>


                    <!-- FECHA -->
                    <td>
                        <div>
                            {{ $orden->created_at->format('d/m/Y') }}
                        </div>
                        <div class="small text-muted">
                            {{ $orden->created_at->format('H:i') }}
                        </div>
                    </td>


                    <!-- EXÁMENES COMPACTOS -->
                    <td style="max-width:300px;">
                        <div class="d-flex flex-wrap gap-1">

                            @foreach ($orden->detalles->take(3) as $det)
                                <span class="badge rounded-pill bg-light text-dark border small">
                                    {{ $det->examenes->nombre ?? 'Manual' }}
                                </span>
                            @endforeach

                            @if ($orden->detalles->count() > 3)
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary small">
                                    +{{ $orden->detalles->count() - 3 }}
                                </span>
                            @endif

                        </div>
                    </td>


                    <!-- ESTADO ELEGANTE -->
                    <td>
                        @if ($orden->estado === 'PENDIENTE')
                            <span class="px-3 py-2 rounded-pill small fw-semibold"
                                  style="background:#fef3c7;color:#92400e;">
                                Pendiente
                            </span>

                        @elseif ($orden->estado === 'PROCESO')
                            <span class="px-3 py-2 rounded-pill small fw-semibold"
                                  style="background:#e0f2fe;color:#075985;">
                                En Proceso
                            </span>

                        @else
                            <span class="px-3 py-2 rounded-pill small fw-semibold"
                                  style="background:#dcfce7;color:#166534;">
                                Finalizado
                            </span>
                        @endif
                    </td>


                    <!-- ACCIONES -->
                    <td class="text-end">
                        @can('editar-laboratorio')

                            <a href="{{ route('laboratorio.resultados', $orden->id_orden) }}"
                               class="btn btn-sm rounded-pill px-3
                               {{ $orden->estado === 'FINALIZADO'
                                    ? 'btn-outline-secondary'
                                    : 'btn-primary' }}">

                                @if ($orden->estado === 'PENDIENTE')
                                    Subir
                                @elseif($orden->estado === 'PROCESO')
                                    Editar
                                @else
                                    Ver
                                @endif

                            </a>

                        @endcan
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        No se encontraron órdenes
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>