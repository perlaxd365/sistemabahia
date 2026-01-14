<div>
    <div class="card shadow-sm border-0 mt-4">
        {{-- HEADER --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    🧾 Facturación de la Atención
                </h5>
                <small class="text-muted">
                    Atención #{{ $atencion->id_atencion }}
                </small>
            </div>

            @if ($comprobante)
                @php
                    $color = match ($comprobante->estado) {
                        'BORRADOR' => 'warning',
                        'EMITIDO' => 'success',
                        'RECHAZADO' => 'danger',
                        default => 'secondary',
                    };
                @endphp

                <span class="badge bg-{{ $color }} fs-6">
                    {{ $comprobante->estado }}
                </span>
            @endif
        </div>

        {{-- BODY --}}
        <div class="card-body">
            {{-- SIN COMPROBANTE --}}
            @if (!$comprobante)
                <div class="text-center py-4">
                    <p class="text-muted mb-3">
                        Esta atención aún no tiene comprobante generado.
                    </p>

                    <button wire:click="crearBorrador" type="button" class="btn btn-primary px-4">
                        ➕ Crear comprobante
                    </button>
                </div>
            @else
                {{-- DATOS GENERALES --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Paciente:</strong><br>
                        {{ $atencion->paciente->name }}
                    </div>
                    <div class="col-md-4">
                        <strong>Documento:</strong><br>
                        {{ $comprobante->tipo_comprobante }} {{ $comprobante->serie }}-{{ $comprobante->numero ?? '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Fecha:</strong><br>
                        {{ DateUtil::getFechaSimple($comprobante->fecha_emision) }}
                    </div>

                </div>
                <div class="row g-3">

                    {{-- Tipo de comprobante --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de comprobante</label>
                        <select class="form-control" wire:model.live="tipo_comprobante"
                            wire:change="actualizarTipoComprobante">
                            <option value="TICKET">🟡 Ticket de venta</option>
                            <option value="BOLETA">🟢 Boleta</option>
                            <option value="FACTURA">🔵 Factura</option>
                        </select>
                    </div>

                    {{-- IGV --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Aplicar IGV</label>
                        <select class="form-control" wire:model.live="con_igv"
                            @if ($tipo_comprobante === 'FACTURA') disabled @endif>
                            <option value="1">Con IGV (18%)</option>
                            <option value="0">Sin IGV</option>
                        </select>

                        @if ($tipo_comprobante === 'FACTURA')
                            <small class="text-muted">
                                La factura siempre incluye IGV
                            </small>
                        @endif
                    </div>
                </div>
                @if ($tipo_comprobante === 'FACTURA')
                    <div class="card mt-3 border-primary">
                        <div class="card-header fw-semibold text-primary">
                            🏢 Datos del cliente (Factura)
                        </div>

                        <div class="card-body row g-2">

                            <div class="row g-2">

                                <div class="col-md-4">
                                    <label>RUC</label>
                                    <div class="input-group">
                                        <input type="text"  class="form-control" wire:model.live="cliente_ruc"
                                            maxlength="11">

                                        <button class="btn btn-outline-primary" type="button" wire:click="buscarRuc"
                                            @if (strlen($cliente_ruc) !== 11) disabled @endif> 🔍</button>
        
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <label>Razón Social</label>
                                    <input type="text" disabled class="form-control" wire:model="cliente_razon">
                                </div>

                                <div class="col-md-12">
                                    <label>Dirección</label>
                                    <input type="text" disabled class="form-control" wire:model="cliente_direccion">
                                </div>

                            </div>

                        </div>
                    </div>
                @endif
                {{-- TABLA ITEMS --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">P. Unit</th>
                                <th class="text-end">IGV</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comprobante->detalles as $d)
                                <tr>
                                    <td>{{ $d->descripcion }}</td>
                                    <td class="text-center">{{ $d->cantidad }}</td>
                                    <td class="text-end">S/ {{ number_format($d->precio_unitario, 2) }}</td>
                                    <td class="text-end">S/ {{ number_format($d->igv, 2) }}</td>
                                    <td class="text-end fw-semibold">
                                        S/ {{ number_format($d->subtotal + $d->igv, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- RESUMEN --}}
                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <span>S/ {{ number_format($comprobante->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>IGV (18%)</span>
                                    <span>S/ {{ number_format($comprobante->igv, 2) }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fs-5 fw-bold">
                                    <span>Total</span>
                                    <span>S/ {{ number_format($comprobante->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    @if ($comprobante->estado === 'BORRADOR')
                        <button wire:click="emitir" type="button" class="btn btn-success px-4">
                            🚀 Emitir comprobante
                        </button>
                    @endif

                    @if ($comprobante->estado === 'EMITIDO')
                        <a href="{{ $comprobante->pdf_url }}" target="_blank" class="btn btn-outline-primary">
                            📄 Ver PDF
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
