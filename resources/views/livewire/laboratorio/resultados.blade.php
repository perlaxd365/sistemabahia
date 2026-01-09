<div>
    <div class="card mb-4 border-0 shadow-sm">

        <!-- BANDA SUPERIOR -->
        <div class="card-header bg-light border-0 py-2">
            <div class="d-flex justify-content-between align-items-center">

                <div class="fw-bold text-uppercase text-secondary small">
                    Resultados de Laboratorio
                </div>

                <div>
                    <span class="badge bg-secondary-subtle text-dark px-3 py-2">
                        Orden N° {{ $orden->id_orden }}
                    </span>
                    <span class="badge bg-{{ $orden->estado === 'FINALIZADO' ? 'success' : 'warning' }} ms-2 px-3 py-2">
                        {{ $orden->estado }}
                    </span>
                </div>

            </div>
        </div>

        <!-- CUERPO -->
        <div class="card-body py-3">

            <div class="row g-3">

                <!-- PACIENTE -->
                <div class="col-md-5 border-end">
                    <div class="text-muted small text-uppercase">Paciente</div>
                    <div class="fs-5 fw-semibold text-primary">
                        {{ $paciente->name }}
                    </div>

                    <div class="mt-1 small">
                        <span class="me-3">
                            <strong>DNI:</strong> {{ $paciente->dni ?? '—' }}
                        </span>
                        <span>
                            <strong>HC:</strong> {{ $historia->nro_historia ?? '—' }}
                        </span>
                    </div>
                </div>

                <!-- DATOS CLÍNICOS -->
                <div class="col-md-5 border-end small">

                    <div class="row">
                        <div class="col-6 mb-2">
                            <div class="text-muted text-uppercase">Fecha Atención</div>
                            <div class="fw-semibold">
                                {{ DateUtil::getFechaSimple($atencion->fecha_inicio_atencion) }}
                            </div>
                        </div>

                        <div class="col-6 mb-2">
                            <div class="text-muted text-uppercase">Fecha de Nacimiento</div>
                            <div class="fw-semibold">
                                {{ DateUtil::getFechaSimple($paciente->fecha_nacimiento) ?? '—' }}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="text-muted text-uppercase">Solicitante</div>
                            <div class="fw-semibold">
                                {{ UserUtil::getUserbyID($orden->solicitante)->name ??  '—' }} / {{UserUtil::getUserbyID($orden->solicitante)->nombre_cargo ??  '—'}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- IDENTIFICADORES -->
                <div class="col-md-2 text-center small">

                    <div class="text-muted text-uppercase mb-1">Tipo</div>
                    <span class="badge bg-info-subtle text-dark px-3 py-2 mb-2 d-inline-block">
                        LABORATORIO
                    </span>

                    <div class="text-muted text-uppercase mt-2">Fecha Orden</div>
                    <div class="fw-semibold">
                        {{ $orden->created_at->format('d/m/Y H:i') }}
                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="card shadow-sm border-0">

        <!-- CABECERA -->
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-primary">
                🔬 Resultados de Laboratorio
            </h5>
            <small class="text-muted">
                Orden #{{ $orden->id_orden }} · {{ $orden->created_at->format('d/m/Y') }}
            </small>
        </div>

        <!-- BODY -->
        <div class="card-body">

            @foreach ($orden->detalles as $det)
                <div class="border rounded p-3 mb-3">

                    <h6 class="text-uppercase text-secondary mb-2">
                        <strong>
                            @if ($det->examenes)
                                {{ $det->examenes->nombre }}
                            @else
                                {{ $det->examen_manual }}
                                <span class="badge bg-secondary ms-1">Manual</span>
                            @endif
                        </strong>
                    </h6>

                    <div class="row g-2">


                        <!-- CKEDITOR -->
                        <div class="col-md-12">
                            <label class="small text-muted">Resultado</label>
                            <div wire:ignore>
                                <textarea id="editor_{{ $det->id_detalle_laboratorio }}" class="form-control">{!! $resultados[$det->id_detalle_laboratorio] ?? '' !!}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <!-- FOOTER -->
        <div class="card-footer bg-light text-end">
            <button class="btn btn-primary" wire:click="guardar">
                Guardar temporalmente
            </button>
            <button class="btn btn-secondary" wire:click="vista_previa">
                Vista Previa
            </button>
            <button class="btn btn-warning" wire:click="finalizar">
                💾 Finalizar y Enviar
            </button>
        </div>


    </div>
</div>
