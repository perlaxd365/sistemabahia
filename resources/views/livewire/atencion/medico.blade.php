<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">👨‍⚕️ Asignar Médico</h5>
            </div>

            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($id_medico)
                    <div class="card border-success shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                    <img class="rounded-circle" src="{{ $atencion->medico->foto_url }}" width="50px" height="50px" alt="">
                                </div>

                                <div class="pl-2">
                                    <h6 class="mb-1 text-success fw-bold">
                                        Médico asignado
                                    </h6>

                                    <div class="fw-semibold">
                                        {{ $atencion->medico->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $atencion->medico->nombre_cargo ?? 'Médico tratante' }} / {{ $atencion->medico->nombre_especialidad ?? 'Sin Especialidad' }}
                                    </small>
                                </div>
                            </div>

                           

                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Médico</label>
                            <select class="form-control" wire:model="id_medico">
                                <option value="">-- Seleccione médico --</option>
                                @foreach ($medicos as $medico)
                                    <option value="{{ $medico->id }}">{{ $medico->name }} -
                                        {{ $medico->nombre_cargo }}
                                        ({{ $medico->especialidad_cargo ?? 'Sin especialidad' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_medico')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" wire:click='asignar' class="btn btn-primary">
                                Guardar
                            </button>
                        </div>
                @endif
            </div>

        </div>
    </div>
</div>

</div>
