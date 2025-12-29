<div>
    <!-- ===== CABECERA ===== -->
    @if (!$listado)
        <div class="mb-3">
            <h5 class="text-clinico mb-0">Compras</h5>
            <small class="text-muted">Registro de compras a proveedores</small>
        </div>
        <div>
            <!-- ===== DATOS DE COMPRA ===== -->
            <div class="card card-clinica shadow-sm mb-3">
                <div class="card-header bg-white">
                    <strong class="text-clinico">Datos de la Compra</strong>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Proveedor</label>
                            <select class="form-control form-control-sm" wire:model="id_proveedor">
                                <option value="">Seleccione proveedor</option>
                                @foreach ($proveedores as $prov)
                                    <option value="{{ $prov->id_proveedor }}">
                                        {{ $prov->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_proveedor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="form-label">Tipo Doc.</label>
                            <select class="form-control form-control-sm" wire:model="tipo_documento">
                                <option value="">Seleccione documento</option>
                                <option value="FACTURA">Factura</option>
                                <option value="BOLETA">Boleta</option>
                            </select>
                            @error('tipo_documento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-2">
                            <label class="form-label">N° Documento</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="nro_documento">
                            @error('nro_documento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-2">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control form-control-sm" wire:model.defer="fecha_compra">
                            @error('fecha_compra')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- ===== DETALLE DE COMPRA ===== -->
            <div class="card card-clinica shadow-sm mb-3">
                <div class="card-header bg-white">
                    <strong class="text-clinico">Detalle de Medicamentos</strong>
                </div>

                <div class="card-body p-0">
                    @if ($mostrarFormulario)
                        <div class="card shadow-sm border-0 mb-4"
                            style="background:#f6f9fc; border-left:5px solid #0d6efd">

                            <div class="card-body">

                                <!-- CABECERA -->
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2">
                                        <i class="fa fa-search fa-lg text-primary"></i>
                                    </div>
                                    <div class="ml-2">
                                        <h6 class="mb-0 fw-bold text-primary">
                                            Búsqueda de Medicamentos
                                        </h6>
                                        <small class="text-muted">
                                            Escriba el nombre o principio activo
                                        </small>
                                    </div>
                                </div>

                                <!-- INPUT -->
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-pills text-primary"></i>
                                    </span>

                                    <input type="text" wire:model.live.debounce.200ms="buscarMedicamento"
                                        class="form-control border-start-0"
                                        placeholder="Ej: Paracetamol, Amoxicilina...">

                                    <span class="input-group-text bg-white">
                                        <i wire:target="buscarMedicamento"
                                            wire:loading.class="fa fa-spinner fa-spin text-primary"></i>
                                    </span>
                                </div>
                                <!-- RESULTADOS -->
                                @if ($resultados && count($resultados))
                                    <div class="list-group list-group-flush mt-2"
                                        style="max-height:220px; overflow-y:auto">

                                        @foreach ($resultados as $med)
                                            <button type="button"
                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                                wire:click="seleccionarMedicamento({{ $med->id_medicamento }})">

                                                <span>
                                                    <i class="fa fa-capsules text-primary me-1"></i>
                                                    {{ $med->nombre }} | Presentacion : {{ $med->presentacion }} |
                                                    Concentración : {{ $med->concentracion }} | Laboratorio
                                                    {{ $med->marca }}
                                                </span>

                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    Seleccionar
                                                </span>
                                            </button>
                                        @endforeach

                                    </div>
                                @endif
                                <br>
                                @if ($mostrarFormulario)
                                    <button class="btn btn-sm btn-outline-clinico"
                                        wire:click="$toggle('mostrarFormulario')">
                                        Cerrar
                                    </button>
                                @endif

                            </div>
                        </div>


                    @endif

                    <div class="table-responsive">
                        <table class="table table-clinica mb-0">
                            <thead>
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Presentacion</th>
                                    <th>Marca / Laboratorio</th>
                                    <th>Concentración</th>
                                    <th width="120">Cantidad</th>
                                    <th width="140">Precio Unitario Compra</th>
                                    <th width="140">Subtotal</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalle as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model="detalle.{{ $index }}.nombre" readonly>

                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model="detalle.{{ $index }}.presentacion" readonly>

                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model="detalle.{{ $index }}.marca" readonly>

                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model="detalle.{{ $index }}.concentracion" readonly>

                                        </td>
                                        <td>
                                            <input type="number" min="1" minlength="0"
                                                class="form-control form-control-sm"
                                                wire:model.live="detalle.{{ $index }}.cantidad">
                                        </td>

                                        <td>
                                            <input type="number" min="0" step="0.01"
                                                class="form-control form-control-sm"
                                                wire:model.live="detalle.{{ $index }}.precio">
                                        </td>

                                        <td class="fw-semibold">
                                            S/ {{ number_format($item['subtotal'], 2) }}
                                        </td>
                                        <td class="text-center">

                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="removeItem({{ $index }})"
                                                onclick="return confirm('¿Quitar medicamento del detalle?')"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="5">
                                        @if (!$mostrarFormulario)
                                            <button class="btn btn-sm btn-outline-clinico"
                                                wire:click="$toggle('mostrarFormulario')">
                                                + Agregar Medicamento
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        @error('detalle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- ===== TOTAL ===== -->
            <div class="d-flex justify-content-end mb-3">
                <h5 class="text-clinico">
                    Total: S/ {{ number_format($total, 2) }}
                </h5>
            </div>

            <!-- ===== BOTÓN ===== -->
            <div class="text-end">
                <button class="btn btn-clinico" wire:click='guardarCompra'>
                    <i class="fa fa-plus-circle"></i> Registrar Compra
                </button>

                @if (!$listado)
                    <button class="btn btn-clinico bg-info" wire:click="$toggle('listado')">
                        <i class="fa fa-eye"></i> Listado de Compras
                    </button>
                @endif
            </div>

        </div>
    @else
        <style>
            .text-clinico {
                color: #0b3c5d;
                font-weight: 600;
            }

            .badge-activa {
                background-color: #1e88e5;
            }

            .badge-anulada {
                background-color: #c62828;
            }

            .detalle-compra {
                background: #f8fbff;
                border-left: 4px solid #1e88e5;
            }

            .tabla-detalle th {
                font-size: 0.75rem;
                text-transform: uppercase;
                color: #607d8b;
            }

            .tabla-detalle td {
                font-size: 0.85rem;
            }

            .btn-icon {
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
        </style>

        @if ($listado)
            <button class="btn btn-sm btn-outline-clinico" wire:click="$toggle('listado')">
                + Nueva Compra
            </button>
        @endif
        <br>
        <br>
        <div class="row container">
            <div class="col-11">
                <strong class="text-clinico">Ingresar Búsqueda <br></strong>
                <input type="text" wire:model.live.debounce.200ms='search' class="form-control form-control-sm"
                    placeholder="Buscador" />
            </div>
            <div class=" col-1 input-group-append">
                <i wire:target="search" wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
            </div>
        </div>
        <br>
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr class="text-clinico">
                    <th>#</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($compras as $compra)
                    <!-- FILA PRINCIPAL -->
                    <tr>
                        <td class="fw-bold">{{ $compra->id_compra }}</td>

                        <td>
                            <div class="fw-semibold">{{ $compra->proveedor->razon_social ?? '-' }}</div>
                        </td>

                        <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>

                        <td class="text-end fw-bold">
                            S/ {{ number_format($compra->total, 2) }}
                        </td>

                        <td class="text-center">
                            <span class="badge {{ $compra->estado == 'ACTIVA' ? 'badge-activa' : 'badge-anulada' }}">
                                {{ $compra->estado }}
                            </span>
                        </td>

                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon"
                                wire:click="toggleDetalle({{ $compra->id_compra }})">
                                <i class="fa fa-eye"></i>
                                {{ $verDetalle[$compra->id_compra] ?? false ? 'Ocultar' : 'Detalle' }}
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-secondary btn-icon ms-1"
                                wire:click="printCompra({{ $compra->id_compra }})">
                                <i class="fa fa-print"></i>
                                PDF
                            </button>
                            @if ($compra->estado === 'ACTIVA')
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="abrirModalAnular({{ $compra->id_compra }})">
                                    Anular
                                </button>
                            @endif
                        </td>
                    </tr>

                    <!-- DETALLE -->
                    @if ($verDetalle[$compra->id_compra] ?? false)
                        <tr class="detalle-compra">
                            <td colspan="6">
                                <table class="table table-sm mb-0 tabla-detalle">
                                    <thead>
                                        <tr>
                                            <th>Medicamento</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($compra->detalles as $det)
                                            <tr>
                                                <td>{{ $det->medicamento->nombre }}</td>
                                                <td class="text-center">{{ $det->cantidad }}</td>
                                                <td class="text-end">
                                                    S/ {{ number_format($det->precio, 2) }}
                                                </td>
                                                <td class="text-end fw-bold">
                                                    S/ {{ number_format($det->subtotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div wire:ignore.self class="card-footer text-right">
            {{ $compras->links(data: ['scrollTo' => false]) }}
        </div>

    @endif
    @if ($mostrarModalAnular)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5)">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">⚠️ Anular Compra #{{ $compraAnular->id_compra }}</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('mostrarModalAnular', false)"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <div class="alert alert-warning small">
                            Esta acción revertirá el stock de los siguientes medicamentos.
                        </div>

                        <!-- TABLA DETALLE -->
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicamento</th>
                                    <th class="text-center">Cantidad a revertir</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalleAnular as $det)
                                    <tr>
                                        <td>{{ $det->medicamento->nombre }}</td>
                                        <td class="text-center fw-bold text-danger">
                                            - {{ $det->cantidad }}
                                        </td>
                                        <td>{{ $det->precio }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- MOTIVO -->
                        <div class="mt-3">
                            <label class="form-label fw-bold">Motivo de anulación</label>
                            <textarea class="form-control" rows="3" wire:model.defer="motivoAnulacion"
                                placeholder="Ingrese el motivo obligatorio"></textarea>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('mostrarModalAnular', false)">
                            Cancelar
                        </button>

                        <button class="btn btn-danger" wire:click="confirmarAnulacion">
                            Confirmar Anulación
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


</div>
