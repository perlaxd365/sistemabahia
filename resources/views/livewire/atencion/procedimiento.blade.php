<div class="hc-card">

    <div class="hc-header">
        Procedimientos Clínicos (SETI - SUSALUD)
    </div>

    <div class="hc-body">

        <div class="alert alert-light border shadow-sm">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="mb-1 fw-semibold">
                        ¿Se realizaron procedimientos durante esta atención?
                    </h6>

                    <small class="text-muted">

                        Si durante la atención se realizó algún procedimiento,
                        actívelo y registre el procedimiento junto con la UPS.

                    </small>

                </div>

                <div>

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" style="transform:scale(1.3)"
                            wire:model.live="tieneProcedimientos">

                    </div>

                </div>

            </div>

        </div>

        @if ($tieneProcedimientos)

            <div class="row">

                {{-- PROCEDIMIENTO --}}
                <div class="col-md-6 position-relative">

                    <label class="fw-semibold">

                        Procedimiento

                    </label>

                    <input type="text" class="form-control form-control-lg"
                        placeholder="Buscar código o procedimiento..." wire:model.live.debounce.500ms="buscar">

                    @if (!empty($resultados))

                        <div class="list-group position-absolute w-100 shadow"
                            style="z-index:9999;max-height:300px;overflow:auto;">

                            @foreach ($resultados as $item)
                                <button type="button" class="list-group-item list-group-item-action"
                                    wire:click="seleccionar({{ $item->id }})">

                                    <div class="d-flex justify-content-between">

                                        <strong class="text-primary">

                                            {{ $item->codigo }}

                                        </strong>

                                        <span class="badge bg-secondary">

                                            {{ $item->subseccion_codigo }}

                                        </span>

                                    </div>

                                    <div class="fw-semibold mt-1">

                                        {{ $item->denominacion }}

                                    </div>

                                    <div class="mt-2">

                                        <span class="badge bg-primary">

                                            {{ $item->grupo_nombre }}

                                        </span>

                                        <span class="badge bg-info text-dark">

                                            {{ $item->seccion_nombre }}

                                        </span>

                                        <span class="badge bg-light text-dark border">

                                            {{ $item->subseccion_nombre }}

                                        </span>

                                    </div>

                                </button>
                            @endforeach

                        </div>

                    @endif

                </div>

                {{-- UPS --}}
                <div class="col-md-4 position-relative">

                    <label class="fw-semibold">

                        UPS

                    </label>

                    <input type="text" class="form-control form-control-lg" placeholder="Buscar código o UPS..."
                        wire:model.live.debounce.500ms="buscarUps">

                    @if (!empty($resultadosUps))

                        <div class="list-group position-absolute w-100 shadow"
                            style="z-index:9999;max-height:300px;overflow:auto;">

                            @foreach ($resultadosUps as $ups)
                                <button type="button" class="list-group-item list-group-item-action"
                                    wire:click="seleccionarUps({{ $ups->id }})">

                                    <div class="d-flex justify-content-between">

                                        <strong class="text-success">

                                            {{ $ups->codigo }}

                                        </strong>

                                    </div>

                                    <div class="fw-semibold">

                                        {{ $ups->nombre }}

                                    </div>

                                </button>
                            @endforeach

                        </div>

                    @endif

                </div>

                {{-- CANTIDAD --}}
                <div class="col-md-2">

                    <label class="fw-semibold">

                        Cantidad

                    </label>

                    <input type="number" min="1" class="form-control form-control-lg" wire:model="cantidad">

                </div>

            </div>

            <div class="text-end mt-4">

                <button type="button" class="btn btn-primary btn-lg" wire:click.prevent="agregar">

                    <i class="fa fa-plus-circle"></i>

                    Agregar procedimiento

                </button>

            </div>

            <hr>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="12%">
                                Código
                            </th>

                            <th>
                                Procedimiento
                            </th>

                            <th width="25%">
                                UPS
                            </th>

                            <th width="8%">
                                Cant.
                            </th>

                            <th width="8%">
                                Acción
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($procedimientos as $item)
                            <tr>

                                <td>

                                    <strong class="text-primary">

                                        {{ $item->procedimiento->codigo }}

                                    </strong>

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $item->procedimiento->denominacion }}

                                    </div>

                                    <div class="mt-2">

                                        <span class="badge bg-primary">

                                            {{ $item->procedimiento->grupo_nombre }}

                                        </span>

                                        <span class="badge bg-info text-dark">

                                            {{ $item->procedimiento->seccion_nombre }}

                                        </span>

                                    </div>

                                    <small class="text-muted d-block mt-2">

                                        {{ $item->procedimiento->subseccion_codigo }}

                                        -

                                        {{ $item->procedimiento->subseccion_nombre }}

                                    </small>

                                </td>

                                <td>

                                    <strong class="text-success">

                                        {{ $item->ups->codigo }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ $item->ups->nombre }}

                                    </small>

                                </td>

                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $item->cantidad }}

                                    </span>

                                </td>

                                <td>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        wire:click.prevent="eliminar({{ $item->id }})">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center text-muted py-4">

                                    No existen procedimientos registrados.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>
