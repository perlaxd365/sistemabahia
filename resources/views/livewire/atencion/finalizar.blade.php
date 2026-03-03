<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                Finalizar Atención #{{ $atencion->id_atencion }}
            </h3>
            <small class="text-muted">
                Verificación final antes de envío a SUSALUD
            </small>
        </div>

        <span
            class="badge rounded-pill px-4 py-2 fs-6
            {{ $atencion->estado === 'FINALIZADA' ? 'bg-success' : 'bg-warning text-dark' }}">
            {{ $atencion->estado }}
        </span>
    </div>


    <div class="row g-4">

        {{-- PACIENTE --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="bi bi-person-vcard me-2"></i>
                        Información del Paciente
                    </h6>

                    <p class="mb-1 fw-semibold">
                        {{ $atencion->paciente->apellido_paterno }}
                        {{ $atencion->paciente->apellido_materno }},
                        {{ $atencion->paciente->nombres }}
                    </p>

                    <small class="text-muted">
                        DNI: {{ $atencion->paciente->dni ?? 'No registrado' }}
                    </small>
                </div>
            </div>
        </div>


        {{-- MÉDICO --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Médico Responsable
                    </h6>

                    <p class="mb-1 fw-semibold">
                        {{ $atencion->medico->name ?? 'No asignado' }}
                    </p>

                    <small class="text-muted">
                        CMP: {{ $atencion->medico->colegiatura_cargo ?? 'No registrado' }}
                    </small>
                </div>
            </div>
        </div>


        {{-- DIAGNÓSTICOS --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-primary fw-bold mb-3">
                        <i class="bi bi-clipboard2-pulse me-2"></i>
                        Diagnósticos Registrados
                    </h6>

                    @forelse($atencion->diagnosticos as $diag)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <span class="fw-semibold">
                                    {{ $diag->cie10->codigo }}
                                </span>
                                <span class="text-muted">
                                    - {{ $diag->cie10->descripcion }}
                                </span>
                            </div>

                            <span
                                class="badge
                                {{ $diag->tipo === 'PRINCIPAL' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $diag->tipo }}
                            </span>
                        </div>
                    @empty
                        <div class="alert alert-danger mb-0">
                            No existen diagnósticos registrados.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>


        {{-- VALIDACIÓN --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h6 class="fw-bold mb-3">
                        Validación para cierre SUSALUD
                    </h6>

                    @if (!empty($errores))
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errores as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            ✔ La atención cumple con todos los requisitos para ser finalizada.
                        </div>
                    @endif

                </div>
            </div>
        </div>


        {{-- BOTÓN --}}
        <div class="col-12 text-end mt-3">

            @if ($atencion->estado === 'FINALIZADA')
                <button class="btn btn-success btn-lg px-4" disabled>
                    <i class="bi bi-check-circle me-2"></i>
                    Atención Finalizada
                </button>
            @else
                <input type="button" @if (!empty($errores)) disabled @endif
                    class="btn btn-primary btn-lg px-5 shadow-sm" wire:click="finalizar" value="Finalizar Atención">
            @endif

        </div>

    </div>

</div>
