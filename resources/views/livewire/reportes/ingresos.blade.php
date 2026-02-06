<div>
    <div>
        {{-- FILTROS --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Fecha</label>
                <input type="date" wire:model.live="fecha" class="form-control">
            </div>
        </div>

        {{-- TOTALES --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6>Total</h6>
                        <h4>S/ {{ number_format($total, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Servicio</th>
                            <th>Monto pagado</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ingresos as $item)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->fecha_emision)->format('H:i') }}
                                </td>

                                <td>
                                    {{ $item->atencion?->paciente?->name ?? '—' }}
                                </td>

                                <td>
                                    @foreach ($item->detalles as $det)
                                        <div>{{ $det->descripcion }}</div>
                                    @endforeach
                                </td>

                                <td>
                                    S/ {{ number_format($item->totalPagado(), 2) }}
                                </td>

                                <td>
                                    {{ $item->serie }}-{{ $item->numero }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay ingresos para esta fecha
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
