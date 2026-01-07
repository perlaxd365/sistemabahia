<div>
    <div class="container">

        {{-- TÍTULO PRINCIPAL --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary">
                🧾 Resultados de la Atención
            </h4>

            <span class="badge bg-secondary">
                Historia Clínica
            </span>
        </div>

        {{-- DATOS DEL PACIENTE --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Paciente:</strong> {{ $nombre_paciente }}
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha Nacimiento:</strong> {{ DateUtil::getFechaSimple($fecha_nacimiento) }}
                    </div>
                </div>
            </div>
        </div>

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


            </div>
        </div>

        {{-- PIE CLÍNICO --}}
        <div class="text-center text-muted small mt-4">
            Sistema Clínico — Resultados generados bajo responsabilidad profesional
        </div>

    </div>

</div>
