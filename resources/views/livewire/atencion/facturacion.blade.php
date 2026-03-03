<div>
    @php
        $bloqueado = $comprobante && $comprobante->estado === 'EMITIDO';
    @endphp
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
                    @if ($comprobante->estado === 'BORRADOR')
                        {{-- Tipo de comprobante --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de comprobante</label>
                            <select class="form-control" wire:model.live="tipo_comprobante"
                                @if ($bloqueado) disabled @endif
                                wire:change="actualizarTipoComprobante">
                                <option value="TICKET">🟡 Ticket de venta</option>
                                <option value="BOLETA">🟢 Boleta</option>
                                <option value="FACTURA">🔵 Factura</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Método de pago
                            </label>
                            <select class="form-control" wire:model.live="tipo_pago">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="YAPE">Yape</option>
                                <option value="PLIN">Plin</option>
                                <option value="TARJETA">Tarjeta</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                            </select>
                        </div>
                        {{-- IGV --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aplicar IGV</label>
                            <select class="form-control" wire:model.live="con_igv"
                                @if ($bloqueado) disabled @endif
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
                    @endif
                </div>
                @if ($tipo_comprobante === 'FACTURA' || $tipo_comprobante === 'BOLETA')
                    <div class="card mt-3 border-primary">
                        <div class="card-header fw-semibold text-primary">
                            🏢 Datos del cliente
                            {{ $tipo_comprobante === 'FACTURA' ? '(Factura)' : '(Boleta)' }}
                        </div>
                        <div class="card-body row g-2">
                            <div class="row g-2">

                                @if ($tipo_comprobante === 'FACTURA')
                                    <div class="col-md-4">
                                        <label>
                                            FACTURA
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                wire:model.live="numero_documento"
                                                maxlength="{{ $tipo_comprobante === 'FACTURA' ? 11 : 8 }}">
                                            <button type="button" class="btn btn-outline-primary"
                                                wire:click="{{ $tipo_comprobante === 'FACTURA' ? 'buscarRuc' : 'buscarDni' }}"
                                                @disabled(!$this->puedeBuscar)>
                                                🔍
                                            </button>
                                            @error('numero_documento')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label>Razón Social</label>
                                        <input type="text" disabled class="form-control" wire:model="cliente_razon">
                                        @error('cliente_razon')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label>Dirección</label>
                                        <input type="text" disabled class="form-control"
                                            wire:model="cliente_direccion">
                                        @error('cliente_direccion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @elseif($tipo_comprobante === 'BOLETA')
                                    <div class="col-md-6">
                                        <label>
                                            DNI
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control"
                                                wire:model.live="numero_documento" value=""
                                                placeholder="Ingrese DNI (8 dígitos)">
                                            <button class="btn btn-outline-primary" type="button"
                                                wire:click="buscarDni"
                                                @if (strlen($numero_documento) !== 8) disabled @endif> 🔍</button>
                                            @error('numero_documento')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombres</label>
                                        <input type="text" class="form-control" value=""
                                            wire:model="cliente_nombre">
                                        @error('cliente_nombre')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif


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
                                @if ($comprobante->recargo > 0)
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>Recargo tarj. ({{ $porcentaje_recargo_tarjeta }}%)</span>
                                        <span>S/ {{ number_format($comprobante->recargo, 2) }}</span>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>Recargo tarj. ({{ $porcentaje_recargo_tarjeta }}%)</span>
                                        <span>S/ {{ number_format($recargo, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fs-5 fw-bold">
                                    <span>Total</span>

                                    @if ($comprobante->total_cobrado > 0)
                                        <span>S/ {{ number_format($comprobante->total_cobrado, 2) }}</span>
                                    @else
                                        <span>S/ {{ number_format($comprobante->total, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    @if ($comprobante->estado === 'BORRADOR')
                        <button wire:click="emitir" wire:loading.attr="disabled" class="btn btn-success btn-sm"
                            type="button"> <i class="fa fa-plus-circle"></i> <i wire:target="emitir"
                                wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> 🚀 Emitir
                            comprobante
                            Usuario</button>

                        {{-- NUEVO BOTÓN ELIMINAR --}}
                        <button wire:click="eliminarComprobante" wire:loading.attr="disabled"
                            onclick="confirm('¿Seguro que deseas eliminar este comprobante? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()"
                            class="btn btn-danger btn-sm" type="button">
                            <i class="fa fa-trash"></i>
                            <i wire:target="eliminarComprobante" wire:loading.class="fa fa-spinner fa-spin"></i>
                            🗑 Eliminar
                        </button>
                    @endif

                    @if (
                        ($comprobante->estado === 'EMITIDO' || $comprobante->estado === 'PENDIENTE') &&
                            ($comprobante->tipo_comprobante === 'BOLETA' || $comprobante->tipo_comprobante === 'FACTURA'))
                        <a href="{{ $comprobante->pdf_url }}" target="_blank" class="btn btn-outline-primary">
                            📄 Ver PDF
                        </a>
                    @endif

                    @if ($comprobante && $comprobante->tipo_comprobante === 'TICKET' && $comprobante->estado === 'EMITIDO')

                        @if ($comprobante->tipo_comprobante === 'TICKET')
                            <button wire:click="anularComprobanteInterno" wire:loading.attr="disabled"
                                class="btn btn-danger btn-sm" type="button"> <i class="fa fa-trash"></i> <i
                                    wire:target="anularComprobanteInterno" wire:loading.class="fa fa-spinner fa-spin"
                                    aria-hidden="true"></i> Anular comprobante</button>
                        @endif
                        <a href="{{ route('tickets.imprimir', $comprobante) }}" target="_blank"
                            class="btn btn-secondary">
                            🧾 Imprimir Ticket
                        </a>

                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
