<div wire:ignore.self class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Buscar servicio</h5>
        <button type="button" class="btn-close close" data-bs-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>

            <div class="modal-body">

                <input type="text"
                       class="form-control mb-3"
                       placeholder="Escriba el nombre del servicio"
                        wire:model.live="buscar">

                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Servicio</th>
                            <th>Tipo</th>
                            <th>Subtipo</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultados as $r)
                            <tr style="cursor:pointer"
                                wire:click="seleccionar({{ $r->id_servicio }})"
                                data-bs-dismiss="modal">
                                <td>{{ $r->nombre_servicio }}</td>
                                <td>{{ $r->nombre_tipo_servicio }}</td>
                                <td>{{ $r->nombre_subtipo_servicio }}</td>
                                <td>{{ $r->precio_servicio }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Sin resultados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>
