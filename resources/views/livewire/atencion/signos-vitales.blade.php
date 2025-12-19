<div>
    <div class="info-clinica">


        <!-- ============================
          SECCIÓN: DATOS DEL PACIENTE
    ============================== -->
        <div class="card p-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-primary fw-semibold">
                    Signos vitales de <b>{{ $nombre_paciente }}</b>
                </h6>
                <small class="text-muted">
                    Control de Presión Arterial
                </small>
            </div>

            <div class=" border-0 shadow-sm mb-4">


                <div class="">

                    <!-- busqueda de servicio -->
                    <div class="container-fluid">

                        {{-- FORMULARIO --}}
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                <h6 class="fw-semibold mb-3 text-secondary">
                                    Registro de presión arterial
                                </h6>

                                <div class="row g-3">

                                    {{-- Brazo derecho --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Brazo derecho (lpm)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="Sistólica"
                                                wire:model.defer="sistolica_derecha">
                                            <span class="input-group-text">/</span>
                                            <input type="number" class="form-control" placeholder="Diastólica"
                                                wire:model.defer="diastolica_derecha">
                                        </div>
                                    </div>

                                    {{-- Brazo izquierdo --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Brazo izquierdo (lpm)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="Sistólica"
                                                wire:model.defer="sistolica_izquierda">
                                            <span class="input-group-text">/</span>
                                            <input type="number" class="form-control" placeholder="Diastólica"
                                                wire:model.defer="diastolica_izquierda">
                                        </div>
                                    </div>

                                    {{-- Frecuencia cardiaca --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Frecuencia cardiaca (lpm)
                                        </label>
                                        <input type="number" class="form-control"
                                            wire:model.defer="frecuencia_cardiaca">
                                    </div>


                                    {{-- Botón --}}

                                </div>
                                <br>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary" wire:click="guardar">
                                        Agregar Signos
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- HISTORIAL --}}
                        <div class="card shadow-sm border-0">
                            <div class="card-body">

                                <h6 class="fw-semibold mb-3 text-secondary">
                                    📊 Historial de registros
                                </h6>

                                <table class="table table-sm table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Brazo derecho</th>
                                            <th>Brazo izquierdo</th>
                                            <th>FC</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($signos as $sv)
                                            <tr>
                                                <td class="text-muted">
                                                    {{ DateUtil::getFechaSimple($sv->fecha_signo) }}
                                                </td>
                                                <td class="text-muted">
                                                    {{ DateUtil::getHora($sv->fecha_signo) }}
                                                </td>
                                                <td>
                                                    {{ $sv->sistolica_derecha }}/{{ $sv->diastolica_derecha }}
                                                </td>
                                                <td>
                                                    {{ $sv->sistolica_izquierda }}/{{ $sv->diastolica_izquierda }}
                                                </td>
                                                <td>
                                                    {{ $sv->frecuencia_cardiaca }} lpm
                                                </td>
                                                <td class="text-center "><a href="javascript:void()"
                                                        wire:click='eliminar_signo({{ $sv->id_signo }})'><i
                                                            class="fas fa-trash text-dark"></i></a></td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    No hay registros aún
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>

                            <div class="card shadow-sm border-0 mt-4">
                                <div class="card-body">

                                    <h6 class="fw-semibold text-secondary mb-3">
                                        📈 Evolución de Presión Arterial
                                    </h6>

                                    <div wire:ignore>
                                        <canvas id="graficoPresion" height="90"
                                            data-datos='@json($datos)'>
                                        </canvas>
                                    </div>

                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="imprimirGrafico()">
                                🖨️ Imprimir gráfico
                            </button>


                        </div>

                    </div>


                </div>

            </div>
        </div>

    </div>
</div>
