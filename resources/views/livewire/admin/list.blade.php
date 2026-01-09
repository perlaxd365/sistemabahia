<div class="container">
    <div id="clasepadre">
        <div class="form-wrap">
            <form id="survey-form">
                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="datatable-search-input">Listado</label>
                    <hr>
                    <div class="row">
                        <div class="col-11">
                            <input type="text" wire:model.live.debounce.200ms='search' class="form-control"
                                placeholder="Buscador" />
                        </div>
                        <div class=" col-1 input-group-append">
                            <i wire:target="search" wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <table wire:target="search" class="table table-striped table-hover table-responsive">
                    <thead class="thead-dark ">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">Dni</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($lista_usuarios->count())
                            <?php $count = 1; ?>
                            @foreach ($lista_usuarios as $usuario)
                                <tr>
                                    <th scope="row">{{ $count++ }}</th>
                                    <td class="border-top-0 px-1 py-1">
                                        <div class="d-flex no-block align-items-center">
                                            <div class="mr-3">
                                                <img src="{{ $usuario->foto_url }}" alt="user"
                                                    class="rounded-circle" width="45" height="45" />
                                            </div>
                                            <div class="">
                                                <p class="text-dark mb-0  font-weight-medium">
                                                    {{ $usuario->name }}
                                                </p>
                                                <span class="text-muted font-14">
                                                    <b>{{ $usuario->nombre_cargo }}</b>
                                                </span>
                                            </div>
                                        </div>


                                    </td>

                                    <td>{{ $usuario->dni }}</td>
                                    <td>{{ $usuario->telefono }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td class="text-center">
                                        @if ($usuario->estado_user)
                                            <i class="fa fa-circle text-success font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i>
                                        @else
                                            <i class="fa fa-circle text-danger font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i>
                                        @endif

                                    </td>
                                    <td>
                                        <a class="text-success" href="#"
                                            wire:click='link_atencion({{ $usuario->id }})'><u>Atenciones</u></a>
                                        <a href="#" wire:click='edit({{ $usuario->id }})'><u>Editar</u></a>
                                        &nbsp;

                                        @if ($usuario->estado_user)
                                            <a class="text-danger" href="#"
                                                wire:click='desactivar_usuario({{ $usuario->id }})'><u>Deshabilitar</u></a>
                                        @else
                                            <a class="text-success" href="#"
                                                wire:click='activar_usuario({{ $usuario->id }})'><u>Habilitar</u></a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <div class="card-body">
                                <strong>No se encontraron resultados</strong>
                            </div>
                        @endif
                    </tbody>
                </table>
            </form>

            <div wire:ignore.self class="card-footer text-right">
                {{ $lista_usuarios->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
