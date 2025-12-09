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
                    <div id="alerta" class="alert alert-success alert-dismissible fade show col-md-12"
                        style="display: none" role="alert"><button id=""
                            class="btn btn-circle btn-success pull-rigth pl-1 pt-2" style=""><i
                                class="fa fa-check"></i></button>
                        <strong>Dni encontrado</strong> Datos encontrados.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="alerta-no-existe" class="alert alert-danger alert-dismissible fade show col-md-12"
                        style="display: none" role="alert"><button id=""
                            class="btn btn-circle btn-danger pull-rigth pl-1 pt-2"  style=""><i
                                class="fa fa-info"></i></button>
                        <strong>Dni no existe</strong> Datos no encontrados.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span >&times;</span>
                        </button>
                    </div>
                    
    {{-- Paso 2 --}}
    @if ($step == 1)
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <br>
                            <h5><b> Datos de Estudiante</b> </h5>
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
                                                    wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
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
                                        <input type="text" wire:model='telefono' placeholder="Telefono de paciente"
                                            disabled class="form-control" required>
                                        @error('telefono')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p>Fecha de nacimiento</p>
                                        <input type="text" wire:model='fecha_nacimiento'
                                            placeholder="Fecha de nacimiento" disabled class="form-control" required>
                                        @error('fecha_nacimiento')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-sm nextBtn mt-3 " wire:click="Salir" type="button"><u>Cancelar</u> x
                            </button>
                            <button class="btn btn-sm nextBtn mt-3 float-right" wire:click="nextStep"
                                type="button"><u>Siguiente</u> → </button>

                        </div>
                    </div>
                    
    @endif
    {{-- Paso 2 --}}
    @if ($step == 2)
    
    @endif

                </form>
            </div>
        </div>
    </div>

    {{-- Paso 1 --}}
    @if ($step == 1)
        <h2 class="text-xl font-bold mb-3">1️⃣ Seleccionar Paciente</h2>

        <select wire:model="id_paciente" class="w-full border rounded p-2">
            <option value="">-- Seleccione un paciente --</option>
            @foreach ($pacientes as $p)
                <option value="{{ $p->id }}">{{ $p->nombre_completo }} - {{ $p->dni }}</option>
            @endforeach
        </select>

        <div class="flex justify-end mt-4">
            <button wire:click="nextStep" class="bg-blue-600 text-white px-4 py-2 rounded">
                Siguiente →
            </button>
        </div>
    @endif


    {{-- Paso 2 --}}
    @if ($step == 2)
        <h2 class="text-xl font-bold mb-3">2️⃣ Seleccionar Servicio</h2>

        <select wire:model="id_servicio" class="w-full border rounded p-2">
            <option value="">-- Seleccione un servicio --</option>
            @foreach ($servicios as $s)
                <option value="{{ $s->id_servicio }}">{{ $s->nombre_servicio }}</option>
            @endforeach
        </select>

        <div class="flex justify-between mt-4">
            <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2 rounded">
                ← Atrás
            </button>

            <button wire:click="nextStep" class="bg-blue-600 text-white px-4 py-2 rounded">
                Siguiente →
            </button>
        </div>
    @endif


    {{-- Paso 3 --}}
    @if ($step == 3)
        <h2 class="text-xl font-bold mb-3">3️⃣ Detalles de la Atención</h2>

        <textarea wire:model="motivo" placeholder="Motivo de la consulta" class="w-full border rounded p-2 mb-3"></textarea>

        <textarea wire:model="diagnostico" placeholder="Diagnóstico" class="w-full border rounded p-2 mb-3"></textarea>

        <textarea wire:model="notas" placeholder="Notas adicionales" class="w-full border rounded p-2 mb-3"></textarea>

        <div class="flex justify-between mt-4">
            <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2 rounded">
                ← Atrás
            </button>

            <button wire:click="nextStep" class="bg-blue-600 text-white px-4 py-2 rounded">
                Siguiente →
            </button>
        </div>
    @endif


    {{-- Paso 4 --}}
    @if ($step == 4)
        <h2 class="text-xl font-bold mb-3">4️⃣ Confirmación</h2>

        <div class="bg-gray-100 p-4 rounded">
            <p><strong>Paciente:</strong> {{ optional($pacientes->find($id_paciente))->name }}</p>
            <p><strong>Servicio:</strong> {{ optional($servicios->find($id_servicio))->nombre_servicio }}</p>
            <p><strong>Motivo:</strong> {{ $motivo }}</p>
            <p><strong>Diagnóstico:</strong> {{ $diagnostico }}</p>
        </div>

        <div class="flex justify-between mt-4">
            <button wire:click="prevStep" class="bg-gray-500 text-white px-4 py-2 rounded">
                ← Atrás
            </button>

            <button wire:click="guardar" class="bg-green-600 text-white px-4 py-2 rounded">
                Guardar Atención ✔
            </button>
        </div>
    @endif

</div>
