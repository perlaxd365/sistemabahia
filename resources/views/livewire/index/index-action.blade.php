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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🩺 Atenciones Activas</h5>

                    <input type="text" class="form-control w-25" placeholder="Buscar paciente..."
                        wire:model.debounce.500ms="search">
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Paciente</th>
                                <th>DNI</th>
                                <th>Médico</th>
                                <th>Fecha</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($atenciones as $index => $atencion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $atencion->paciente->name ?? '-' }}</td>
                                    <td>{{ $atencion->paciente->dni ?? '-' }}</td>
                                    <td>{{ $atencion->medico->name ?? '-' }}</td>
                                    <td>{{ $atencion->created_at->format('d/m/Y H:i') }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}"><u>Ir
                                                a atención</u></a>
                                    </td>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        No hay atenciones activas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
