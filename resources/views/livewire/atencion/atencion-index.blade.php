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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-9">
                                        <h5><b>Atenciones de <p class="text-danger">{{ $name }}</p></b> </h5>
                                    </div>
                                    <div class="col-3">

                                        <button wire:click="exportarPDF" wire:loading.attr="disabled"
                                            class="btn btn-secondary btn-sm" type="button"> <i
                                                class="fa fa-print"></i> <i wire:target="exportarPDF"
                                                wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                            Imprimir</button>
                                    </div>

                                    <table class="table table-responsive">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tipo de Atención</th>
                                                <th scope="col">Fecha Atención</th>
                                                <th scope="col">fecha finalización</th>
                                                <th scope="col">Ver</th>
                                                <th scope="col">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @if ($atenciones->count())
                                                <?php $count = 1; ?>
                                                @foreach ($atenciones as $atencion)
                                                    <tr>
                                                        <th scope="row">{{ $count++ }}</th>
                                                        <td>{!! $atencion->tipo_atencion !!}</td>
                                                        <td>{{ DateUtil::getFechaCompleta($atencion->fecha_inicio_atencion) }}
                                                        </td>
                                                        <td>{{ $atencion->fecha_fin_atencion ? DateUtil::getFechaCompleta($atencion->fecha_fin_atencion) : 'Atendiendose' }}
                                                        </td>
                                                        <td class="text-center">


                                                            @if ($atencion->estado_atencion == true)
                                                                <span class="badge bg-success">Activa</span>
                                                            @elseif($atencion->estado_atencion == false)
                                                                <span class="badge bg-secondary">Finalizada</span>
                                                            @else
                                                                <span class="badge bg-warning">Pendiente</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td><a
                                                                href="{{ route('atencion_general', ['id' => $atencion->id_atencion]) }}"><u>Ir
                                                                    a atención</u></a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <div class="card-body">
                                                            <strong>No se registraron atenciones.</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <button class="btn btn-sm nextBtn mt-3 " wire:click="prevStep"
                                type="button"><u>Atrás</u>
                            </button>
                            <button class="btn btn-sm nextBtn mt-3 float-right" wire:click="nextStep"
                                type="button"><u>Siguiente</u> → </button>
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
                                                <textarea id="editor" name="tipo_atencion" class="form-control" rows="10">{{ $tipo_atencion }}</textarea>

                                                @error('tipo_atencion')
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
