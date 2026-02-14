<div class="max-w-2xl mx-auto p-6">

    <div class="container">
        <div id="clasepadre">
            <div class="form-wrap">
                <form id="survey-form">
                    <div class="stepwizard">
                        <div class="stepwizard-row setup-panel">
                            <li class="stepwizard-step">
                                <a href="#step-1" type="button"
                                    class="btn btn-circle {{ $step != 1 ? 'btn-default' : 'btn-primary' }} pt-1"
                                    style="padding-left: 10px; padding-block: 30px">1</a>
                                <p>Paciente</p>
                            </li>
                            <li class="stepwizard-step">
                                <a href="#step-2" type="button"
                                    class="btn btn-circle {{ $step != 2 ? 'btn-default' : 'btn-warning' }} pt-1 "
                                    style="padding-left: 10px; padding-block: 30px">2</a>
                                <p>Servicio</p>
                            </li>
                            <div class="stepwizard-step">
                                <a href="#step-3" type="button"
                                    class="btn btn-circle {{ $step != 3 ? 'btn-default' : 'btn-success' }} pt-1 "
                                    style="padding-left: 10px; padding-block: 30px" disabled="disabled">3</a>
                                <p>Detalles</p>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    {{-- Paso 2 --}}
                    @if ($step == 1)
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <h5><b> Datos de Paciente</b> </h5>
                                    </div>

                                    <div class="col-9" style="float: initial">

                                        <div id="alerta"
                                            class="alert alert-success alert-dismissible fade show col-md-12"
                                            style="display: none" role="alert"><button id=""
                                                class="btn btn-circle btn-success pull-rigth pl-1 pt-2"
                                                style=""><i class="fa fa-check"></i></button>
                                            <strong>Dni encontrado</strong> Datos encontrados.
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div id="alerta-no-existe"
                                            class="alert alert-danger alert-dismissible fade show col-md-12"
                                            style="display: none" role="alert"><button id=""
                                                class="btn btn-circle btn-danger pull-rigth pl-1 pt-2" style=""><i
                                                    class="fa fa-info"></i></button>
                                            <strong>Dni no existe</strong> Datos no encontrados.
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                    </div>


                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <p>Buscar por DNI de paciente</p>
                                        <div class="input-group">
                                            <input type="text" wire:model='dni' maxlength="8"
                                                placeholder="Número de DNI" class="form-control" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-info" wire:loading.attr="disabled"
                                                    wire:click='buscarPaciente' type="button" id="button-addon2">
                                                    <i class="fa fa-search"></i> <i wire:target="buscarPaciente"
                                                        wire:loading.class="fa fa-spinner fa-spin"
                                                        aria-hidden="true"></i>
                                                    Buscar</button>
                                            </div>
                                        </div>
                                        @error('dni')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p>Nombres</p>
                                            <input type="text" wire:model='name' placeholder="Nombres de Paciente"
                                                disabled readonly class="form-control" required>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p>Teléfono</p>
                                            <input type="text" wire:model='telefono'
                                                placeholder="Telefono de paciente" disabled class="form-control"
                                                required>
                                            @error('telefono')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p>Fecha de nacimiento</p>
                                            <input type="text" wire:model='fecha_nacimiento'
                                                placeholder="Fecha de nacimiento" disabled class="form-control"
                                                required>
                                            @error('fecha_nacimiento')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-sm nextBtn mt-3 float-right" wire:click="nextStep"
                                    type="button"><u>Siguiente</u> → </button>

                            </div>
                        </div>
                    @endif
                    {{-- Paso 2 --}}
                    @if ($step == 2)
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Facturación</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($atenciones as $i => $atencion)
                                    <tr>

                                        <td>{{ $i + 1 }}</td>

                                        <td>
                                            <span class="fw-semibold">
                                                {!! $atencion->relato_atencion !!}
                                            </span>
                                        </td>

                                        <td>
                                            {{ DateUtil::getFechaHora($atencion->fecha_inicio_atencion) }}
                                        </td>

                                        <td>
                                            {{ $atencion->fecha_fin_atencion ? DateUtil::getFechaHora($atencion->fecha_fin_atencion) : '—' }}
                                        </td>

                                        {{-- ESTADO CLÍNICO --}}
                                        <td class="text-center">
                                            @if ($atencion->estado == 'PROCESO')
                                                <span class="badge bg-success-subtle text-success border">
                                                    ● En atención
                                                </span>
                                            @elseif ($atencion->estado == 'FINALIZADO')
                                                <span class="badge bg-primary-subtle text-primary border">
                                                    ✔ Finalizado
                                                </span>
                                            @elseif ($atencion->estado == 'ANULADO')
                                                <span class="badge bg-danger-subtle text-danger border">
                                                    ✖ Anulado
                                                </span>
                                            @endif
                                        </td>

                                        {{-- FACTURACIÓN --}}
                                        <td class="text-center">

                                            @if ($atencion->comprobante)
                                                <span class="badge bg-info text-dark">
                                                    Facturado
                                                </span>
                                            @elseif ($atencion->pagado ?? false)
                                                <span class="badge bg-warning text-dark">
                                                    Cobrado
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    Pendiente
                                                </span>
                                            @endif

                                        </td>

                                        {{-- ACCIÓN --}}
                                        <td class="text-center">
                                            <a href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Ver
                                            </a>
                                        </td>

                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No se registraron atenciones
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                        <div class="flex justify-between mt-4">
                            <button class="btn btn-sm nextBtn mt-3 " wire:click="prevStep"
                                type="button"><u>Atrás</u>
                            </button>
                            <button class="btn btn-sm nextBtn mt-3 float-right" wire:click="nextStep"
                                type="button"><u>Nueva Atención</u> → </button>
                        </div>
                    @endif

                    {{-- Paso 3 --}}
                    @if ($step == 3)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <hr>
                                    <div class="col-md-12">
                                        <div wire:ignore>
                                            <div class="form-group">
                                                <div class="col-12">
                                                    <h5><b>Motivo de visita <p class="text-danger">{{ $name }}
                                                            </p></b>
                                                    </h5>
                                                </div>
                                                <select wire:model="tipo_atencion" class="form-control" required>
                                                    <option value="">Seleccionar</option>
                                                    <option value="01">Consulta Externa</option>
                                                    <option value="02">Emergencia</option>
                                                    <option value="03">Hospitalización</option>
                                                    <option value="05">Procedimiento Ambulatorio</option>
                                                </select>
                                                @error('tipo_atencion')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <br>
                                                <textarea id="editor" name="relato_consulta" class="form-control" rows="10">{{ $relato_consulta }}</textarea>

                                                @error('relato_consulta')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <button type="button" onclick="printEditor()" class="btn btn-sm btn-danger">Imprimir
                                    <i class="fa fa-print"></i></button>
                                <div class="flex justify-between mt-4">
                                    <button class="btn btn-sm nextBtn mt-3 " wire:click="prevStep"
                                        type="button"><u>Atrás</u>
                                    </button>
                                    <button class="btn btn-sm nextBtn mt-3 float-right" wire:click="nextStep"
                                        type="button"><u><b>Guardar Atención</b></u> ✔ </button>

                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>

</div>
