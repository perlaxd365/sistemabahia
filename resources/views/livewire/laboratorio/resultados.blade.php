<div class="container-fluid px-4">

    <!-- ================= HEADER ================= -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <div class="text-uppercase text-muted small fw-semibold mb-1">
                        Informe de Laboratorio
                    </div>

                    <h4 class="fw-bold mb-1" style="color:#1e3a8a;">
                        Orden N° {{ $orden->id_orden }} | Atencion # {{ $atencion->id_atencion }}
                    </h4>

                    <div class="text-muted small">
                        {{ $orden->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div>
                    <span
                        class="px-4 py-2 rounded-pill small fw-semibold
                        {{ $orden->estado === 'FINALIZADO' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                        {{ $orden->estado }}
                    </span>
                </div>

            </div>

            <hr class="my-4">

            <div class="row g-4">

                <!-- Paciente -->
                <div class="col-md-6">
                    <div class="p-3 rounded-4 border bg-light-subtle">

                        <div class="text-uppercase small text-muted fw-semibold mb-2">
                            Paciente
                        </div>

                        <div class="fs-5 fw-bold">
                            {{ $paciente->name }}
                        </div>

                        <div class="small text-muted mt-2">
                            DNI: {{ $paciente->dni ?? '—' }} <br>
                            Historia Clínica: {{ $historia->nro_historia ?? '—' }}
                        </div>

                    </div>
                </div>

                <!-- Información Clínica -->
                <div class="col-md-6">
                    <div class="p-3 rounded-4 border bg-light-subtle">

                        <div class="text-uppercase small text-muted fw-semibold mb-2">
                            Información Clínica
                        </div>

                        <div class="small text-muted">
                            <strong>Fecha Atención:</strong>
                            {{ DateUtil::getFechaSimple($atencion->fecha_inicio_atencion) }} <br>

                            <strong>Fecha Nacimiento:</strong>
                            {{ DateUtil::getFechaSimple($paciente->fecha_nacimiento) ?? '—' }} <br>

                            <strong>Solicitante:</strong>
                            {{ UserUtil::getUserbyID($orden->solicitante)->name ?? '—' }}
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- ================= EXÁMENES ================= -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-bold text-secondary">
                Exámenes Solicitados
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-2">

                @foreach ($orden->detalles as $det)
                    <div class="col-md-4 col-sm-6">

                        <div
                            class="border rounded-pill px-3 py-2 small d-flex justify-content-between align-items-center bg-light">

                            <span class="text-truncate">
                                @if ($det->examenes)
                                    {{ $det->examenes->nombre }}
                                @else
                                    {{ $det->examen_manual }}
                                @endif
                            </span>

                            @if (!$det->examenes)
                                <span class="badge bg-secondary ms-2">Manual</span>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>


    <!-- ================= INFORME PDF ================= -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h6 class="fw-bold mb-4" style="color:#334155;">
                Documento de Resultados
            </h6>

            @if ($orden->puedeActualizarPdf())
                <div class="border rounded-4 p-4 bg-light-subtle mb-4">

                    <label class="form-label fw-semibold small text-muted">
                        Subir o actualizar informe (PDF)
                    </label>

                    <input type="file" class="form-control form-control-sm" wire:model="pdfResultado"
                        accept="application/pdf">

                    @error('pdfResultado')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <div wire:loading wire:target="pdfResultado" class="small text-muted mt-2">
                        Procesando archivo...
                    </div>

                </div>
            @endif


            @if ($orden->ruta_pdf_resultado)
                <div class="p-4 rounded-4 border bg-white shadow-sm">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <div class="fw-semibold text-success">
                                Documento cargado correctamente
                            </div>

                            <div class="small text-muted">
                                Actualizado el
                                {{ \Carbon\Carbon::parse($orden->fecha_subida_pdf)->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <a href="https://res.cloudinary.com/{{ config('cloudinary.cloud_name') }}/raw/upload/{{ $orden->ruta_pdf_resultado }}"
                                class="btn btn-outline-primary btn-sm rounded-pill px-4" target="_blank">
                                Ver PDF
                            </a>
                        </div>

                    </div>

                </div>
            @endif

        </div>


        <!-- ================= FOOTER BOTONES ================= -->
        <div class="card-footer bg-white border-0 text-end p-4">

            @if ($orden->puedeActualizarPdf())
                <button class="btn btn-primary rounded-pill px-4" wire:click="subirResultado"
                    wire:loading.attr="disabled">
                    Guardar / Actualizar Documento
                </button>
            @endif
            @if ($orden->ruta_pdf_resultado && $orden->puedeActualizarPdf())
                <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="finalizarInforme"
                    wire:loading.attr="disabled">
                    Finalizar y Registrar Informe
                </button>
            @endif


        </div>

    </div>

</div>
