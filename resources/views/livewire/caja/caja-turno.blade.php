<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">

            <div class="card border-0 shadow-sm">

                {{-- HEADER --}}
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center gap-3">
                            <div class="border rounded p-2 text-muted">
                                <i class="fas fa-money-bill"></i>
                            </div>

                            <div class="ml-2">
                                <h6 class="mb-0 fw-semibold text-dark">
                                    Control de turno
                                    @isset($cajaTurno)
                                        Monto inicial <b>S/ {{ number_format($cajaTurno->monto_apertura, 2) }}</b>
                                    @endisset
                                </h6>
                                <small class="text-muted">
                                    Gestión de ingresos y egresos
                                </small>
                            </div>
                        </div>

                        @if ($cajaTurno)
                            <span class="badge bg-success-subtle text-success border border-success">
                                Turno abierto
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border">
                                Sin turno activo
                            </span>
                        @endif
                    </div>
                </div>

                {{-- BODY --}}
                <div class="card-body">

                    {{-- SIN TURNO --}}
                    @if (!$cajaTurno)

                        <div class="alert alert-light border mb-4">
                            <strong>No existe un turno activo.</strong>
                            <div class="text-muted small">
                                Es necesario abrir un turno para registrar movimientos.
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Monto de apertura (S/)
                                </label>
                                <input type="number" step="0.01" class="form-control"
                                    wire:model.defer="monto_apertura" placeholder="0.00">
                                @error('monto_apertura')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <button wire:click="abrirCaja" class="btn btn-primary px-5">
                                    Abrir turno
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- DATOS DEL TURNO --}}
                        <div class="border rounded p-3 mb-4 bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted small">Apertura</div>
                                    <div class="fw-semibold">
                                        {{ $cajaTurno->fecha_apertura->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted small">Monto apertura</div>
                                    <span class="fw-semibold">S/
                                        {{ number_format($cajaTurno->monto_apertura, 2) }}</span>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <div class="text-muted small">Responsable</div>
                                    <div class="fw-semibold">
                                        {{ $cajaTurno->usuarioApertura->name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- INGRESOS --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                Ingresos por método de pago
                            </h6>

                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach ($totales as $tipo => $monto)
                                        <tr>
                                            <td class="text-muted">
                                                {{ $tipo }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                S/ {{ number_format($monto, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- TOTAL INGRESOS --}}
                        <div class="border-top pt-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">
                                    Total ingresos
                                </span>
                                <span class="fw-bold text-primary">
                                    S/ {{ number_format($cajaTurno->totalIngresos(), 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- EGRESOS --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-danger">
                                    Total egresos (Caja chica)
                                </span>
                                <span class="fw-bold text-danger">
                                    S/ {{ number_format($egresos, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- SALDO FINAL --}}
                        <div class="border-top pt-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">
                                    Saldo actual en caja
                                </span>
                                <span class="fw-bold fs-5 text-success">
                                    S/ {{ number_format($saldo, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- ACCIONES --}}
                        <div class="text-end">
                            <button wire:click="cerrarCaja" class="btn btn-outline-danger px-4">
                                Cerrar turno
                            </button>
                        </div>

                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
