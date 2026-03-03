<div class="hc-card shadow-sm border-0 rounded-4">

    <!-- HEADER -->
    <div class="hc-header px-4 py-3 border-bottom bg-white rounded-top-4">
        <h5 class="mb-0 fw-bold" style="color:#1e3a8a;">
            Órdenes de Laboratorio
        </h5>
    </div>

    <div class="hc-body p-4 bg-light-subtle rounded-bottom-4">

        @if ($ordenes->isEmpty())
            <div class="alert alert-info border-0 shadow-sm rounded-3">
                No existen resultados de laboratorio para esta atención.
            </div>
        @endif


        @foreach ($ordenes as $orden)
            <div class="card border-0 shadow-sm mb-4 rounded-4">

                <!-- CABECERA -->
                <div class="card-body border-bottom">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <div class="fw-bold fs-6" style="color:#334155;">
                                Orden N° {{ $orden->id_orden }}
                            </div>

                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div>
                            <span
                                class="px-3 py-2 rounded-pill small fw-semibold
                                {{ $orden->estado === 'FINALIZADO' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                {{ $orden->estado }}
                            </span>
                        </div>

                    </div>

                </div>


                <!-- DETALLE EXÁMENES -->
                <div class="card-body">

                    <div class="row g-2">

                        @foreach ($orden->detalles as $det)
                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="border rounded-3 p-2 bg-white shadow-sm small">

                                    <div class="fw-semibold text-truncate">
                                        {{ $det->examenes->nombre ?? $det->examen_manual }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $det->examenes->areas->nombre ?? 'Manual' }}
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>


                <!-- FOOTER ACCIONES -->
                <div class="card-footer bg-white border-0 text-end">

                    @if (($orden->estado === 'PROCESO' || $orden->estado === 'FINALIZADO') && $orden->ruta_pdf_resultado)
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
                    @else
                        <span class="text-muted small">
                            En espera de resultados
                        </span>
                    @endif

                </div>

            </div>
        @endforeach

    </div>
</div>
