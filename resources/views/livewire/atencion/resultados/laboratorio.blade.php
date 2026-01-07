<div>
    <div>

        @if ($ordenes->isEmpty())
            <div class="alert alert-secondary text-center">
                No existen resultados de laboratorio para esta atención.
            </div>
        @else
            @foreach ($ordenes as $orden)
                <div class="card shadow-sm mb-4">

                    {{-- HEADER ORDEN --}}
                    <div class="card-header bg-white d-flex justify-content-between">
                        <div>
                            <strong class="text-uppercase">
                                Orden N° {{ $orden->id_orden }}
                            </strong>
                            <div class="text-muted small">
                                Fecha: {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                            </div>
                        </div>

                        <span class="badge bg-primary align-self-center">
                            {{ $orden->estado }}
                        </span>
                    </div>

                    {{-- CUERPO --}}
                    <div class="card-body p-0">

                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Examen</th>
                                    <th style="width: 20%">Área</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orden->detalles as $det)
                                    <tr>
                                        {{-- EXAMEN --}}
                                        <td class="fw-semibold">
                                            {{ $det->examenes->nombre ?? $det->examen_manual }}
                                        </td>

                                        {{-- ÁREA --}}
                                        <td class="text-muted small">
                                            {{ $det->examenes->areas->nombre ?? 'Manual' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    {{-- FOOTER --}}
                    <div class="row">
                        <div class="col-6">

                            <div class="card-footer bg-white text-end small text-muted">
                                Validado por laboratorio clínico
                            </div>
                        </div>
                        <div class="col-6">

                            <div class="card-footer bg-white text-end small text-muted">
                                <button type="button" class="btn text-end btn-sm" wire:click="imprimirResultados({{ $orden->id_orden }})">
                                    <i class="fa fa-print me-1"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

        @endif

    </div>

</div>
