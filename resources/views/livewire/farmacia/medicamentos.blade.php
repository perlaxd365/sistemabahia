<div>
    <div>

        <!-- ===== CABECERA ===== -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="text-clinico mb-0">Medicamentos</h5>
                <small class="text-muted">Gestión de inventario farmacéutico</small>
            </div>
        </div>

        <div class="row">
            @can('editar-farmacia')
                <!-- ===== FORMULARIO ===== -->
                <div class="col-md-4">
                    <div class="card card-clinica shadow-sm">
                        <div class="card-header bg-white">
                            <strong class="text-clinico">Registro de Medicamento</strong>
                        </div>

                        <div class="card-body">

                            <div class="mb-2">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="nombre">
                                @error('nombre')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Presentación</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Tableta, jarabe, ampolla" wire:model.defer="presentacion">
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Concentración</label>
                                <input type="text" class="form-control form-control-sm" placeholder="500 mg, 5 mg/ml"
                                    wire:model.defer="concentracion">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Precio Venta</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" min="0"
                                        step="0.01" wire:model.defer="precio_venta">
                                    @error('precio_venta')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Stock Inicial</label>
                                    <input type="number" class="form-control form-control-sm" min="0" step="0.01"
                                        wire:model.defer="stock">
                                    @error('stock')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Marca / Laboratorio</label>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer="marca">
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="form-label">Fecha Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm"
                                        wire:model.defer="fecha_vencimiento">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                @if ($edicion)
                                    <button wire:click="actualizar" wire:loading.attr="disabled"
                                        class="btn btn-clinico btn-sm">
                                        <i class="fa fa-edit"></i> <i wire:target="actualizar"
                                            wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Actualizar
                                        Medicamento
                                    </button>
                                @else
                                    <button wire:click="agregar" wire:loading.attr="disabled"
                                        class="btn btn-clinico btn-sm">
                                        <i class="fa fa-plus-circle"></i> <i wire:target="agregar"
                                            wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Guardar
                                    </button>
                                @endif


                            </div>

                        </div>
                    </div>
                </div>
            @endcan
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
                                        <th>Medicamento</th>
                                        <th>Presentación</th>
                                        <th>Marca/Laboratorio</th>
                                        <th>Stock</th>
                                        <th>Precio</th>
                                        <th>Vencimiento</th>
                                        <th>Estado</th>
                                        @can('editar-farmacia')
                                            <th class="text-end">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($medicamentos as $med)
                                        <tr>
                                            <td>{{ $med->id_medicamento }}</td>
                                            <td class="fw-semibold">{{ $med->nombre }}</td>
                                            <td>{{ $med->presentacion }}</td>
                                            <td>{{ $med->marca }}</td>
                                            <td>
                                                @if ($med->stock <= 5)
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        {{ $med->stock }}
                                                    </span>
                                                @else
                                                    {{ $med->stock }}
                                                @endif
                                            </td>
                                            <td>S/ {{ number_format($med->precio_venta, 2) }}</td>
                                            <td>{{ $med->fecha_vencimiento }}</td>
                                            <td>
                                                @if ($med->estado)
                                                    <span class="badge bg-success-subtle text-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            @can('editar-farmacia')
                                                <td class="text-end">
                                                    <a href="javascrip:void(0)"
                                                        wire:click="editar({{ $med->id_medicamento }})"
                                                        class="text-dark"><u>Editar</u></a>

                                                    @if ($med->estado)
                                                        <a href="javascrip:void(0)"
                                                            wire:click="eliminar({{ $med->id_medicamento }})"
                                                            class="text-danger"><u>Eliminar</u></a>
                                                    @else
                                                        <a href="javascrip:void(0)"
                                                            wire:click="habilitar({{ $med->id_medicamento }})"
                                                            class="text-success"><u>Habilitar</u></a>
                                                    @endif
                                                @endcan
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay medicamentos registrados
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div wire:ignore.self class="card-footer text-right">
                                {{ $medicamentos->links(data: ['scrollTo' => false]) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
