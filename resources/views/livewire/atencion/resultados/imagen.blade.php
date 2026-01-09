<div class="hc-card">

    <div class="hc-header">
        Órdenes de Imagen
    </div>
    <br>
    <div class="hc-body">

        @if ($ordenes->isEmpty())
            <div class="alert alert-info">
                No hay órdenes de imagen registradas.
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
                        </small>
                    </div>

                    <span
                        class="badge
                        {{ $orden->estado === 'INFORMADO' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $orden->estado }}
                    </span>
                </div>

                {{-- DETALLES --}}
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @foreach ($orden->detalles as $detalle)
                            <li class="list-group-item py-1">
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
                                @if (!empty($detalle->informe->informe))
                                    - <p>{{ $detalle->informe->informe }}</p>
                                @else
                                    Sin Comentarios
                                @endif

                                @if (!empty($detalle->informe->archivo))
                                    - <a href="{{ $detalle->informe->archivo }}" target="_blank" class="badge">
                                        <i class="fa fa-file"></i> Descargar Informe
                                    </a>
                                @else
                                    Sin Informe
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ACCIONES (opcional, para luego) --}}
                {{-- <div class="card-footer text-end">
                    <a href="{{ route('imagen.resultados', $orden->id_orden_imagen) }}"
                        class="btn btn-sm btn-outline-primary">
                        Ver resultados
                    </a>
                </div> --}}
            </div>
        @endforeach

    </div>
</div>
