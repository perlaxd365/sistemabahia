<div>
    <div class="p-4">

        <h4 class="mb-3">🏥 TAD1 - Hospitalización</h4>

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
                <button class="btn btn-success w-100" wire:click="exportarTxtTAD1">
                    ⬇ Exportar TAD1
                </button>
            </div>
        </div>

        <!-- INFO -->
        <div class="alert alert-info">
            ⚠ Reporte consolidado por servicio (UPS). No es por paciente.
        </div>

        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>UPS</th>
                        <th>Servicio</th>
                        <th>Ingresos</th>
                        <th>Egresos</th>
                        <th>Estancias</th>
                        <th>Pacientes Día</th>
                        <th>Camas</th>
                        <th>Días Cama</th>
                        <th>Fallecidos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row['ups'] }}</td>
                            <td>{{ $row['nombre'] }}</td>
                            <td>{{ $row['ingresos'] }}</td>
                            <td>{{ $row['egresos'] }}</td>
                            <td>{{ $row['estancias'] }}</td>
                            <td>{{ $row['pacientes_dia'] }}</td>
                            <td>{{ $row['camas'] }}</td>
                            <td>{{ $row['dias_cama'] }}</td>
                            <td>{{ $row['fallecidos'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                ⚠ No hay datos disponibles
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
