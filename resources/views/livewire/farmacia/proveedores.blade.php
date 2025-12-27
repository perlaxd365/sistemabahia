<div>
    <div>

        <!-- ===== CABECERA ===== -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="text-clinico mb-0">Proveedores</h5>
                <small class="text-muted">Gestión de proveedores farmacéuticos</small>
            </div>
        </div>

        <div class="row">

            <!-- ===== FORMULARIO ===== -->
            <div class="col-md-4">
                <div class="card card-clinica shadow-sm">
                    <div class="card-header bg-white">
                        <strong class="text-clinico">Registro de Proveedor</strong>
                    </div>

                    <div class="card-body">

                        <div class="mb-2">
                            <label class="form-label">Razón Social</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="razon_social">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">RUC</label>
                            <input type="text" class="form-control form-control-sm" maxlength="12" wire:model.defer="ruc">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="contacto">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="telefono">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-sm" wire:model.defer="email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control form-control-sm" rows="2" wire:model.defer="direccion"></textarea>
                        </div>

                        <div class="text-end">
                            @if ($edicion)
                                
                            <button wire:click="actualizar" wire:loading.attr="disabled" class="btn btn-clinico btn-sm"
                                type="button"> <i class="fa fa-edit"></i> <i wire:target="actualizar"
                                    wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Actualizar
                                Proveedor</button>
                            @else
                                
                            <button wire:click="agregar" wire:loading.attr="disabled" class="btn btn-clinico btn-sm"
                                type="button"> <i class="fa fa-plus-circle"></i> <i wire:target="agregar"
                                    wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Agregar
                                Proveedor</button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <!-- ===== LISTADO ===== -->
            <div class="col-md-8">
                <div class="card card-clinica shadow-sm">
                    <div class="card-body p-0">

                        <div class="row container">
                            <div class="col-11">
                                <strong class="text-clinico">Ingresar Búsqueda <br></strong>
                                <input type="text" wire:model.live.debounce.200ms='search'
                                    class="form-control form-control-sm" placeholder="Buscador" />
                            </div>
                            <div class=" col-1 input-group-append">
                                <i wire:target="search" wire:loading.class="fa fa-spinner fa-spin"
                                    aria-hidden="true"></i>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-clinica mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Razón Social</th>
                                        <th>RUC</th>
                                        <th>Contacto</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($proveedores as $prov)
                                        <tr>
                                            <td>{{ $prov->id }}</td>
                                            <td class="fw-semibold">{{ $prov->razon_social }}</td>
                                            <td>{{ $prov->ruc }}</td>
                                            <td>{{ $prov->contacto }}</td>
                                            <td>{{ $prov->telefono }}</td>
                                            <td>
                                                @if ($prov->estado)
                                                    <span class="badge bg-success-subtle text-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="javascrip:void(0)"
                                                    wire:click="editar({{ $prov->id_proveedor }})"
                                                    class="text-dark"><u>Editar</u></a>
                                                    
                                                @if ($prov->estado)
                                                <a href="javascrip:void(0)"
                                                    wire:click="eliminar({{ $prov->id_proveedor }})"
                                                    class="text-danger"><u>Eliminar</u></a>
                                                @else
                                                <a href="javascrip:void(0)"
                                                    wire:click="habilitar({{ $prov->id_proveedor }})"
                                                    class="text-success"><u>Habilitar</u></a>
                                                @endif

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay proveedores registrados
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            <div wire:ignore.self class="card-footer text-right">
                                {{ $proveedores->links(data: ['scrollTo' => false]) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
