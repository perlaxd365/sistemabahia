<div class="container-fluid px-0">

    <div class="card border-0 shadow-sm mt-3">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-1 fw-semibold text-primary">
                Servicios para {{ $nombre_paciente }}
            </h6>
            <small class="text-muted">
                Registro de procedimientos realizados
            </small>
        </div>

        <div class="card-body">

            {{-- ================= BUSCAR SERVICIO ================= --}}
            <div class="mb-3">
                <button type="button"
                        class="btn btn-outline-primary btn-sm rounded-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalServicio">
                    Buscar servicio
                </button>

                <livewire:modal-buscar-servicio />

                @error('id_servicio')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>


            {{-- ================= SERVICIO SELECCIONADO ================= --}}
            @if ($servicioSeleccionado)

                <div class="border rounded-3 p-3 mb-4 bg-light">

                    <div class="mb-2">
                        <div class="fw-semibold text-dark">
                            {{ $servicioSeleccionado['nombre_servicio'] }}
                        </div>

                        <div class="text-muted small">
                            {{ $servicioSeleccionado['nombre_tipo_servicio'] }}
                            · {{ $servicioSeleccionado['nombre_subtipo_servicio'] }}
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">

                        {{-- Profesional --}}
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Profesional</label>
                            <select class="form-control form-select-sm rounded-3 shadow-none"
                                    wire:model="id_profesional">
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
                            <label class="form-label small text-muted">Cantidad</label>
                            <input type="number"
                                   min="1"
                                   wire:model.lazy="cantidad"
                                   class="form-control form-control-sm text-center rounded-3 shadow-none">
                        </div>

                        {{-- Precio --}}
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Precio</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-light">S/</span>
                                <input type="number"
                                       step="0.01"
                                       wire:model.lazy="precio_unitario"
                                       class="form-control text-end rounded-3 shadow-none border-light">
                            </div>
                        </div>

                        {{-- Botón --}}
                        <div class="col-md-3 text-end">
                            <button class="btn btn-success btn-sm px-4 rounded-3"
                                    type="button"
                                    wire:click="agregarServicio">
                                Agregar
                            </button>
                        </div>

                    </div>

                </div>

            @endif


            {{-- ================= TABLA SERVICIOS ================= --}}
            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">
                        <tr class="text-muted">
                            <th>Servicio</th>
                            <th>Profesional</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($atencion_servicios as $item)

                            <tr>

                                <td class="fw-semibold text-dark">
                                    {{ $item->nombre_servicio }}
                                </td>

                                <td>
                                    {{ $item->name ?? 'Sin profesional' }}
                                </td>

                                <td class="text-center">
                                    {{ $item->cantidad }}
                                </td>

                                <td class="text-end">
                                    S/ {{ number_format($item->precio_unitario, 2) }}
                                </td>

                                <td class="text-end fw-semibold text-primary">
                                    S/ {{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                                </td>

                                {{-- ESTADO --}}
                                <td class="text-center">
                                    @if(!$item->facturado)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            Por Facturar
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Facturado
                                        </span>
                                    @endif
                                </td>

                                {{-- ELIMINAR --}}
                                <td class="text-center">
                                    <button type="button"
                                            wire:click="eliminar_atencion_servicio({{ $item->id_atencion_servicio }})"
                                            class="btn btn-sm btn-light border rounded-circle"
                                            style="width:28px;height:28px;">
                                        <i class="fa fa-minus text-muted" style="font-size:7px;"></i>
                                    </button>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No se han registrado servicios
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>


            {{-- ================= TOTAL ================= --}}
            <div class="d-flex justify-content-end mt-3">

                <div class="text-end">
                    <div class="text-muted small">
                        Total servicios
                    </div>

                    <div class="fs-5 fw-bold text-primary">
                        S/ {{ number_format($totalervicios, 2) }}
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>