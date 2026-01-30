<div class="container d-flex justify-content-center">
    <div class="col-xl-8 col-lg-9 col-md-10">

        {{-- ENCABEZADO INSTITUCIONAL --}}
        <div class="text-center mb-4">
            <h5 class="fw-semibold text-dark mb-1">
                Caja chica
            </h5>
            <div class="text-muted small">
                Registro y control de egresos del turno activo
            </div>
        </div>

        {{-- REGISTRO DE EGRESO --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body px-4 py-4">

                <div class="mb-3">
                    <h6 class="fw-semibold text-dark mb-0">
                        Registro de egreso
                    </h6>
                    <small class="text-muted">
                        Los egresos se descuentan automáticamente del turno de caja
                    </small>
                </div>

                <form wire:submit.prevent="guardar" class="row g-4">

                    <div class="col-12">
                        <label class="form-label text-dark fw-semibold">
                            Concepto
                        </label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                            wire:model.defer="descripcion" placeholder="Detalle del gasto realizado">
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-dark fw-semibold">
                            Monto (S/)
                        </label>
                        <input type="number" step="0.01" class="form-control @error('monto') is-invalid @enderror"
                            wire:model.defer="monto">
                        @error('monto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label text-dark fw-semibold">
                            Responsable
                            <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="text" class="form-control" wire:model.defer="responsable">
                    </div>

                    <div class="col-12 text-end pt-2">
                        <button type="submit" class="btn btn-dark px-4" wire:loading.attr="disabled">
                            Registrar egreso
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- HISTORIAL --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body px-0 py-0">

                <div class="px-4 py-3 border-bottom">
                    <h6 class="fw-semibold text-dark mb-0">
                        Historial de egresos del turno
                    </h6>
                </div>
                <div class="container pt-2 table-responsive">

                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-4">Fecha</th>
                                <th>Concepto</th>
                                <th class="text-end">Monto (S/)</th>
                                <th>Responsable</th>
                                <th class="text-center">Estado</th>
                                @can('editar-caja')
                                    <th class="text-center pe-4">Acción</th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($registros as $item)
                                <tr>
                                    <td class="ps-4 text-muted">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        {{ $item->descripcion }}
                                    </td>

                                    <td class="text-end fw-semibold">
                                        {{ number_format($item->monto, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->responsable ?? '—' }}
                                    </td>

                                    <td class="text-center">
                                        @if ($item->estado === 'REGISTRADO')
                                            <span class="badge bg-light text-dark border">
                                                Registrado
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border">
                                                Anulado
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center pe-4">
                                        @can('editar-caja')
                                            @if ($item->estado === 'REGISTRADO')
                                                <button wire:click="anular({{ $item->id_caja_chica }})"
                                                    class="badge btn-sm btn-outline-info"
                                                    onclick="confirm('¿Confirma la anulación del egreso?') || event.stopImmediatePropagation()">
                                                    Anular
                                                </button>
                                            @else
                                                —
                                            @endif
                                        @endcan

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No se registran egresos en este turno.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="border-top bg-light px-4 py-3 d-flex justify-content-between">
                        <span class="fw-semibold text-dark">
                            Total de egresos por caja chica
                        </span>
                        <span class="fw-bold fs-5">
                            S/ {{ number_format($this->totalCajaChica, 2) }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
