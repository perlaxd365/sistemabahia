<div>
    <div class="p-4">

        <h4 class="mb-3">🏥 TAD2 - Morbilidad Hospitalización</h4>

        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" class="form-control" wire:model="fecha_inicio">
            </div>

            <div class="col-md-3">
                <input type="date" class="form-control" wire:model="fecha_fin">
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100" wire:click="filtrar">
                    Filtrar
                </button>
            </div>

            <div class="col-md-3">
                <button class="btn btn-success w-100" wire:click="exportarTxtTAD2">
                    Exportar TAD2
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Periodo</th>
                        <th>DNI</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>CIE10</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atenciones as $a)
                        @foreach ($a->diagnosticos as $diag)
                            @if ($diag->tipo === 'PRINCIPAL' && $diag->cie10)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('Ym') }}</td>
                                    <td>{{ $a->paciente->dni }}</td>
                                    <td>{{ $a->paciente->genero }}</td>
                                    <td>{{ $a->paciente->edad }}</td>
                                    <td>{{ $diag->cie10->codigo }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
