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

            <div class="card border-0 shadow-sm mb-4">


                <div class="card-body">

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
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body">

                                <div class="row align-items-center">

                                    {{-- Servicio --}}
                                    <div class="col-md-4">
                                        <div class="text-primary fw-semibold fs-5">
                                            {{ $servicioSeleccionado['nombre_servicio'] }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $servicioSeleccionado['nombre_tipo_servicio'] }}
                                            <span class="mx-1">•</span>
                                            {{ $servicioSeleccionado['nombre_subtipo_servicio'] }}
                                        </div>
                                        {{-- Separador --}}
                                        <hr class="my-2">

                                        {{-- Etiqueta --}}
                                        <div class="text-secondary small">
                                            Servicio seleccionado
                                        </div>
                                    </div>

                                    {{-- Profesional --}}
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted mb-1">
                                            Profesional
                                        </label>
                                        <select class="form-control form-control-sm" wire:model="id_profesional">
                                            <option value="">Seleccione</option>
                                            <option value="0">Sin profesional</option>
                                            @foreach ($profesionales as $p)
                                                <option value="{{ $p->id }}">
                                                    {{ $p->name }} ({{ $p->nombre_cargo }})
                                                    ({{ $p->privilegio_cargo }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Cantidad --}}
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted mb-1">
                                            Cant.
                                        </label>
                                        <input type="number" min="1"
                                            class="form-control form-control-sm @error('cantidad')border-danger @enderror text-center"
                                            wire:model="cantidad">


                                    </div>

                                    {{-- Precio --}}
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted mb-1">
                                            Precio
                                        </label>
                                        <input type="number" step="0.01"
                                            class="form-control form-control-sm @error('precio_unitario')border-danger @enderror  text-end"
                                            value="" wire:model="precio_unitario">

                                    </div>

                                    {{-- Botón --}}
                                    <div class="col-md-1 text-end">
                                        <label class="form-label small text-muted mb-1 d-block">
                                            &nbsp;
                                        </label>
                                        <button class="btn btn-primary btn-sm w-100" type="button"
                                            wire:click="agregarServicio">
                                            +
                                        </button>
                                    </div>
                                </div>

                                @error('cantidad')
                                    <hr>
                                    <small class="text-danger"> Falta rellenar cantidad</small>
                                    <hr>
                                @enderror
                                @error('precio_unitario')
                                    <hr>
                                    <small class="text-danger"> Falta rellenar precio unitario</small>
                                    <hr>
                                @enderror
                            </div>
                        </div>
                    @endif

                </div>
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 text-primary fw-semibold">
                            Servicios registrados
                        </h6>
                    </div>

                    <div class="card-body p-0 table-responsive">

                        <table class="table mb-0 align-middle ">
                            <thead class="table-light small">
                                <tr>
                                    <th>Servicio</th>
                                    <th>Profesional</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($atencion_servicios as $item)
                                    <tr>
                                        <td class="fw-medium">
                                            {{ $item->nombre_servicio }}
                                        </td>

                                        <td>
                                            {{ $item->name }}
                                        </td>

                                        <td class="text-center">
                                            {{ $item->cantidad }}
                                        </td>

                                        <td class="text-end">
                                            {{ $item->precio_unitario }}

                                        <td class="text-end fw-semibold">
                                            {{ $item->cantidad * $item->precio_unitario }}
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill
                                @if ($item->estado == true) bg-warning
                                @elseif($item->estado == false) bg-success
                                @else bg-secondary @endif">
                                                {{ $item->estado?'Agregado':'Finalizado' }}
                                            </span>
                                        </td>
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
                            <div class="col-1"></div>
                            <span class="text-muted small">Total de servicios</span>
                            <div class="fs-5 fw-bold text-primary">
                                <hr>
                                <b>S/ 323</b>
                            </div>
                            <div></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
