<div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-semibold text-dark">
                            Consulta de Ticket SUNAT
                        </h6>
                        <small class="text-muted">
                            Validación del estado de resúmenes diarios (boletas)
                        </small>
                    </div>

                    <div class="card-body">

                        <form wire:submit.prevent="consultar">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Número de Ticket
                                </label>
                                <input type="text" class="form-control @error('ticket') is-invalid @enderror"
                                    wire:model.defer="ticket" placeholder="Ej: 202601230001">

                                @error('ticket')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    Consultar estado
                                </button>
                            </div>
                        </form>

                        {{-- RESULTADO --}}
                        @if ($resultado)
                            <hr>

                            <div class="mt-3">

                                <div class="mb-2">
                                    <span class="fw-semibold">Estado SUNAT:</span>

                                    @if ($resultado['estado'] === 'ACEPTADO')
                                        <span class="badge bg-success ms-2">
                                            Aceptado
                                        </span>
                                    @elseif ($resultado['estado'] === 'EN_PROCESO')
                                        <span class="badge bg-warning text-dark ms-2">
                                            En proceso
                                        </span>
                                    @else
                                        <span class="badge bg-danger ms-2">
                                            Rechazado
                                        </span>
                                    @endif
                                </div>

                                @if (!empty($resultado['sunat_description']))
                                    <div class="alert alert-danger small">
                                        {{ $resultado['sunat_description'] }}
                                    </div>
                                @endif

                                @if (!empty($resultado['enlace_del_cdr']))
                                    <div class="mt-2">
                                        <a href="{{ $resultado['enlace_del_cdr'] }}" target="_blank"
                                            class="btn btn-outline-secondary btn-sm">
                                            Descargar CDR
                                        </a>
                                    </div>
                                @endif

                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
