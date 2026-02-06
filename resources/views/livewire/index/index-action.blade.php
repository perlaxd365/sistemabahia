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
                                <th>Médico</th>
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

                                    {{-- HISTORIA CLÍNICA --}}
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $atencion->historia->nro_historia ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- MÉDICO --}}
                                    <td>
                                        <span class="text-dark fw-medium">

                                        </span>




                                        <div class="pl-2">
                                            @if (isset($atencion->medico->name))
                                                <div class="d-flex align-items-center gap-1">
                                                    <div class="bg-info bg-opacity-10 rounded-circle p-1">
                                                        <img class="rounded-circle"
                                                            src="{{ $atencion->medico->foto_url }}" width="30px"
                                                            height="30px" alt="">
                                                    </div>

                                                    <div class="pl-2">

                                                        <div class="fw-semibold">
                                                            {{ $atencion->medico->name }}
                                                        </div>

                                                        <small class="text-muted">
                                                            {{ $atencion->medico->nombre_cargo ?? 'Médico tratante' }}
                                                            /
                                                            {{ $atencion->medico->nombre_especialidad ?? 'Sin Especialidad' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>




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

                                    {{-- COMPROBANTE --}}
                                    <td class="text-center">
                                        @isset($atencion->comprobante->estado)
                                            @switch($atencion->comprobante->estado)
                                                @case('BORRADOR')
                                                    <span class="badge bg-warning-subtle text-warning border px-3 py-2">
                                                        Borrador
                                                    </span>
                                                @break

                                                @case('PENDIENTE')
                                                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">
                                                        Emitido
                                                    </span>
                                                @break

                                                @case('EMITIDO')
                                                    <span class="badge bg-success-subtle text-success border px-3 py-2">
                                                        Emitido
                                                    </span>
                                                @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-light text-muted border">
                                                Aún no genera comprobante
                                            </span>
                                        @endisset
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="text-center">
                                        <a href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-1">
                                            Abrir atención
                                        </a>
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
