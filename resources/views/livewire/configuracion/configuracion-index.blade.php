<div class="container">
    <div id="clasepadre">
        <div class="form-wrap">
            <form id="survey-form">
                    <ul  class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $tabActivo === 'servicios' ? 'active' : '' }}" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Servicios
                                <b>({{ count($lista_servicios) }})</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tabActivo === 'subtipos' ? 'active' : '' }}" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Sub Tipos de
                                Servicios
                                <b>({{ count($lista_sub_tipos_servicios) }})</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tabActivo === 'tipos' ? 'active' : '' }}" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                                role="tab" aria-controls="pills-contact" aria-selected="false"> Tipos de Servicios
                                <b>({{ count($lista_tipos_servicios) }})</b></a>
                        </li>
                    </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div wire:ignore.self class="tab-pane fade show {{ $tabActivo === 'servicios' ? 'active' : '' }}" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        {{-- inicio de servicios --}}
                        <h4>Servicios</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="name">Nombre de Sub Tipo</label>
                                    <select wire:model="id_subtipo_servicio" class="form-control" id=""
                                        required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($lista_sub_tipos_servicios as $sub_tipo_servicios)
                                            <option value="{{ $sub_tipo_servicios->id_subtipo_servicio }}">
                                                {{ $sub_tipo_servicios->nombre_subtipo_servicio }} ({{$sub_tipo_servicios->nombre_tipo_servicio}})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_subtipo_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="nombre_servicio">Nombre de Servicio</label>
                                    <input type="text" wire:model='nombre_servicio' placeholder="Nombres completos"
                                        class="form-control" required>
                                    @error('nombre_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="precio_servicio">Precio de Servicio</label>
                                    <input type="number" wire:model='precio_servicio' placeholder="Precio de servicio"
                                        class="form-control" required>
                                    @error('precio_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="agregar_servicio"></label>
                                    <button wire:click="agregar_servicio" wire:loading.attr="disabled"
                                        class="btn btn-outline-dark" type="button">
                                        <i class="fa fa-plus-circle"></i> <i wire:target="agregar_servicio"
                                            wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        Guardar</button>
                                </div>
                            </div>

                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre Servicio</th>
                                        <th scope="col">Nombre Tipo Servicio</th>
                                        <th scope="col">Precio de Servicio</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($lista_servicios->count())
                                        <?php $count = 1; ?>
                                        @foreach ($lista_servicios as $servicios)
                                            <tr>
                                                <th scope="row">{{ $count++ }}</th>
                                                <td>{{ $servicios->nombre_servicio }}</td>
                                                <td>{{ $servicios->nombre_tipo_servicio }} /
                                                    {{ $servicios->nombre_subtipo_servicio }}</td>
                                                <td>{{ $servicios->precio_servicio }}</td>
                                                <td>
                                                    <a href="#" class="text-dark"
                                                        wire:click='delete_servicio({{ $servicios->id_servicio }})'><u><button>x</button></u></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="card-body">
                                                <strong>No se encontraron resultados</strong>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- fin de servicios --}}


                    </div>
                    <div wire:ignore.self class="tab-pane fade {{ $tabActivo === 'subtipos' ? 'active' : '' }}" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        {{-- Inicio de sub tipos de servicios --}}
                        <h4>Sub tipos de servicios</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="name">Nombre de Tipo</label>
                                    <select wire:model="id_tipo_servicio" class="form-control" id=""
                                        required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($lista_tipos_servicios as $tipo_servicios)
                                            <option value="{{ $tipo_servicios->id_tipo_servicio }}">
                                                {{ $tipo_servicios->nombre_tipo_servicio }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_tipo_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="nombre_subtipo_servicio">Nombre de sub tipo</label>
                                    <input type="text" wire:model='nombre_subtipo_servicio'
                                        placeholder="Nombres de sub tipo" class="form-control" >
                                    @error('nombre_subtipo_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="name-label" for="nombre_tipo_servicio"><br></label>
                                    <button wire:click="agregar_subtipo_servicio" wire:loading.attr="disabled"
                                        class="btn btn-outline-dark" type="button">
                                        <i class="fa fa-plus-circle"></i> <i wire:target="agregar_subtipo_servicio"
                                            wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        Guardar</button>
                                </div>
                            </div>

                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre de Sub Tipo de Servicio</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($lista_sub_tipos_servicios->count())
                                        <?php $count = 1; ?>
                                        @foreach ($lista_sub_tipos_servicios as $sub_tipo_servicios)
                                            <tr>
                                                <th scope="row">{{ $count++ }}</th>
                                                <td>{{ $sub_tipo_servicios->nombre_subtipo_servicio }} / {{ $sub_tipo_servicios->nombre_tipo_servicio }} </td>
                                                <td>
                                                    <a href="#" class="text-dark"
                                                        wire:click='delete_subtipo_servicio({{ $sub_tipo_servicios->id_subtipo_servicio }})'><u><button>x</button></u></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="card-body">
                                                <strong>No se encontraron resultados</strong>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- fin de sub tipos de servicios --}}


                    </div>
                    <div wire:ignore.self class="tab-pane fade {{ $tabActivo === 'tipos' ? 'active' : '' }}" id="pills-contact" role="tabpanel"
                        aria-labelledby="pills-contact-tab">



                        {{-- inicio de tipos de servicios --}}
                        <h4>Tipos de Servicios</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="name-label" for="nombre_tipo_servicio">Nombre de Tipo</label>
                                    <input type="text" wire:model='nombre_tipo_servicio'
                                        placeholder="Nombres de tipo de servicio" class="form-control">
                                    @error('nombre_tipo_servicio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="name-label" for="nombre_tipo_servicio"><br></label>
                                    <button wire:click="agregar_tipo_servicio" wire:loading.attr="disabled"
                                        class="btn btn-outline-dark" type="button">
                                        <i class="fa fa-plus-circle"></i> <i wire:target="agregar_tipo_servicio"
                                            wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        Guardar</button>
                                </div>
                            </div>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre de Tipo de Servicio</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($lista_tipos_servicios->count())
                                        <?php $count = 1; ?>
                                        @foreach ($lista_tipos_servicios as $tipo_servicios)
                                            <tr>
                                                <th scope="row">{{ $count++ }}</th>
                                                <td>{{ $tipo_servicios->nombre_tipo_servicio }}</td>
                                                <td>
                                                    <a href="#" class="text-dark"
                                                        wire:click='delete_tipo_servicio({{ $tipo_servicios->id_tipo_servicio }})'><u><button>x</button></u></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="card-body">
                                                <strong>No se encontraron resultados</strong>
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- fin de tipos de servicios --}}

                    </div>
                </div>









            </form>
        </div>
    </div>
</div>
