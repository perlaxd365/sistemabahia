<div>
    <div class="container">
        <div id="clasepadre">
            <div class="form-wrap">
                <form id="survey-form">
                    <h4>Datos de Proveedor</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Razón Social</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Ingresar Razon Social" wire:model.defer="razon_social" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="ruc"
                                    placeholder="Ingresar RUC">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contacto</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="contacto"
                                    placeholder="Ingresar Nombre del contacto">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="telefono"
                                    placeholder="Ingresar NRO de telefono">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control form-control-sm" wire:model.defer="email"
                                    placeholder="Ingresar correo del proveedor">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="direccion"
                                    placeholder="Ingresar dirección del proveedor">
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <div class="text-left">
                            <button wire:click="agregar" wire:loading.attr="disabled" class="btn btn-success btn-sm"
                                type="button"> <i class="fa fa-plus-circle"></i> <i wire:target="agregar"
                                    wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Agregar
                                Proveedor</button>

                            <button wire:click="default" wire:loading.attr="disabled" class="btn btn-secondary btn-sm"
                                type="button"> <i wire:target="default" wire:loading.class="fa fa-spinner fa-spin"
                                    aria-hidden="true"></i>Limpiar</button>

                        </div>
                    </div>




                    <div data-mdb-input-init class="form-outline mb-4">
                        <hr>
                        <label class="form-label" for="datatable-search-input">Listado de Proveedores</label>
                        <div class="row">
                            <div class="col-11">
                                <input type="text" wire:model.live.debounce.200ms='search' class="form-control"
                                    placeholder="Buscador" />
                            </div>
                            <div class=" col-1 input-group-append">
                                <i wire:target="search" wire:loading.class="fa fa-spinner fa-spin"
                                    aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <table wire:target="search" class="table table-striped table-hover table-responsive">
                        <thead class="thead-dark ">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Razón Social</th>
                                <th scope="col">Ruc</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Email</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--     @if ($proveedores->count()) --}}
                            <?php $count = 1; ?>
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <th scope="row">{{ $count++ }}</th>

                                    <td>{{ $proveedor->razon_social }}</td>
                                    <td>{{ $proveedor->ruc }}</td>
                                    <td>{{ $proveedor->contacto }}</td>
                                    <td>{{ $proveedor->email }}</td>
                                    <td>{{ $proveedor->telefono }}</td>
                                    <td class="text-center">
                                        @if ($proveedor->estado)
                                            <i class="fa fa-circle text-success font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i>
                                        @else
                                            <i class="fa fa-circle text-danger font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i>
                                        @endif

                                    </td>
                                    <td>
                                        @if ($proveedor->estado)
                                            <a class="text-danger" href="#"
                                                wire:click='delete_proveedor({{ $proveedor->id_proveedor }})'><u>Deshabilitar</u></a>
                                        @else
                                            <a class="text-success" href="#"
                                                wire:click='habilitar_proveedor({{ $proveedor->id_proveedor }})'><u>Habilitar</u></a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            {{--    @else
                                <div class="card-body">
                                    <strong>No se encontraron resultados</strong>
                                </div>
                            @endif --}}
                        </tbody>
                    </table>

                    {{--   <div wire:ignore.self class="card-footer text-right">
                    {{ $proveedor->links(data: ['scrollTo' => false]) }}
                </div> --}}


                </form>
            </div>
        </div>
    </div>
</div>
