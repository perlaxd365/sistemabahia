<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-primary mb-0">
                <i class="fas fa-file-medical me-2"></i>
                Tramas SUSALUD
            </h5>
            <small class="text-muted">Control y validación de atenciones</small>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Desde</label>
                    <input type="date" wire:model="fecha_inicio"
                        class="form-control form-control-sm rounded-3">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Hasta</label>
                    <input type="date" wire:model="fecha_fin"
                        class="form-control form-control-sm rounded-3">
                </div>

                <div class="col-md-2">
                    <button wire:click="filtrar"
                        class="btn btn-sm btn-primary w-100 rounded-3">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="row g-2 mb-3 text-center">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm py-2">
                <small class="text-muted">Total</small>
                <h6 class="fw-bold mb-0">{{ $total }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm py-2 bg-success-subtle">
                <small class="text-success">Válidas</small>
                <h6 class="fw-bold text-success mb-0">{{ $validas }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm py-2 bg-danger-subtle">
                <small class="text-danger">Incompletas</small>
                <h6 class="fw-bold text-danger mb-0">{{ $incompletas }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm py-2 bg-primary-subtle">
                <small class="text-primary">Enviadas</small>
                <h6 class="fw-bold text-primary mb-0">{{ $enviadas }}</h6>
            </div>
        </div>

    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-2 table-responsive">

            <table class="table table-sm table-hover align-middle mb-0">

                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>CIE10</th>
                        <th>Médico</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody class="small">

                    @forelse($atenciones as $atencion)

                        @php
                            $diagnosticoPrincipal = $atencion->diagnosticos
                                ->where('tipo','PRINCIPAL')
                                ->first();
                        @endphp

                        <tr>

                            <td class="fw-semibold">
                                {{ $atencion->id_atencion }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($atencion->fecha_inicio_atencion)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $atencion->paciente->name ?? '-' }}
                            </td>

                            <td>
                                {{ $atencion->paciente->dni ?? '-' }}
                            </td>

                            <td>
                                {{ $diagnosticoPrincipal->cie10->codigo ?? '-' }}
                            </td>

                            <td>
                                {{ $atencion->medico->name ?? '-' }}
                            </td>

                            {{-- ESTADO --}}
                            <td>

                                @if ($atencion->estado !== 'FINALIZADO')
                                    <span class="badge bg-warning-subtle text-warning small">
                                        En proceso
                                    </span>

                                @elseif($atencion->enviado_susalud)
                                    <span class="badge bg-primary-subtle text-primary small">
                                        Enviado
                                    </span>

                                @else
                                    <span class="badge bg-success-subtle text-success small">
                                        Finalizada
                                    </span>
                                @endif

                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-center">

                                @if ($atencion->estado !== 'FINALIZADO')
                                    <a href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}"
                                       class="btn badge ">
                                        Abrir
                                    </a>

                                @elseif(!$atencion->enviado_susalud)
                                    <span class="badge bg-success-subtle text-success small">
                                        Lista
                                    </span>

                                @else
                                    <span class="badge bg-secondary-subtle text-secondary small">
                                        Enviada
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3 small">
                                No hay registros en este rango.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

    {{-- BOTONES --}}
    <div class="mt-3 d-flex gap-2">

        <button wire:click="generarTrama"
            class="btn btn-sm btn-primary rounded-pill px-4"
            {{ $validas > 0 ? '' : 'disabled' }}>
            Generar Trama
        </button>

        <button wire:click="marcarNoEnviado"
            class="btn btn-sm btn-outline-warning rounded-pill px-4">
            Marcar no enviado
        </button>

    </div>

</div>