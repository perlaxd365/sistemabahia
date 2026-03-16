<div>
    <div class="container py-4">

        {{-- VISTA DE ADMIN --}}
        @if ($privilegio == 1)
            <h2 class="fw-bold mb-4">📊 Indicadores del Sistema</h2>

            <div class="row g-4">
                <!-- Total Usuarios -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #4e8fbb;">
                        <div class="icono">👥</div>
                        <div class="titulo">Total Usuarios</div>
                        <p class="valor">{{ $totalUsuarios }}</p>
                    </div>
                </div>

                <!-- Usuarios Activos -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #59c773;">
                        <div class="icono">✔️</div>
                        <div class="titulo">Usuarios Activos</div>
                        <p class="valor">{{ $activos }}</p>
                    </div>
                </div>

                <!-- Usuarios Inactivos -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #e75b69;">
                        <div class="icono">⛔</div>
                        <div class="titulo">Inactivos</div>
                        <p class="valor">{{ $inactivos }}</p>
                    </div>
                </div>

                <!-- Nuevos Usuarios -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #8a5edd;">
                        <div class="icono">📅</div>
                        <div class="titulo">Nuevos (30 días)</div>
                        <p class="valor">{{ $nuevos }}</p>
                    </div>
                </div>

            </div>
        @endif
        <br>
        <div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 fw-semibold text-dark">🩺 Atenciones Activas</h5>
                        <small class="text-muted">Pacientes en proceso de atención</small>
                    </div>

                    <input type="text" class="form-control w-25 rounded-pill" placeholder="Buscar paciente..."
                        wire:model.live.500ms="search">
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase small text-muted">
                                <th width="50">#</th>
                                <th>Paciente</th>
                                <th width="140">Historia Clínica</th>
                                <th width="50">Médico</th>
                                <th width="160">Fecha</th>
                                <th width="140" class="text-center">Comprobante</th>
                                <th width="140" class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($atenciones as $index => $atencion)
                                <tr>
                                    <td class="fw-semibold text-muted">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- PACIENTE + DNI --}}
                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ $atencion->paciente->name ?? '-' }}
                                        </div>
                                        <small class="text-muted">
                                            DNI: {{ $atencion->paciente->dni ?? '-' }}
                                        </small>
                                    </td>
                                    <link rel="stylesheet"
                                        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

                                    {{-- HISTORIA CLÍNICA --}}
                                    <td class="text-center">

                                        {{-- Historia clínica --}}
                                        <div class="fw-semibold text-dark">
                                            <i class="bi bi-journal-medical text-primary me-1"></i>
                                            {{ $atencion->historia->nro_historia ?? 'Sin historia' }}
                                        </div>

                                        {{-- Estado de consulta --}}
                                        <div class="mt-1">
                                            @if ($atencion->consulta)
                                                <span
                                                    class="badge rounded-pill bg-success-subtle text-success border px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Consulta abierta
                                                </span>
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-danger-subtle text-danger border px-3 py-2">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    No iniciada
                                                </span>
                                            @endif
                                        </div>

                                    </td>
                                    {{-- MÉDICO --}}
                                    <td>
                                        @if (isset($atencion->medico->name))
                                            {{-- Estado --}}
                                            <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning ">


                                                <div class="fw-semibold">
                                                    {{ $atencion->medico->name }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ $atencion->medico->nombre_cargo ?? 'Médico tratante' }}

                                                    @if ($atencion->medico->especialidad_cargo == '')
                                                        {{ '/ Medicina General' }}
                                                    @else
                                                        / {{ $atencion->medico->especialidad_cargo }}
                                                    @endif

                                                </small>
                                        @endif
                                    </td>
                                    {{-- FECHA --}}
                                    <td>
                                        <div class="fw-medium">
                                            {{ $atencion->created_at->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $atencion->created_at->format('H:i') }}
                                        </small>
                                    </td>

                                    <td class="text-center">

                                        @php
                                            $estado = 'SIN_COMPROBANTE';

                                            if ($atencion->comprobantes->where('estado', 'EMITIDO')->count()) {
                                                $estado = 'EMITIDO';
                                            } elseif ($atencion->comprobantes->where('estado', 'PENDIENTE')->count()) {
                                                $estado = 'PENDIENTE';
                                            } elseif ($atencion->comprobantes->where('estado', 'BORRADOR')->count()) {
                                                $estado = 'BORRADOR';
                                            }

                                            $responsable = UserUtil::getUserByID($atencion->id_responsable);
                                        @endphp


                                        <div
                                            class="d-inline-block text-start px-3 py-2 rounded-3  bg-light-subtle shadow-sm">

                                            {{-- Responsable --}}
                                            <div class="small text-muted mb-1">
                                                <i class="bi bi-person-circle me-1"></i>
                                                Responsable: {{ $responsable->nombres }}
                                                {{ $responsable->apellido_paterno }}
                                            </div>


                                            {{-- Estado --}}
                                            @if ($estado === 'BORRADOR')
                                                <span
                                                    class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning border">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Borrador
                                                </span>
                                            @elseif ($estado === 'PENDIENTE')
                                                <span
                                                    class="badge rounded-pill px-3 py-2 bg-secondary-subtle text-secondary border">
                                                    <i class="bi bi-hourglass-split me-1"></i>
                                                    Pendiente
                                                </span>
                                            @elseif ($estado === 'EMITIDO')
                                                <span
                                                    class="badge rounded-pill px-3 py-2 bg-success-subtle text-success border">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Emitido, pendiente finalizar
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-3 py-2 bg-light text-muted border">
                                                    <i class="bi bi-receipt me-1"></i>
                                                    Sin comprobante
                                                </span>
                                            @endif

                                        </div>

                                    </td>
                                    {{-- ACCIONES --}}
                                    <td class="text-center">

                                        <a href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}">
                                            <small class="badge badge-success">Abrir atención</small></a>

                                        @can('ver-caja')
                                            <a href="javascript:void(0)"
                                                wire:click='anular({{ $atencion->id_atencion }})'><small
                                                    class="badge badge-warning mt-2"
                                                    onclick=" return confirm('¿Seguro que deseas anular esta atención?') || event.stopImmediatePropagation()">Anular
                                                    Atencion</small></a>
                                        @endcan
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No hay atenciones activas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div wire:ignore.self class="card-footer text-right">
                {{ $atenciones->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
