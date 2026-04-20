<div>
    <div class="p-4">
        <h4 class="mb-3">📊 Reporte Consolidado de Producción Asistencial en Consulta Ambulatoria
        </h4>

        {{-- FILTROS --}}
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
                <button wire:click="filtrar" class="btn btn-primary w-100">
                    Filtrar
                </button>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button wire:click="exportarTxtB1" class="btn btn-success w-100">
                    📄 Exportar TXT B1
                </button>
            </div>
        </div>

        <div class="mb-3">
            <h6>Cantidad: {{ $cantidad }}</h6>
        </div>
        {{-- TABLA --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Médico</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($atenciones as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $a->paciente->apellido_paterno ?? '' }}
                                {{ $a->paciente->apellido_materno ?? '' }}
                                {{ $a->paciente->name ?? '' }}
                            </td>

                            <td>{{ $a->paciente->genero ?? '' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($a->paciente->fecha_nacimiento)->age ?? '' }}
                            </td>

                            <td>{{ $a->medico->name ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
