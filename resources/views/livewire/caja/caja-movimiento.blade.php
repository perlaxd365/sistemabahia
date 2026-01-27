<div class="container-fluid d-flex justify-content-center">
    <div class="col-xxl-8 col-xl-9 col-lg-10">

        {{-- ALERTA TURNO --}}
        @if (!$cajaTurno)
            <div class="alert alert-warning text-center py-3 mb-4">
                ⚠️ No existe un turno de caja abierto.
                <br>
                <small>Los movimientos mostrados son solo de consulta.</small>
            </div>
        @endif

        {{-- FILTROS --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Fecha</label>
                    <input type="date"
                           class="form-control"
                           wire:model.live="fechaConsulta">
                </div>

                <div class="col-md-5">
                    <label class="form-label">Responsable</label>
                    <select class="form-control"
                            wire:model.live="idUsuario">
                        <option value="">— Todos —</option>
                        @foreach ($usuarios as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-grid">
                    <button wire:click="cargarMovimientos"
                            class="btn btn-primary">
                        🔍 Consultar
                    </button>
                </div>

            </div>
        </div>

        {{-- RESUMEN --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Ingresos</small>
                        <div class="fs-4 fw-semibold text-success">
                            S/ {{ number_format($totalIngresos, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Egresos</small>
                        <div class="fs-4 fw-semibold text-danger">
                            S/ {{ number_format($totalEgresos, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm border-primary">
                    <div class="card-body">
                        <small class="text-muted">Saldo</small>
                        <div class="fs-3 fw-bold text-primary">
                            S/ {{ number_format($saldo, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="card shadow-sm">
            <div class="card-header text-center bg-white">
                <div class="fw-semibold">
                    Movimientos de Caja
                </div>
                <small class="text-muted">
                    Fecha: {{ \Carbon\Carbon::parse($fechaConsulta)->format('d/m/Y') }}
                </small>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted text-center">
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th class="text-start">Descripción</th>
                            <th>Responsable</th>
                            <th class="text-end">Monto (S/)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($movimientos as $mov)
                            <tr>
                                <td class="text-center">
                                    {{ $mov->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="text-center">
                                    <span class="badge
                                        {{ $mov->tipo === 'INGRESO'
                                            ? 'bg-success-subtle text-success border'
                                            : 'bg-danger-subtle text-danger border' }}">
                                        {{ $mov->tipo }}
                                    </span>
                                </td>

                                <td>{{ $mov->descripcion }}</td>

                                <td class="text-center">
                                    {{ $mov->usuario->name ?? $mov->responsable ?? '—' }}
                                </td>

                                <td class="text-end fw-semibold">
                                    <span class="{{ $mov->tipo === 'INGRESO' ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($mov->monto, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No existen movimientos para los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
