<div>
    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <strong>Diagnósticos (CIE10)</strong>
        </div>

        <div class="card-body">

            {{-- BUSCADOR --}}
            <div class="row mb-3">
                <div class="col-md-8 position-relative">

                    <input type="text" wire:model.live="buscar" class="form-control form-control-lg"
                        placeholder="Buscar por código o descripción...">

                    @if (!empty($resultados))
                        <div class="list-group position-absolute w-100 shadow"
                            style="z-index:1000; max-height:250px; overflow-y:auto;">

                            @foreach ($resultados as $item)
                                <button type="button" class="list-group-item list-group-item-action text-start"
                                    wire:click="seleccionar({{ $item->id }})">

                                    <strong class="text-primary">
                                        {{ $item->codigo }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->descripcion }}
                                    </small>
                                </button>
                            @endforeach

                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <select wire:model="tipo" class="form-control form-select-lg">
                        <option value="PRINCIPAL">Diagnóstico Principal</option>
                        <option value="SECUNDARIO">Diagnóstico Secundario</option>
                    </select>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Código</th>
                            <th>Descripción</th>
                            <th width="15%">Tipo</th>
                            <th width="10%">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnosticos as $diag)
                            <tr>
                                <td>
                                    <strong class="text-primary">
                                        {{ $diag->cie10->codigo }}
                                    </strong>
                                </td>
                                <td>
                                    {{ $diag->cie10->descripcion }}
                                </td>
                                <td>
                                    @if ($diag->tipo == 'PRINCIPAL')
                                        <span class="badge bg-danger">
                                            PRINCIPAL
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            SECUNDARIO
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn"
                                        wire:click="eliminar({{ $diag->id }})">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No hay diagnósticos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
