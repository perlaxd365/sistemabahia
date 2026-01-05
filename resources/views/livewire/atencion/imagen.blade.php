<div>
    <div class="orden-lab p-4">

        {{-- ENCABEZADO --}}
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-clinica.png') }}" height="60">
            <h4 class="mt-2 fw-bold">ORDEN DE IMAGEN</h4>
        </div>

        {{-- DATOS PACIENTE --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Paciente:</strong> {{ $nombre_paciente }}
            </div>
            <div class="col-md-6">
                <strong>Fecha Nacimiento:</strong> {{ DateUtil::getFechaSimple($fecha_nacimiento) }}
            </div>
        </div>

        {{-- EXÁMENES agregados --}}
        @if ($ordenesLaboratorio->count())
            <div class="card mb-3 border-secondary">

                <div class="card-header bg-light">
                    <h6 class="mb-0 text-secondary">
                        <i class="fa fa-history me-1"></i>
                        Órdenes de laboratorio registradas
                    </h6>
                    <small class="text-muted">
                        Historial de exámenes solicitados en esta atención
                    </small>
                </div>

                <div class="card-body p-2">

                    @foreach ($ordenesLaboratorio as $orden)
                        <div class="mb-3 border rounded p-2">

                            {{-- Cabecera de orden --}}
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <strong>Orden #{{ $orden->id_orden }}</strong>
                                    <span
                                        class="badge bg-{{ $orden->estado == 'FINALIZADO' ? 'success' : ($orden->estado == 'PROCESO' ? 'warning' : 'secondary') }}">
                                        {{ $orden->estado }}
                                    </span>
                                </div>

                                <small class="text-muted">
                                    {{ $orden->fecha }}
                                    @if ($orden->estado === 'PENDIENTE')
                                        <button type="button" class="btn btn-sm text-secondary"
                                            wire:click="eliminarOrden({{ $orden->id_orden }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm text-secondary" disabled
                                            title="La orden ya fue procesada">
                                            <i class="fa fa-lock"></i> No editable
                                        </button>
                                    @endif
                                </small>
                            </div>

                            {{-- Exámenes --}}
                            <ul class="list-group list-group-flush small">
                                @foreach ($orden->detalles as $det)
                                    <li class="list-group-item px-0">
                                        <strong>
                                            {{ $det->examenes->nombre ?? $det->examen_manual }}
                                        </strong>

                                        @if (!$det->examenes)
                                            <span class="badge bg-warning text-dark ms-2">
                                                Manual
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    @endforeach

                </div>
            </div>
        @endif

        {{-- EXÁMENES --}}
        <div class="row">

            @foreach ($areas as $area)
                <div class="col-md-4 mb-3">
                    <div class="border p-2 h-100">
                        <strong class="text-uppercase" style="color: #3a3cc5">{{ $area->nombre }}</strong>
                        <hr class="my-1">

                        @foreach ($area->examenes as $examen)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="examenesSeleccionados"
                                    value="{{ $examen->id_examen }}" id="ex_{{ $examen->id_examen }}">
                                <label class="form-check-label" for="ex_{{ $examen->id_examen }}">
                                    {{ $examen->nombre }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="col-md-4 mb-3">
                <div class="border p-2 h-100">
                    <strong class="text-uppercase" style="color: #3a3cc5">Ingresar Manualmente</strong>
                    <hr class="my-1">
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model.live="usarExamenManual" class="form-check-input">
                        <label class="form-check-label">
                            Otro examen (no listado)
                        </label>
                    </div>

                    @if ($usarExamenManual)
                        <input type="text" class="form-control mt-2" placeholder="Ingrese nombre del examen"
                            wire:model.defer="examenManual">
                        @error('examenManual')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    @endif
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary mt-3" wire:click="guardarOrdenLaboratorio">
            <i class="fa fa-save"></i> Registrar orden de laboratorio
        </button>

        {{-- FECHA --}}
        <div class="mt-3">
            <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}
        </div>

    </div>

</div>
