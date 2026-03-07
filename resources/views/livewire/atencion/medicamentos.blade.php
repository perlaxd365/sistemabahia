<div class="container-fluid px-0">

    {{-- ================= TRATAMIENTO ================= --}}
    <div class="card border-0 shadow-sm mt-3">

        <div class="card-body py-3">

            <div class="d-flex align-items-start gap-3">

                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                    <i class="fa fa-notes-medical text-primary"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-semibold text-dark">
                        Tratamiento indicado para
                        <span class="text-primary">{{ $nombre_paciente }}</span>
                    </div>

                    <div class="text-muted mt-2" style="line-height:1.5;">
                        {!! $tratamiento ? nl2br(e($tratamiento)) : '<span class="text-muted">Sin tratamiento registrado</span>' !!}
                    </div>
                </div>

            </div>

        </div>
    </div>



    @can('editar-farmacia')

        {{-- ================= DISPENSACIÓN ================= --}}
        <div class="card border-0 shadow-sm mt-3">

            <div class="card-header bg-white py-2 border-bottom">
                <span class="fw-semibold text-primary">
                    <i class="fa fa-pills me-1"></i>
                    Dispensación
                </span>
            </div>

            <div class="card-body py-3">

                {{-- Buscador --}}
                <div class="mb-3">
                    <input type="text" wire:model.live.debounce.300ms="buscarMedicamento"
                        class="form-control form-control-sm rounded-3 shadow-none" placeholder="Buscar medicamento...">
                </div>

                @if ($resultados)
                    <ul class="list-group shadow-sm mb-3">
                        @foreach ($resultados as $med)
                            <li class="list-group-item list-group-item-action py-2 border-0"
                                wire:click="agregarMedicamento({{ $med->id_medicamento }})" style="cursor:pointer">

                                <div class="fw-semibold text-dark">
                                    {{ $med->nombre }}
                                </div>

                                <div class="text-muted d-flex justify-content-between">
                                    <span>
                                        {{ $med->concentracion }} · {{ $med->presentacion }}
                                    </span>
                                    <span>
                                        Stock: {{ $med->stock }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif


                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead class="table-light">
                            <tr class="text-muted">
                                <th>Medicamento</th>
                                <th width="90" class="text-center">Cant.</th>
                                <th width="110" class="text-end">Precio</th>
                                <th width="110" class="text-end">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($detalle as $i => $item)
                                <tr>

                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ $item['nombre'] }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $item['concentracion'] }} · {{ $item['presentacion'] }}
                                        </div>
                                    </td>

                                    {{-- CANTIDAD --}}
                                    <td>
                                        <input type="number" min="1"
                                            wire:model.lazy="detalle.{{ $i }}.cantidad"
                                            class="form-control form-control-sm text-center rounded-3 shadow-none border-light">
                                    </td>

                                    {{-- PRECIO --}}
                                    <td>
                                        <input type="number" step="0.01"
                                            wire:model.lazy="detalle.{{ $i }}.precio"
                                            class="form-control form-control-sm text-end rounded-3 shadow-none border-light">
                                    </td>

                                    {{-- SUBTOTAL --}}
                                    <td class="text-end fw-semibold text-success t-2">
                                        <div class="pt-2">
                                            S/ {{ number_format($item['subtotal'], 2) }}
                                        </div>
                                    </td>

                                    {{-- ELIMINAR --}}
                                    <td class="text-center">
                                        <button type="button" wire:click="removeItem({{ $i }})"
                                            class="btn btn-sm btn-light border rounded-circle p-1"
                                            style="width:28px;height:28px;">
                                            <i class="fa fa-minus text-muted" style="font-size:11px;"></i>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No hay medicamentos agregados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>


                {{-- TOTAL --}}
                @if (count($detalle))
                    <div class="d-flex justify-content-end align-items-center mt-2">

                        <div class="text-end">
                            <div class="text-muted small">
                                Total actual
                            </div>
                            <div class="fw-bold text-primary fs-5">
                                S/ {{ number_format(collect($detalle)->sum('subtotal'), 2) }}
                            </div>
                        </div>

                    </div>
                @endif


                {{-- BOTÓN --}}
                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm px-4 rounded-3" type="button" wire:click="guardarMedicamentos">
                        Dispensar medicamentos
                    </button>
                </div>

            </div>
        </div>

    @endcan



    {{-- ================= HISTORIAL ================= --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-semibold text-dark">
                    Medicamentos Dispensados
                </h6>
                <small class="text-muted">
                    Registro histórico de la atención
                </small>
            </div>

            <div class="t-3 mt-3 text-end">
                <div class="text-muted">
                    Total dispensado
                </div>
                <div class="fs-4 fw-bold text-success">
                    S/ {{ number_format($this->totalDispensado, 2) }}
                </div>
            </div>
            <button type="button" wire:click="toggleDispensados" class="btn btn-sm btn-light border rounded-3 px-3">
                {{ $mostrarDispensados ? 'Ocultar' : 'Ver detalle' }}
            </button>
        </div>


        @if ($mostrarDispensados)
            <div class="card-body">

                @if ($medicamentosDispensados->count())

                    @foreach ($medicamentosDispensados as $item)
                        <div class="border rounded-3 p-3 mb-3 bg-white">

                            <div class="row align-items-center">

                                <div class="col-md-7">

                                    <div class="d-flex align-items-center gap-2">

                                        <div class="fw-semibold text-dark">
                                            {{ optional($item->medicamento)->nombre ?? 'Medicamento eliminado' }}
                                        </div>

                                        @if ($item->facturado)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"
                                                style="font-size:11px;">
                                                Facturado
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"
                                                style="font-size:11px;">
                                                Pendiente
                                            </span>
                                        @endif

                                    </div>

                                    <div class="text-muted mt-1">
                                        {{ optional($item->medicamento)->concentracion }}
                                        · {{ optional($item->medicamento)->presentacion }}
                                        · {{ optional($item->medicamento)->marca }}
                                    </div>

                                    <div class="mt-2 text-muted">
                                        Cantidad: <strong>{{ $item->cantidad }}</strong>
                                        &nbsp; | &nbsp;
                                        Precio: <strong>S/ {{ number_format($item->precio, 2) }}</strong>
                                        &nbsp; | &nbsp;
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </div>

                                </div>

                                <div class="col-md-5 text-md-end mt-3 mt-md-0">

                                    <div class="fs-5 fw-bold text-primary">
                                        S/ {{ number_format($item->subtotal, 2) }}
                                    </div>

                                    <button type="button"
                                        wire:click="eliminarMedicamento({{ $item->id_atencion_medicamento }})"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 mt-2"
                                        style="font-size:13px;">
                                        <i class="fa fa-undo me-1"></i>
                                        Retornar
                                    </button>

                                </div>

                            </div>

                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        No se han dispensado medicamentos
                    </div>
                @endif

            </div>
        @endif

    </div>

</div>
