<div>
    <div class="container py-4">

    {{-- PERFIL INSTITUCIONAL --}}
    <div class="card border-0 shadow-sm mb-4">

        {{-- CABECERA --}}
        <div class="card-header bg-white border-bottom d-flex align-items-center">
            <div class="me-3">
                <div class="rounded-circle border d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;overflow:hidden;">
                    @if($user->foto_url)
                        <img src="{{ asset($user->foto_url) }}" class="w-100 h-100 object-fit-cover">
                    @else
                        <span class="fw-semibold text-secondary fs-3">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex-grow-1 pl-3">
                <div class="fw-semibold text-dark">
                    {{ $user->name }}
                </div>
                <div class="text-muted small">
                    {{ $user->nombre_cargo }}
                    @if($user->especialidad_cargo)
                        · {{ $user->especialidad_cargo }}
                    @endif
                </div>
                <div class="text-muted small">
                    DNI {{ $user->dni }}
                </div>
            </div>

            <span class="badge {{ $user->estado_user ? 'bg-light text-success border border-success' : 'bg-light text-danger border border-danger' }}">
                {{ $user->estado_user ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- CUERPO --}}
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">
                        Datos personales
                    </h6>

                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted w-50">Correo</td>
                            <td class="fw-medium">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Teléfono</td>
                            <td class="fw-medium">{{ $user->telefono ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha de nacimiento</td>
                            <td class="fw-medium">{{ $user->fecha_nacimiento }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Género</td>
                            <td class="fw-medium">{{ ucfirst($user->genero) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dirección</td>
                            <td class="fw-medium">{{ $user->direccion ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">
                        Datos profesionales
                    </h6>

                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted w-50">Cargo</td>
                            <td class="fw-medium">{{ $user->nombre_cargo }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Especialidad</td>
                            <td class="fw-medium">{{ $user->especialidad_cargo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Colegiatura</td>
                            <td class="fw-medium">{{ $user->colegiatura_cargo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nivel de privilegio</td>
                            <td class="fw-medium">
                                {{ $user->privilegio_cargo }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- FIRMA --}}
            <div>
                <h6 class="text-uppercase text-muted small border-bottom pb-2 mb-3">
                    Firma digital
                </h6>

                @if($user->firma_url)
                    <div class="border rounded p-3 d-inline-block bg-light">
                        <img src="{{ asset($user->firma_url) }}" style="height:80px;">
                    </div>
                @else
                    <span class="text-muted small">
                        No registrada
                    </span>
                @endif
            </div>

        </div>
    </div>

</div>


</div>
