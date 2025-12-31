<div>
    <div class="card border-0 shadow-sm mt-3">

        <!-- ================= CABECERA TRATAMIENTO ================= -->
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-clinico mr-2">
                    <i class="fa fa-notes-medical fa-lg"></i> 
                </div>

                <div>
                    <div class="fw-semibold text-clinico">
                        Tratamiento indicado por el médico para <b>{{$nombre_paciente}}</b>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        {!! nl2br(e($tratamiento)) ? nl2br(e($tratamiento)) : '- Sin consulta -' !!}
                    </div>
                </div>
            </div>
        </div>



        <div class="card-body pt-3">

            <!-- ================= BUSCADOR MEDICAMENTOS ================= -->
            <div class="card border-primary-subtle mb-3">
                <div class="card-header bg-primary-subtle py-2">
                    <strong class="text-primary">
                        <i class="fa fa-pills me-1"></i>
                        Dispensación de medicamentos
                    </strong>
                </div>

                <div class="card-body p-2">

                    <label class="small text-muted mb-1">
                        Buscar medicamento
                    </label>

                    <input type="text" wire:model.live.debounce.300ms="buscarMedicamento"
                        class="form-control form-control-sm" placeholder="Escriba el nombre del medicamento…">

                    @if ($resultados)
                        <ul class="list-group mt-2 shadow-sm">
                            @foreach ($resultados as $med)
                                <li class="list-group-item list-group-item-action py-2"
                                    wire:click="agregarMedicamento({{ $med->id_medicamento }})
                                    "
                                    style="cursor:pointer">

                                    <div class="fw-semibold text-primary">
                                        {{ $med->nombre }}
                                    </div>

                                    <div class="small text-muted d-flex justify-content-between">
                                        <span>
                                            {{ $med->concentracion }} · {{ $med->presentacion }}
                                        </span>
                                        <span class="fst-italic">
                                            {{ $med->marca }}
                                        </span>
                                    </div>

                                    <div class="small mt-1">
                                        <span class="badge bg-{{ $med->stock > 0 ? 'success' : 'danger' }}">
                                            Stock: {{ $med->stock }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>
            </div>

            <!-- ================= TABLA DISPENSACIÓN ================= -->
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle mb-2">
                    <thead class="table-light">
                        <tr class="text-muted small">
                            <th>Medicamento</th>
                            <th width="20" style="width: 120px;" class="text-center">Cantidad</th>
                            <th width="20" class="text-center">Precio</th>
                            <th width="110" class="text-end">Subtotal</th>
                            <th width="40"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detalle as $i => $item)
                            <tr>
                                <td class="small">

                                    <li class="list-group-item list-group-item-action py-2"style="cursor:pointer">

                                        <div class="fw-semibold text-primary">
                                            {{ $item['nombre'] }}
                                        </div>

                                        <div class="small text-muted d-flex justify-content-between">
                                            <span>
                                                {{ $item['concentracion'] }} · {{ $item['presentacion'] }}
                                            </span>
                                            <span class="fst-italic">
                                                {{ $item['marca'] }}
                                            </span>
                                        </div>

                                        <div class="small mt-1">
                                            <span class="badge bg-{{ $item['stock'] > 0 ? 'success' : 'danger' }}">
                                                Stock: {{ $item['stock'] }}
                                            </span>
                                        </div>
                                    </li>
                                </td>


                                <!-- CANTIDAD -->
                                <td style="width: 120px; min-width:120px; max-width:120px;">
                                    <div class="border rounded px-2 py-2 bg-white">
                                        <small class="text-muted d-block">Cantidad</small>

                                        <input type="number" min="0" step="0.01"
                                            wire:model.live="detalle.{{ $i }}.cantidad"
                                            class="form-control-sm w-100 form-control-sm  text-secondary fs-6 border-0 bg-light text-end fw-semibold">
                                    </div>
                                </td>

                                <!-- PRECIO -->
                                <td style="width: 120px; min-width:120px; max-width:120px;">
                                    <div class="border rounded px-2 py-2 bg-white">
                                        <small class="text-muted d-block">Precio (S/)</small>

                                        <input type="number" min="0" step="0.01"
                                            wire:model.live="detalle.{{ $i }}.precio"
                                            class="form-control-sm w-100 form-control-sm  text-secondary fs-6 border-0 bg-light text-end fw-semibold">
                                    </div>
                                </td>


                                <!-- SUBTOTAL -->
                                <td style="width: 120px; min-width:120px; max-width:120px; ">
                                    <div class="border rounded px-2 py-2 bg-white text-success">
                                        <small class="text-muted d-block">Subtotal</small>
                                        <input
                                            class="form-control-sm w-100 form-control-sm  text-success fs-6 border-0 bg-light text-end fw-semibold"
                                            readonly disabled value=" S/ {{ number_format($item['subtotal'], 2) }}">
                                    </div>
                                </td>

                                <!-- ACCIÓN -->
                                <td class="text-center pb-2">
                                    <button class="btn btn-sm btn-outline-danger rounded-circle"
                                        wire:click="removeItem({{ $i }})" title="Quitar medicamento"
                                        type="button">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted small py-3">
                                    No hay medicamentos agregados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ================= ACCIÓN FINAL ================= -->
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary btn-sm px-4" wire:click="guardarMedicamentos" type="button">
                    <i class="fa fa-check me-1"></i>
                    Dispensar medicamentos
                </button>
            </div>

        </div>
    </div>


    <div class="card border-0 shadow-sm mt-3">

        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <div class="icon-clinico mr-2">
                    <i class="fa fa-pills"></i>
                </div>

                <div>
                    <div class="fw-semibold text-clinico">
                        Medicamentos Dispensados
                    </div>
                    <div class="small text-muted">
                        Registro histórico de medicamentos entregados en esta atención
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-toggle-clinico" wire:click="toggleDispensados">
                <i class="fa {{ $mostrarDispensados ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                {{ $mostrarDispensados ? 'Ocultar detalle' : 'Ver detalle' }}
            </button>

        </div>


        <!-- CONTENIDO DESPLEGABLE -->
        @if ($mostrarDispensados)
            <div class="card-body p-0">

                @if ($medicamentosDispensados->count())
                    <ul class="list-group list-group-flush">

                        @foreach ($medicamentosDispensados as $item)
                            <li class="list-group-item">

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong class="text-clinico">
                                            {{ $item->medicamentos->nombre }}
                                        </strong>

                                        <div class="small text-muted">
                                            {{ $item->medicamentos->concentracion }}
                                            · {{ $item->medicamentos->presentacion }}
                                            · {{ $item->medicamentos->marca }}
                                        </div>

                                        <div class="small mt-1">
                                            Cantidad: <b>{{ $item->cantidad }}</b>
                                            | Precio: <b>S/ {{ number_format($item->precio, 2) }}</b>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <span class="badge bg-light text-dark">
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </span>

                                        <div class="fw-semibold text-primary mt-1">
                                            S/ {{ number_format($item->subtotal, 2) }}
                                        </div>
                                    </div>
                                </div>

                            </li>
                        @endforeach

                    </ul>
                @else
                    <div class="p-3 text-center text-muted small">
                        No se han dispensado medicamentos aún
                    </div>
                @endif

            </div>
        @endif

    </div>



</div>
