<div>
    <div class="orden-lab p-4">

        {{-- ENCABEZADO --}}
        <div class="card border-0 shadow-sm mb-3">

            @if ($ordenesImagen->count())
                <!-- CABECERA -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 text-primary">
                            <i class="fa fa-x-ray me-1"></i> Órdenes de Imágenes
                        </h6>
                        <small class="text-muted">
                            Resumen de estudios solicitados en esta atención
                        </small>
                    </div>
                </div>
            @endif

            <div class="card-body p-2">

                @if ($ordenesImagen->count())

                    @foreach ($ordenesImagen as $orden)
                        <div class="border rounded p-2 mb-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span
                                        class="badge bg-{{ $orden->estado == 'PENDIENTE' ? 'warning' : ($orden->estado == 'PROCESO' ? 'info' : 'success') }}">
                                        {{ $orden->estado }}
                                    </span>

                                    <span class="text-muted small ms-2">
                                        {{ $orden->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <span class="small text-muted">
                                    Orden #{{ $orden->id_orden_imagen }}
                                </span>
                            </div>
                            <!-- ESTUDIOS -->

                            <p>- Diagnóstico: {{ $orden->diagnostico }}</p>
                            <p>- Examenes solicitados:</p>
                            <ul class="mt-2 mb-0 ps-3 small">
                                @if ($orden->detalles && $orden->detalles->count())
                                    @foreach ($orden->detalles as $det)
                                        <li>
                                            {{ $det->estudio->nombre ?? $det->descripcion_manual }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted small py-3">
                        No se han registrado órdenes de imágenes en esta atención
                    </div>
                @endif

            </div>
        </div>

        {{-- DATOS PACIENTE --}}
        <div class="card">
            <div class="card-header bg-white">

                <div class="text-center mb-3">
                    <img src="{{ asset('images/logo-clinica.png') }}" height="60">
                    <h4 class="mt-2 fw-bold">ORDEN DE IMAGEN</h4>
                </div>

                <h6 class="text-primary mb-0">
                    <i class="fa fa-x-ray me-1"></i> Orden de Imágen
                </h6>

                <h6 class="text-primary mb-0">
                    <small class="text-muted">Seleccione las órdendes de imágenes solicitadas</small>
                </h6>
                <hr style="color: gray">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Paciente:</strong> {{ $nombre_paciente }}
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha Nacimiento:</strong> {{ DateUtil::getFechaSimple($fecha_nacimiento) }}
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach ($areas as $area)
                        <div class="col-md-4 mb-3">
                            <div class="border p-2 h-100">
                                <strong class="text-uppercase text-primary">
                                    {{ $area->nombre }}
                                </strong>
                                <hr class="my-1">

                                @foreach ($area->estudios as $estudio)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                            wire:model="estudiosSeleccionados" value="{{ $estudio->id_estudio }}"
                                            id="est_{{ $estudio->id_estudio }}">
                                        <label class="form-check-label" for="est_{{ $estudio->id_estudio }}">
                                            {{ $estudio->nombre }}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($area->nombre == 'Otros Imágenes')
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" wire:model.live="imagenManual"
                                            id="imagenManual">
                                        <label class="form-check-label fw-semibold" for="imagenManual">
                                            Registrar estudio de imagen manual
                                        </label>
                                    </div>

                                    @if ($imagenManual)
                                        <input type="text" class="form-control" wire:model.defer="descripcionManual"
                                            placeholder="Ej. RX especial de hombro / Eco externa / Otro">
                                    @endif
                                @endif

                            </div>
                        </div>
                    @endforeach


                </div>

                <div class="mt-3">
                    <label class="form-label">Diagnóstico / Observación</label>
                    <textarea class="form-control" wire:model.defer="diagnostico"></textarea>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary" type="button" wire:click="guardarOrden">
                        <i class="fa fa-save me-1"></i> Guardar Orden
                    </button>
                </div>
            </div>
        </div>
    </div>
