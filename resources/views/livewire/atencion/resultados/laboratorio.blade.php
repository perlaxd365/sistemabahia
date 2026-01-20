<div class="hc-card">

    <div class="hc-header">
        Órdenes de Laboratorio
    </div>
    <br>
    <div class="hc-body">

        @if ($ordenes->isEmpty())
            <div class="alert alert-info">
                No existen resultados de laboratorio para esta atención.
            </div>
        @endif

        @foreach ($ordenes as $orden)
            <div class="card mb-3">

                {{-- CABECERA DE ORDEN --}}
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <strong>Orden #{{ $orden->id_orden_imagen }}</strong><br>
                        <small class="text-muted">
                            Fecha: {{ $orden->fecha }}
                            <span
                                class="badge {{ $orden->estado === 'FINALIZADO' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $orden->estado }}
                            </span>
                        </small>
                    </div>

                </div>

                {{-- DETALLES --}}
                <div class="container">

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



                {{-- ACCIONES (opcional, para luego) --}}
                <div class="card-footer">
                    @if ($orden->estado == 'FINALIZADO')
                        - <a href="javascript:void(0)" wire:click='imprimirResultados({{ $orden->id_orden }})'
                            class="badge text-primary">
                            <i class="fa fa-file"></i> Descargar Informe
                        </a>
                    @else
                        - <p class="badge">Espera de resultados</p>
                    @endif

                </div>
            </div>
        @endforeach

    </div>
</div>
