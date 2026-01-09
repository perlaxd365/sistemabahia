<div class="container">
    {{--  ENCABEZADO --}}
    <div class="card border-0 shadow-sm mt-3">

        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <div class="icon-clinico mr-2">
                    <i class="fa fa-notes-medical fa-lg"></i>
                </div>

                <div>
                    <div class="fw-semibold text-clinico">
                        Informe de Imagen</b>
                    </div>
                    <div class="small text-muted">
                        Registro histórico de imagenes para esta atención
                    </div>
                </div>
            </div>

        </div>
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
                                {{ UserUtil::getUserbyID($orden->solicitante)->name ?? '—' }} /
                                {{ UserUtil::getUserbyID($orden->solicitante)->nombre_cargo ?? '—' }}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- IDENTIFICADORES -->
                <div class="col-md-2 text-center small">

                    <div class="text-muted text-uppercase ">Tipo</div>
                    <span class="badge bg-info-subtle text-dark px-3 py-2 mb-2 d-inline-block">
                        IMAGEN
                    </span>

                    <div class="text-muted text-uppercase">Fecha Orden</div>
                    <div class="fw-semibold">
                        {{ $orden->created_at->format('d/m/Y H:i') }}
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- FIN ENCABEZADO --}}


    {{-- FORMULARIO --}}
    <form wire:submit.prevent="guardar">

        @foreach ($orden->detalles as $detalle)
            <div class="card mb-4">
                <div class="card-header">
                    <strong>
                        @if ($detalle->id_estudio && $detalle->estudio)
                            {{ optional($detalle->estudio->area)->nombre ?? 'Área no asignada' }}
                            -
                            {{ $detalle->estudio->nombre }}
                        @else
                            {{ $detalle->descripcion_manual }}
                            <span class="badge bg-secondary ms-2">Manual</span>
                        @endif
                    </strong>
                </div>

                <div class="card-body">

                    {{-- INFORME --}}
                    <div class="mb-3">
                        <label class="form-label">Informe</label>
                        <textarea class="form-control" rows="6" wire:model.defer="informes.{{ $detalle->id_detalle_imagen }}">
                        </textarea>
                    </div>

                    {{-- SUBIR IMÁGENES --}}

                    <div class="mb-3">
                        <label class="form-label">Imágenes del estudio</label>
                    </div>
                    <ul>
                        <li>
                            @if (!empty($archivos_existentes[$detalle->id_detalle_imagen]))
                                <div class="card shadow-sm border position-relative" style="max-width: 420px;">


                                    <div class="card-body d-flex align-items-center pl-3">

                                        {{-- ICONO --}}
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                            style="width: 55px; height: 55px;">
                                            <i class="fa fa-file fs-3 text-primary"></i>
                                        </div>

                                        {{-- INFO --}}
                                        <div class="flex-grow-1 ml-2">
                                            <div class="fw-semibold text-dark">Resultado de Imagen</div>
                                            <div class="text-muted small">Archivo adjunto</div>

                                            <a href="{{ $archivos_existentes[$detalle->id_detalle_imagen] }}"
                                                target="_blank" class="text-primary small fw-semibold">
                                                Ver / Descargar
                                            </a>
                                        </div>
                                        {{-- BOTÓN ELIMINAR --}}
                                        <button type="button"
                                            wire:click="eliminarArchivo({{ $detalle->id_detalle_imagen }})"
                                            class="btn btn-sm btn-light   m-2 border" title="Eliminar archivo">
                                            <i class="fa-solid fa fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <input type="file" wire:model="archivos.{{ $detalle->id_detalle_imagen }}"
                                    class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach

        {{-- ACCIONES --}}
        <div class="text-end">
            <button class="btn btn-success">
                Guardar Resultados
            </button>
        </div>

    </form>
  <form wire:submit.prevent="finalizar">
        <div class="">
            <button class="btn btn-warning">
               Finalizar y Enviar
            </button>
        </div>
  </form>
</div>
