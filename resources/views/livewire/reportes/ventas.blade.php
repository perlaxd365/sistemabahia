<div class="container-fluid">

    <style>
        .card-clinic {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .05);
        }

        .header-clinic {
            font-weight: 600;
            color: #2c3e50;
        }

        .kpi-card {
            border-radius: 10px;
            padding: 15px;
            background: #f8fbff;
            border-left: 4px solid #3a8dde;
        }

        .kpi-title {
            font-size: 13px;
            color: #6c757d;
        }

        .kpi-value {
            font-size: 20px;
            font-weight: 600;
        }

        .table-clinic thead {
            background: #f1f4f7;
            font-size: 13px;
        }

        .table-clinic td {
            vertical-align: middle;
        }

        .badge-comprobante {
            background: #eef4ff;
            color: #3a8dde;
            font-weight: 600;
        }
    </style>


    <div class="card card-clinic">

        <div class="card-header bg-white border-0">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="header-clinic mb-0">
                        📊 Reporte de Ventas
                    </h5>

                    <small class="text-muted">
                        Control financiero de comprobantes emitidos
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body">

            {{-- FILTROS --}}

            <div class="row mb-4">

                <div class="col-md-3">

                    <label class="form-label text-muted small">
                        Fecha inicio
                    </label>

                    <input type="date" class="form-control" wire:model.live="fecha_inicio">

                </div>

                <div class="col-md-3">

                    <label class="form-label text-muted small">
                        Fecha fin
                    </label>

                    <input type="date" class="form-control" wire:model.live="fecha_fin">

                </div>
                <div class="col-md-3">

                    <label class="form-label small text-muted">
                        Tipo comprobante
                    </label>

                    <select class="form-control" wire:model.live="tipo">

                        <option value="TODOS">
                            Todos
                        </option>

                        <option value="TICKET">
                            Ticket
                        </option>

                        <option value="BOLETA">
                            Boleta
                        </option>

                        <option value="FACTURA">
                            Factura
                        </option>

                        <option value="BOLETA_FACTURA">
                            Boleta + Factura
                        </option>

                    </select>

                </div>
                <div class="col-md-3">
                    <button wire:click="exportar" class="btn btn-success">
                        Exportar Excel
                    </button>
                </div>

            </div>


            {{-- KPIs --}}

            <div class="row mb-4">

                <div class="col-md-4">

                    <div class="kpi-card">

                        <div class="kpi-title">
                            Subtotal Ventas
                        </div>

                        <div class="kpi-value">
                            S/ {{ number_format($subtotal, 2) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="kpi-card">

                        <div class="kpi-title">
                            IGV
                        </div>

                        <div class="kpi-value">
                            S/ {{ number_format($igv, 2) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="kpi-card">

                        <div class="kpi-title">
                            Total Facturado
                        </div>

                        <div class="kpi-value text-primary">
                            S/ {{ number_format($total, 2) }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- TABLA --}}

            <div class="table-responsive">

                <table class="table table-hover table-sm table-clinic">

                    <thead>

                        <tr>

                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Paciente</th>
                            <th>Cliente</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">IGV</th>
                            <th class="text-end">Total</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ventas as $venta)
                            <tr>

                                <td>

                                    <span class="text-muted small">

                                        {{ \Carbon\Carbon::parse($venta->fecha_emision)->format('d/m/Y') }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge badge-comprobante">

                                        {{ $venta->serie }}-{{ $venta->numero }}

                                    </span>

                                </td>


                                <td>

                                    {{ $venta->paciente->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $venta->cliente->nombre ?? '-' }}

                                </td>


                                <td class="text-end">

                                    S/ {{ number_format($venta->subtotal, 2) }}

                                </td>


                                <td class="text-end">

                                    S/ {{ number_format($venta->igv, 2) }}

                                </td>


                                <td class="text-end fw-bold">

                                    S/ {{ number_format($venta->total, 2) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted py-4">

                                    No existen comprobantes en este periodo

                                </td>

                            </tr>
                        @endforelse

                    </tbody>


                    <tfoot class="table-light">

                        <tr>

                            <th colspan="4" class="text-end">

                                Totales

                            </th>

                            <th class="text-end">

                                S/ {{ number_format($subtotal, 2) }}

                            </th>

                            <th class="text-end">

                                S/ {{ number_format($igv, 2) }}

                            </th>

                            <th class="text-end">

                                S/ {{ number_format($total, 2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>
