<div>
    <div class="container">



        <div class="card border-0 shadow-sm mt-3">

            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-3">
                    <div class="icon-clinico mr-2">
                        <i class="fa fa-check fa-lg"></i>
                    </div>

                    <div>
                        <div class="fw-semibold text-clinico">
                            Resultados de la Atención de <b>{{ $nombre_paciente }}</b>
                        </div>
                        <div class="small text-muted">
                            Resultados de Laboratorio e Imagen
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>




        {{-- RESULTADOS DE LABORATORIO --}}
        <div class="card shadow-sm mb-4 border-start border-4 border-primary">
            <div class="card-header bg-white d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    🔬 Resultados de Laboratorio
                </h5>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-3">
                    Exámenes realizados por el área de laboratorio clínico.
                    Incluye resultados, observaciones y firma del responsable.
                </p>

                <livewire:atencion.resultados.laboratorio :id_atencion="$id_atencion" />
            </div>
        </div>

        {{-- RESULTADOS DE IMÁGENES --}}
        <div class="card shadow-sm mb-4 border-start border-4 border-success">
            <div class="card-header bg-white d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-success">
                    🖼️ Resultados de Imágenes
                </h5>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-3">
                    Estudios de diagnóstico por imágenes.
                    Incluye informe radiológico y firma del especialista.
                </p>


                <livewire:atencion.resultados.imagen :id_atencion="$id_atencion" />
            </div>
        </div>

        {{-- PIE CLÍNICO --}}
        <div class="text-center text-muted small mt-4">
            Sistema Clínico — Resultados generados bajo responsabilidad profesional
        </div>

    </div>

</div>
