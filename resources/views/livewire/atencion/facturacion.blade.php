<div>
    @php
        $bloqueado = $comprobanteActivo && $comprobanteActivo->estado === 'EMITIDO';
    @endphp

    <div class="card shadow-sm border-0 mt-4 rounded-4">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">
                        🧾 Facturación de la Atención
                    </h5>
                    <small class="text-muted">
                        Atención #{{ $atencion->id_atencion }}
                    </small>
                </div>

                @if ($comprobanteActivo)
                    @php
                        $color = match ($comprobanteActivo->estado) {
                            'BORRADOR' => 'warning',
                            'EMITIDO' => 'success',
                            'RECHAZADO' => 'danger',
                            default => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill">
                        {{ $comprobanteActivo->estado }}
                    </span>
                @endif
            </div>
        </div>

        {{-- SELECTOR COMPROBANTES --}}
        <div class="px-4 pb-3 border-bottom">
            @forelse ($comprobantes as $c)
                <button type="button" wire:click="seleccionarComprobante({{ $c->id_comprobante }})"
                    class="btn btn-sm me-2 mb-2 rounded-pill
                        {{ $comprobanteActivo && $comprobanteActivo->id_comprobante == $c->id_comprobante
                            ? 'btn-primary'
                            : 'btn-outline-secondary' }}">
                    {{ $c->serie }}-{{ $c->numero ?? 'BORRADOR' }}
                </button>
            @empty
                <small class="text-muted">No existen comprobantes.</small>
            @endforelse
        </div>

        <div class="card-body px-4">

            {{-- SIN COMPROBANTES --}}
            @if ($comprobantes->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted mb-3">
                        Esta atención aún no tiene comprobantes generados.
                    </p>

                    <button wire:click="crearBorrador" type="button" class="btn btn-primary px-4 rounded-pill">
                        ➕ Crear comprobante
                    </button>
                </div>
            @endif

            @if ($comprobanteActivo)

                {{-- DATOS GENERALES --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <small class="text-muted">Paciente</small>
                        <div class="fw-semibold">
                            {{ $atencion->paciente->name }} - <b>{{ $atencion->paciente->dni }}</b>
                        </div>
                    </div>
                    @if ($comprobanteActivo->cliente)
                        <div class="col-md-4">
                            <small class="text-muted">Cliente para Comprobante</small>
                            <div class="fw-semibold">

                                <b>{{ $comprobanteActivo->cliente->numero_documento }}</b> <br>
                                @if ($comprobanteActivo->cliente->razon_social)
                                    {{ $comprobanteActivo->cliente->razon_social }}
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <small class="text-muted">Documento</small>
                        <div class="fw-semibold">
                            <b>{{ $comprobanteActivo->tipo_comprobante }}</b>
                            {{ $comprobanteActivo->serie }} - <b>{{ $comprobanteActivo->numero ?? '—' }}</b>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <hr>
                        <small class="text-muted">Fecha</small>
                        <div class="fw-semibold">
                            <b> {{ DateUtil::getFechaSimple($comprobanteActivo->fecha_emision) }} -
                                {{ DateUtil::getHora($comprobanteActivo->fecha_emision) }}</b>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <hr>
                        <small class="text-muted">Medio de Pago</small>
                        <div class="fw-semibold">
                            <b> {{ $comprobanteActivo->metodo_pago }}</b>
                        </div>
                    </div>
                </div>

                {{-- CONFIGURACIÓN BORRADOR --}}
                @if ($comprobanteActivo->estado === 'BORRADOR')

                    <div class="card border-0 bg-light p-3 rounded-4 mb-4">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tipo</label>
                                <select class="form-control rounded-3" wire:model.live="tipo_comprobante"
                                    wire:change="actualizarTipoComprobante">
                                    <option value="TICKET">🟡 Ticket</option>
                                    <option value="BOLETA">🟢 Boleta</option>
                                    <option value="FACTURA">🔵 Factura</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Método de pago</label>
                                <select class="form-control rounded-3" wire:model.live="tipo_pago">
                                    <option value="">Elegir medio de pago</option>
                                    <option value="EFECTIVO">Efectivo</option>
                                    <option value="YAPE">Yape</option>
                                    <option value="PLIN">Plin</option>
                                    <option value="TARJETA">Tarjeta</option>
                                    <option value="TRANSFERENCIA">Transferencia</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">IGV</label>
                                <select class="form-control rounded-3" wire:model.live="con_igv"
                                    @if ($tipo_comprobante === 'FACTURA') disabled @endif>
                                    <option value="1">Con IGV (18%)</option>
                                    <option value="0">Sin IGV</option>
                                </select>
                            </div>

                        </div>

                        {{-- DATOS CLIENTE --}}
                        @if ($tipo_comprobante === 'BOLETA')
                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">DNI</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-3"
                                            wire:model.live="numero_documento" maxlength="8" placeholder="Ingrese DNI">

                                        <button type="button" wire:click="buscarDni"
                                            class="btn btn-outline-primary rounded-end-3" @disabled(!$this->puedeBuscar)>
                                            🔎
                                        </button>
                                    </div>

                                    @error('numero_documento')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Nombre del cliente</label>
                                    <input type="text" class="form-control rounded-3"
                                        wire:model.defer="cliente_nombre">
                                </div>
                            </div>
                        @endif

                        @if ($tipo_comprobante === 'FACTURA')
                            <div class="row g-3 mt-3">

                                <div class="col-md-4">
                                    <label class="form-label">RUC</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-3"
                                            wire:model.live="numero_documento" maxlength="11" placeholder="Ingrese RUC">

                                        <button type="button" wire:click="buscarRuc"
                                            class="btn btn-outline-primary rounded-end-3" @disabled(!$this->puedeBuscar)>
                                            🔎
                                        </button>
                                    </div>

                                    @error('numero_documento')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Razón Social</label>
                                    <input type="text" class="form-control rounded-3"
                                        wire:model.defer="cliente_razon">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control rounded-3"
                                        wire:model.defer="cliente_direccion">
                                </div>

                            </div>
                        @endif
                    </div>
                @endif

                {{-- TABLA ITEMS --}}
                <div class="table-responsive">
                    <table class="table align-middle">
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
                            @foreach ($comprobanteActivo->detalles as $d)
                                <tr>
                                    <td>{{ $d->descripcion }}</td>
                                    <td class="text-center">{{ $d->cantidad }}</td>
                                    <td class="text-end">
                                        S/ {{ number_format($d->precio_unitario, 2) }}
                                    </td>
                                    <td class="text-end">
                                        S/ {{ number_format($d->igv, 2) }}
                                    </td>
                                    <td class="text-end fw-semibold">
                                        S/ {{ number_format($d->subtotal + $d->igv, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- RESUMEN FINANCIERO --}}
                <div class="row justify-content-end mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span>S/ {{ number_format($comprobanteActivo->subtotal, 2) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">IGV</span>
                                    <span>S/ {{ number_format($comprobanteActivo->igv, 2) }}</span>
                                </div>

                                <div class="d-flex justify-content-between text-danger mb-2">
                                    <span>Recargo tarjeta</span>
                                    <span>S/ {{ number_format($comprobanteActivo->recargo ?? 0, 2) }}</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between fs-5 fw-bold">
                                    <span>Total</span>
                                    <span>
                                        S/
                                        {{ number_format(
                                            $comprobanteActivo->total_cobrado > 0 ? $comprobanteActivo->total_cobrado : $comprobanteActivo->total,
                                            2,
                                        ) }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    @if ($comprobanteActivo->estado === 'BORRADOR')
                        <button wire:click="emitir" wire:loading.attr="disabled" wire:target="emitir" type="button"
                            class="btn btn-success rounded-pill px-4">

                            <span wire:loading.remove wire:target="emitir">
                                🚀 Emitir
                            </span>

                            <span wire:loading wire:target="emitir">
                                ⏳ Emitiendo...
                            </span>

                        </button>
                        <button wire:click="eliminarComprobante" type="button"
                            onclick="confirm('¿Eliminar este comprobante?') || event.stopImmediatePropagation()"
                            class="btn btn-outline-danger rounded-pill px-4">
                            🗑 Eliminar
                        </button>
                    @endif

                </div>

            @endif
            @if (
                $comprobanteActivo &&
                    in_array($comprobanteActivo->estado, ['EMITIDO', 'PENDIENTE']) &&
                    in_array($comprobanteActivo->tipo_comprobante, ['BOLETA', 'FACTURA']))
                <button wire:click="anularComprobanteInterno" wire:loading.attr="disabled"
                    class="btn btn-danger btn-sm" type="button">
                    🗑 Anular comprobante
                </button>
                <a href="{{ $comprobanteActivo->pdf_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    📄 Ver PDF
                </a>
            @endif


            @if ($comprobanteActivo && $comprobanteActivo->tipo_comprobante === 'TICKET' && $comprobanteActivo->estado === 'EMITIDO')
                <button wire:click="anularComprobanteInterno" wire:loading.attr="disabled"
                    class="btn btn-danger btn-sm" type="button">
                    🗑 Anular comprobante
                </button>

                <a href="{{ route('tickets.imprimir', $comprobanteActivo) }}" target="_blank"
                    class="btn btn-secondary btn-sm">
                    🧾 Imprimir Ticket
                </a>
            @endif

            @if ($comprobantes->isNotEmpty())
                <div class="text-end mt-3">
                    <button wire:click="crearComprobanteAdicional" type="button"
                        class="btn btn-outline-primary btn-sm">
                        ➕ Agregar Nuevo comprobante
                    </button>
                </div>
            @endif
        </div>


    </div>
</div>
