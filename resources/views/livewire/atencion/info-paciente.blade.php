<div>
    <div class="info-clinica">


        <!-- ============================
          SECCIÓN: DATOS DEL PACIENTE
    ============================== -->
        <div class="card p-4">
            <div class="titulo-seccion">
                👤 Información del Paciente
            </div>

            <div class="info-grid">

                <div>
                    <p class="dato-label">Nombres y Apellidos</p>
                    <p class="dato-valor">{{ $paciente->name }}</p>
                </div>

                <div>
                    <p class="dato-label">Dni</p>
                    <p class="dato-valor">{{ $paciente->dni }}</p>
                </div>
                <div>
                    <p class="dato-label">Teléfono</p>
                    <p class="dato-valor">{{ $paciente->telefono }}</p>
                </div>

                <div>
                    <p class="dato-label">Dirección</p>
                    <p class="dato-valor">{{ $paciente->direccion }}</p>
                </div>

                <div>
                    <p class="dato-label">Email</p>
                    <p class="dato-valor">{{ $paciente->email }}</p>
                </div>

                <div>
                    <p class="dato-label">Fecha de Nacimiento</p>
                    <p class="dato-valor">{{ DateUtil::getFechaSimple($paciente->fecha_nacimiento )}}</p>
                </div>

                <div>
                    <p class="dato-label">Género</p>
                    <p class="dato-valor">{{ $paciente->genero }}</p>
                </div>

            </div>
        </div>

        <!-- ============================
          SECCIÓN: DATOS DE LA ATENCIÓN
    ============================== -->

        <div class="card p-4">
            <div class="titulo-seccion">
                🩺 Información de la Atención
            </div>

            <div class="info-grid">

                <div>
                    <p class="dato-label">Nro. de Historia</p>
                    <p class="dato-valor">{{ $historia->nro_historia }}</p>
                </div>

                <div>
                    <p class="dato-label">Fecha de Inicio</p>
                    <p class="dato-valor">{{ $atencion->fecha_inicio_atencion }}</p>
                </div>

                <div>
                    <p class="dato-label">Fecha de Fin</p>
                    <p class="dato-valor">
                        {{ $atencion->fecha_fin_atencion ?? '— En curso —' }}
                    </p>
                </div>

                <div>
                    <p class="dato-label">Responsable</p>
                    <p class="dato-valor">{{ $responsable }}</p>
                </div>

                <div>
                    <p class="dato-label">Estado</p>
                    <p class="dato-valor">
                        @if ($atencion->estado_atencion == true)
                            <span class="badge bg-success">Activa</span>
                        @elseif($atencion->estado_atencion == false)
                            <span class="badge bg-secondary">Finalizada</span>
                        @else
                            <span class="badge bg-warning">Pendiente</span>
                        @endif
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>
