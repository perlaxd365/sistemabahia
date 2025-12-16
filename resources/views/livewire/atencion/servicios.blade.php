<div>
   <div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">
        🩺 Servicios de la Atención
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Servicio -->
            <div class="col-md-4">
                <label class="form-label">Servicio</label>
                <select class="form-select" wire:model="servicio_id">
                    <option value="">-- Seleccione --</option>
                    
                </select>
                @error('servicio_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Profesional -->
            <div class="col-md-3">
                <label class="form-label">Profesional</label>
                <select class="form-select" wire:model="profesional_id">
                    <option value="">-- Opcional --</option>
                   
                </select>
            </div>

            <!-- Cantidad -->
            <div class="col-md-2">
                <label class="form-label">Cantidad</label>
                <input type="number" min="1" class="form-control" wire:model="cantidad">
            </div>

            <!-- Precio -->
            <div class="col-md-2">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" wire:model="precio_unitario">
            </div>

            <!-- Botón -->
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100" wire:click="agregarServicio">
                    ➕
                </button>
            </div>

        </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Servicio</th>
                    <th>Profesional</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">P. Unit</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                    <tr>
                        <td>1</td>

                        <td>
                           csasaddsa d
                        </td>

                        <td class="text-center">1</td>

                        <td class="text-end">
                            S/ 123
                        </td>

                        <td class="text-end fw-bold">
                            S/ 123
                        </td>

                        <td class="text-center">
                            <span class="badge
                            ">
                                t
                            </span>
                        </td>

                        <td class="text-center">
                                <button class="btn btn-sm btn-outline-success"
                                        wire:click="marcarRealizado()">
                                    ✔
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                        wire:click="anularServicio()">
                                    ✖
                                </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            No hay servicios agregados
                        </td>
                    </tr>
            </tbody>
        </table>

    </div>

    <!-- Total -->
    <div class="card-footer text-end fw-bold">
        Total Servicios:
        <span class="fs-5">
            S/ 123
        </span>
    </div>
</div>

</div>

</div>
