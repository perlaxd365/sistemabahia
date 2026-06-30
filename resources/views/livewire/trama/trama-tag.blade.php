<div>

    <div>
        <div class="p-4">

            <h4 class="mb-3">
                📋 TAD G - Procedimientos Hospitalarios
            </h4>

            <div class="row mb-3">

                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model="fecha_inicio">
                </div>

                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model="fecha_fin">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100" wire:click="filtrar">

                        <i class="fas fa-search"></i>
                        Filtrar

                    </button>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success w-100" wire:click="exportarTxtTG">

                        <i class="fas fa-file-export"></i>
                        Exportar TAD G

                    </button>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-sm">

                    <thead class="table-dark">

                        <tr>

                            <th>Periodo</th>

                            <th>Procedimiento</th>

                            <th>Descripción</th>

                            <th>UPS</th>

                            <th>Nombre UPS</th>

                            <th class="text-center">Cantidad</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $row)
                            <tr>

                                <td>{{ $row['periodo'] }}</td>

                                <td>
                                    <strong>{{ $row['procedimiento'] }}</strong>
                                </td>

                                <td>{{ $row['denominacion'] }}</td>

                                <td>{{ $row['ups'] }}</td>

                                <td>{{ $row['nombre_ups'] }}</td>

                                <td class="text-center">
                                    {{ $row['cantidad'] }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center text-muted">

                                    No existen procedimientos registrados
                                    para el periodo seleccionado.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>
