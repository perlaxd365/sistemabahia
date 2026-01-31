<div class="card shadow-sm border-0">
    <div class="card-body p-3">

        {{-- HEADER --}}
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-capsule-pill me-2 text-primary fs-5"></i>
            <h6 class="mb-0 fw-semibold">Salida interna de farmacia</h6>
        </div>

        {{-- BUSCADOR --}}
        <div class="mb-2 position-relative">
            <label class="form-label small text-muted mb-1">Medicamento</label>
            <input type="text" class="form-control form-control-sm" placeholder="Buscar por nombre o código..."
                wire:model.live="buscar" autocomplete="off">

                @error('id_medicamento')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            @if (!empty($medicamentos))
                <div class="list-group position-absolute w-100 shadow-sm mt-1"
                    style="z-index:1050; max-height:240px; overflow-y:auto">

                    @foreach ($medicamentos as $med)
                        <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-1"
                            wire:click="seleccionarMedicamento('{{ $med->id_medicamento }}','{{ $med->nombre }}')">
                            <div>
                                <div class="fw-semibold">{{ $med->nombre }}</div>
                                <small class="text-muted">
                                    {{ $med->presentacion }} | {{ $med->concentracion }}
                                </small>
                            </div>
                            <span class="badge {{ $med->stock <= 2 ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $med->stock }}
                            </span>
                        </button>
                    @endforeach

                </div>
            @endif
        </div>

        {{-- DETALLE MEDICAMENTO --}}
        @if ($medicamentoSeleccionado)
            <div class="card border-0 shadow-sm bg-light mt-3">
                <div class="card-body p-3">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-semibold mb-0">
                                {{ $medicamentoSeleccionado->nombre }}
                            </h6>
                            <small class="text-muted">
                                {{ $medicamentoSeleccionado->marca }}
                            </small>
                        </div>

                        <span
                            class="badge 
                        {{ $medicamentoSeleccionado->estado === 'ACTIVO' ? 'bg-success' : 'bg-danger' }}">
                            {{ $medicamentoSeleccionado->estado }}
                        </span>
                    </div>

                    <hr class="my-2">

                    <div class="row g-2 small">
                        <div class="col-md-4">
                            <strong>Presentación</strong><br>
                            {{ $medicamentoSeleccionado->presentacion }}
                        </div>

                        <div class="col-md-4">
                            <strong>Concentración</strong><br>
                            {{ $medicamentoSeleccionado->concentracion }}
                        </div>

                        <div class="col-md-4">
                            <strong>Lote</strong><br>
                            {{ $medicamentoSeleccionado->lote ?? '—' }}
                        </div>

                        <div class="col-md-4">
                            <strong>Stock disponible</strong><br>
                            <span
                                class="fw-semibold 
                            {{ $medicamentoSeleccionado->stock <= 2 ? 'text-danger' : 'text-success' }}">
                                {{ $medicamentoSeleccionado->stock }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <strong>Vencimiento</strong><br>
                            <span class="{{ $this->medicamentoVencido() ? 'text-danger' : 'text-muted' }}">
                                {{ $medicamentoSeleccionado->fecha_vencimiento }}
                            </span>
                        </div>

                        @if ($this->medicamentoVencido())
                            <div class="alert alert-danger py-1 mt-2 small mb-0">
                                🚫 Medicamento vencido
                            </div>
                        @endif

                        <div class="col-md-4">
                            <strong>Precio referencial</strong><br>
                            <span class="text-muted">
                                S/ {{ number_format($medicamentoSeleccionado->precio_venta, 2) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        {{-- FORMULARIO --}}
        <div class="row g-2 mt-3">
            <div class="col-3">
                <label class="form-label small mb-1">Cantidad</label>
                <input type="number" step="0.01" class="form-control form-control-sm" wire:model="cantidad">
            </div>

            <div class="col-4">
                <label class="form-label small mb-1">Motivo</label>
                <select class="form-control form-control-sm" wire:model="motivo">
                    <option value="">Seleccionar</option>
                    <option value="uso_atencion">Uso atención</option>
                    <option value="consumo_area">Consumo área</option>
                    <option value="emergencia">Emergencia</option>
                    <option value="descarte">Descarte</option>
                    <option value="ajuste">Ajuste</option>
                </select>
                @error('motivo')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="col-5">
                <label class="form-label small mb-1">Área</label>
                <select class="form-control form-control-sm" wire:model="area">
                    <option value="">Seleccionar</option>
                    <option value="TOPICO">Tópico</option>
                    <option value="EMERGENCIA">Emergencia</option>
                    <option value="QUIROFANO">Quirófano</option>
                    <option value="TRIAJE">Triaje</option>
                    <option value="HOSPITALIZACION">Hospitalización</option>
                    <option value="CONSULTORIO">Consultorio</option>
                </select>
                @error('area')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
        </div>

        {{-- OBSERVACIÓN --}}
        <div class="mt-2">
            <label class="form-label small mb-1">Observación</label>
            <textarea class="form-control form-control-sm" rows="2" wire:model="observacion"></textarea>
        </div>

        {{-- ACCIÓN --}}
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-sm btn-primary px-4" wire:click="guardar">
                Registrar salida
            </button>
        </div>

    </div>

    <div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-3">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-list-ul me-1"></i> Salidas internas registradas
            </h6>

            <input type="text"
                   class="form-control form-control-sm w-25"
                   placeholder="Buscar medicamento..."
                   wire:model.live.500ms="buscar_lista">
        </div>

        {{-- TABLA --}}
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Fecha</th>
                        <th>Medicamento</th>
                        <th class="text-center">Cant.</th>
                        <th>Área</th>
                        <th>Motivo</th>
                        <th>Usuario</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>

                <tbody class="small">
                    @forelse ($salidas as $row)
                        <tr>
                            <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $row->medicamento->nombre }}
                                </div>
                                <small class="text-muted">
                                    {{ $row->medicamento->presentacion }}
                                </small>
                            </td>

                            <td class="text-center text-danger fw-semibold">
                                -{{ $row->cantidad }}
                            </td>

                            <td>{{ $row->descripcion }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ strtoupper($row->descripcion) }}
                                </span>
                            </td>

                            <td>
                                {{ $row->user->name ?? '—' }}
                            </td>

                            <td class="text-center">
                                {{ $row->stock_actual }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                No hay salidas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-2">
            {{ $salidas->links() }}
        </div>

    </div>
</div>

</div>
