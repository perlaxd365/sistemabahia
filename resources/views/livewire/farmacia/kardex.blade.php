<div>
    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <div class="row g-2 mb-3">

                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Buscar medicamento"
                        wire:model.live.debounce.300ms="buscarMedicamento">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" wire:model.live="fechaInicio">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" wire:model.live="fechaFin">
                </div>

                <div class="col-md-2">
                    <select class="form-control form-control-sm" wire:model.live="tipoMovimiento">
                        <option value="">Todos</option>
                        <option value="ENTRADA">Entradas</option>
                        <option value="SALIDA">Salidas</option>
                    </select>
                </div>

                <div class="col-md-2">
                        <button class=" btn-outline-primary btn-sm form-control-sm" wire:click="imprimir"
                            wire:loading.attr="disabled">
                            <i class="fa fa-print me-1"></i> Imprimir Kardex
                        </button>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        @if ($fechaInicio || $fechaFin)
            <small class="text-muted">
                Mostrando del {{ $fechaInicio ?? 'inicio' }} al {{ $fechaFin ?? 'hoy' }}
            </small>
        @endif
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Medicamento</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-center">Stock Ant.</th>
                        <th class="text-center">Stock Nuevo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                        <tr>
                            <td class="small text-muted">
                                {{ $mov->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <strong>{{ $mov->medicamento->nombre }}</strong>
                                <div class="small text-muted">
                                    {{ $mov->medicamento->concentracion }}
                                    · {{ $mov->medicamento->presentacion }}
                                    · {{ $mov->medicamento->marca }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-{{ $mov->tipo_movimiento === 'ENTRADA' ? 'success' : 'danger' }}">
                                    {{ $mov->tipo_movimiento }}
                                </span>
                            </td>

                            <td class="small">{{ $mov->descripcion }}</td>

                            <td class="text-center fw-semibold">
                                {{ $mov->cantidad }}
                            </td>

                            <td class="text-center text-muted">
                                {{ $mov->stock_anterior }}
                            </td>

                            <td class="text-center fw-bold text-primary">
                                {{ $mov->stock_actual }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No se encontraron movimientos
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $movimientos->links() }}
        </div>
    </div>
</div>
