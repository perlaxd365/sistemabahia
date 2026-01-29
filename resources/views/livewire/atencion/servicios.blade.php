<div>
    <div class="info-clinica">


        <!-- ============================
          SECCIÓN: DATOS DEL PACIENTE
    ============================== -->
        <div class="card p-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-primary fw-semibold">
                    Servicios para <b>{{ $nombre_paciente }}</b>
                </h6>
                <small class="text-muted">
                    Registrar procedimientos realizados al paciente
                </small>
            </div>

            <div class=" border-0 shadow-sm mb-4">


                <div class="">

                    <!-- busqueda de servicio -->
                    <div class="col-md-4">
                        <label class="form-label text-muted small">
                            Servicio / Procedimiento
                        </label>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#modalServicio">
                            Buscar servicio
                        </button>

                        <livewire:modal-buscar-servicio />


                        @error('id_servicio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @if ($servicioSeleccionado)
                        <div class="card border-0 shadow mt-3" style="border-left:6px solid #0d6efd;">
                            <div class="card-body">

                                {{-- Header destacado --}}
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width:42px;height:42px;font-size:18px;">
                                        ✓
                                    </div>

                                    <div class="pl-3">
                                        <div class="fw-bold text-uppercase text-primary small">
                                            Servicio seleccionado
                                        </div>
                                        <div class="fw-bold fs-4">
                                            <b>{{ $servicioSeleccionado['nombre_servicio'] }}</b>
                                        </div>
                                    </div>
                                </div>

                                {{-- Badges de clasificación --}}
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark border me-2">
                                        {{ $servicioSeleccionado['nombre_tipo_servicio'] }}
                                    </span>

                                    <span class="badge bg-info text-dark">
                                        {{ $servicioSeleccionado['nombre_subtipo_servicio'] }}
                                    </span>
                                </div>

                                <hr>

                                <div class="row align-items-end">

                                    {{-- Profesional --}}
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">
                                            Profesional
                                        </label>
                                        <select class="form-control form-control-sm" wire:model="id_profesional">
                                            <option value="">Seleccione</option>
                                            <option value="0">Sin profesional</option>
                                            @foreach ($profesionales as $p)
                                                <option value="{{ $p->id }}">
                                                    {{ $p->name }} ({{ $p->nombre_cargo }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Cantidad --}}
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">
                                            Cantidad
                                        </label>
                                        <input type="number" min="1"
                                            class="form-control form-control-sm text-center @error('cantidad')border-danger @enderror"
                                            wire:model="cantidad">
                                    </div>

                                    {{-- Precio --}}
                                    <div class="col-md-3 ">
                                        <label class="form-label small text-muted">
                                            Precio unitario
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">S/</span>
                                            <input type="number" step="0.01"
                                                class="form-control text-end @error('precio_unitario')border-danger @enderror"
                                                wire:model="precio_unitario">
                                        </div>
                                    </div>

                                    {{-- Botón --}}
                                    <div class="col-md-3  d-grid pl-4">
                                        <button class="btn btn-success btn-sm" type="button"
                                            wire:click="agregarServicio">

                                            <i class="fa fa-plus me-1"></i>
                                             Agregar 
                                        </button>
                                    </div>
                                </div>

                                {{-- errores --}}
                                @error('cantidad')
                                    <div class="alert alert-danger mt-2 py-2">
                                        Falta rellenar cantidad
                                    </div>
                                @enderror

                                @error('precio_unitario')
                                    <div class="alert alert-danger mt-2 py-2">
                                        Falta rellenar precio unitario
                                    </div>
                                @enderror

                            </div>
                        </div>
                    @endif


                </div>
                <div class=" border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 text-primary fw-semibold">
                            Servicios registrados
                        </h6>
                    </div>

                    <div class="p-0 table-responsive">

                        <table class="table mb-0 align-middle ">
                            <thead class="table-light small">
                                <tr>
                                    <th>Servicio</th>
                                    <th>Profesional</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($atencion_servicios as $item)
                                    <tr>
                                        <td class="fw-medium">
                                            {{ $item->nombre_servicio }}
                                        </td>

                                        <td>
                                            {{ $item->name ?? '— Sin Profesional —' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $item->cantidad }}
                                        </td>

                                        <td class="text-end">
                                            S/ {{ number_format($item->precio_unitario, 2) }}

                                        <td class="text-end fw-semibold">
                                            S/ {{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill  @if ($item->estado == true) bg-warning @elseif($item->estado == false) bg-success @else bg-secondary @endif">
                                                {{ $item->estado ? 'Agregado' : 'Finalizado' }}
                                            </span>
                                        </td>
                                        <td class="text-center "><a href="javascript:void()"
                                                wire:click='eliminar_atencion_servicio({{ $item->id_atencion_servicio }})'><i
                                                    class="fas fa-trash text-dark"></i></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No se han registrado servicios
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>


                    <div class="col-md-12 ">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-2"></div>
                            <span class="text-muted small">Total de servicios</span>
                            <div class="fs-5 fw-bold text-primary">
                                <hr>
                                <b> S/ {{ number_format($totalervicios, 2) }}</b>
                            </div>
                            <div></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
