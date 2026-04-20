<div>
    <div class="p-4">

        <h4 class="mb-3">📊 Reporte Consolidado de Morbilidad en Consulta Ambulatoria
        </h4>

        <!-- FILTROS -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" wire:model="fecha_inicio">
            </div>

            <div class="col-md-3">
                <label>Fecha Fin</label>
                <input type="date" class="form-control" wire:model="fecha_fin">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" wire:click="filtrar">
                    🔍 Filtrar
                </button>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success w-100" wire:click="exportarTxtB2">
                    ⬇ Exportar TXT B2
                </button>
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Periodo</th>
                        <th>Paciente</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Grupo</th>
                        <th>CIE10</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atenciones as $a)
                        @if ($a->paciente)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('Ym') }}</td>
                                <td>{{ $a->paciente->dni ?? '-' }}</td>
                                <td>{{ $a->paciente->genero ?? '-' }}</td>
                                <td>{{ $a->paciente->edad ?? 0 }}</td>
                                <td>
                                    {{ match (true) {
                                        $a->paciente->edad < 1 => 1,
                                        $a->paciente->edad <= 4 => 2,
                                        $a->paciente->edad <= 9 => 3,
                                        $a->paciente->edad <= 14 => 4,
                                        $a->paciente->edad <= 19 => 5,
                                        $a->paciente->edad <= 24 => 6,
                                        $a->paciente->edad <= 29 => 7,
                                        $a->paciente->edad <= 34 => 8,
                                        $a->paciente->edad <= 39 => 9,
                                        $a->paciente->edad <= 44 => 10,
                                        $a->paciente->edad <= 49 => 11,
                                        $a->paciente->edad <= 54 => 12,
                                        $a->paciente->edad <= 59 => 13,
                                        $a->paciente->edad <= 64 => 14,
                                        default => 15,
                                    } }}
                                </td>
                                <td>
                                    @php
                                        $cie = $a->diagnosticos->where('tipo', 'PRINCIPAL')->first();
                                    @endphp

                                    @if ($cie && $cie->cie10)
                                        {{ $cie->cie10->codigo }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
