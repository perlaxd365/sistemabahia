<div>
   <div class="container-fluid d-flex justify-content-center">

    <div class="col-xxl-8 col-xl-9 col-lg-10">

        {{-- SIN TURNO --}}
        @if (!$cajaTurno)
            <div class="alert alert-warning text-center py-4">
                <div class="fw-semibold mb-1">Caja no disponible</div>
                <small class="text-muted">
                    No existe un turno de caja abierto.
                </small>
            </div>
            @return
        @endif

        {{-- TITULO --}}
        <div class="mb-4 text-center">
            <small class="text-muted">
                Movimientos financieros del turno activo
            </small>
        </div>

        {{-- RESUMEN --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-4">
                        <small class="text-muted">Ingresos</small>
                        <div class="fs-4 fw-semibold text-success mt-1">
                            S/ {{ number_format($totalIngresos, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-4">
                        <small class="text-muted">Egresos</small>
                        <div class="fs-4 fw-semibold text-danger mt-1">
                            S/ {{ number_format($totalEgresos, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-primary text-center">
                    <div class="card-body py-4">
                        <small class="text-muted">Saldo en Caja</small>
                        <div class="fs-3 fw-bold text-primary mt-1">
                            S/ {{ number_format($saldo, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom text-center">
                <div class="fw-semibold">
                    Detalle de Movimientos
                </div>
                <small class="text-muted">
                    Turno N.º {{ $cajaTurno->id_caja_turno }} ·
                    Apertura {{ $cajaTurno->fecha_apertura->format('d/m/Y H:i') }}
                </small>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover mb-0 align-middle">
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

                                <td>
                                    {{ $mov->descripcion }}
                                </td>

                                <td class="text-center">
                                    {{ $mov->responsable ?? '—' }}
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
                                    No existen movimientos registrados.
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
