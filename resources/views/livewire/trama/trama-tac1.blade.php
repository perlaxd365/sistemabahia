<div class="p-4">

    <h4 class="mb-3">🚨 TAC1 - Emergencia (Producción Asistencial)</h4>

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
            <button class="btn btn-success w-100" wire:click="exportarTxtC1">
                ⬇ Exportar TAC1
            </button>
        </div>
    </div>

    <!-- RESUMEN -->
    <div class="mb-2">
        <strong>Total atenciones:</strong> {{ count($atenciones) }}
    </div>

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Periodo</th>
                    <th>DNI</th>
                    <th>Paciente</th>
                    <th>Sexo</th>
                    <th>Edad</th>
                    <th>Grupo Edad</th>
                    <th>Fecha Atención</th>
                </tr>
            </thead>
            <tbody>
                @forelse($atenciones as $a)
                    @if ($a->paciente)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('Ym') }}
                            </td>

                            <td>{{ $a->paciente->dni }}</td>

                            <td>
                                {{ $a->paciente->name ?? '' }}
                            </td>

                            <td>
                                {{ strtoupper(substr($a->paciente->genero ?? '-', 0, 1)) }}
                            </td>

                            <td>{{ $a->paciente->edad ?? 0 }}</td>

                            <td>
                                {{ $this->grupoEdad($a->paciente->edad ?? 0) }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            ⚠ No hay atenciones en el rango seleccionado
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
