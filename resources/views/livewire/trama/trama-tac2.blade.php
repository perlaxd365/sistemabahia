<div>
    <div class="p-4">

        <h4 class="mb-3">🚨 TAC2 - Morbilidad Emergencia</h4>

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
                <button class="btn btn-success w-100" wire:click="exportarTxtTAC2">
                    Exportar TAC2
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Periodo</th>
                        <th>DNI</th>
                        <th>Paciente</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>CIE10</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atenciones as $a)
                        @if ($a->paciente)
                            @foreach ($a->diagnosticos as $diag)
                                @if ($diag->tipo === 'PRINCIPAL' && $diag->cie10)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('Ym') }}</td>
                                        <td>{{ $a->paciente->dni }}</td>
                                        <td>{{ $a->paciente->name }}</td>
                                        <td>{{ $a->paciente->genero }}</td>
                                        <td>{{ $a->paciente->edad }}</td>
                                        <td>{{ $diag->cie10->codigo }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
