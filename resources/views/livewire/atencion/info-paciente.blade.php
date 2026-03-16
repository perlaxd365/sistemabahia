<div>
    <style>
        .form-clinico {
            border-radius: 8px;
            border: 1px solid #dbe2ea;
            padding: 10px;
        }

        .form-clinico:focus {
            border-color: #0b3c5d;
            box-shadow: 0 0 0 0.15rem rgba(11, 60, 93, 0.15);
        }

        .btn-clinico {
            background: #0b3c5d;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 7px;
            font-weight: 500;
        }

        .historial-row {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .historial-row:hover {
            background: #f6f9fc;
        }

        .historial-link {
            opacity: 0.7;
        }

        .historial-row:hover .historial-link {
            opacity: 1;
        }
    </style>
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
                    <p class="dato-valor">{{ DateUtil::getFechaSimple($paciente->fecha_nacimiento) }}</p>
                </div>
                <div>
                    <p class="dato-label">Edad</p>
                    <p class="dato-valor">
                        {{ $edad ? $edad . ' años' : '—' }}
                    </p>
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
                    <p class="dato-label">Motivo de Atención</p>
                    <p class="dato-valor">
                        {!! $atencion->relato_consulta !!}
                    </p>
                </div>
                <div>
                    <p class="dato-label">Tipo de Atención</p>
                    <p class="dato-valor">
                        {{ $atencion->tipo_atencion_texto }}
                    </p>
                </div>

                <div>
                    <p class="dato-label">Responsable</p>
                    <p class="dato-valor">{{ $responsable }}</p>
                </div>

                <div>
                    <p class="dato-label">Estado</p>
                    <p class="dato-valor">
                        @if ($atencion->estado == 'PROCESO')
                            <span class="badge bg-success">Activa</span>
                        @elseif($atencion->estado == 'FINALIZADO')
                            <span class="badge bg-secondary">Finalizado</span>
                        @else
                            <span class="badge bg-warning">ANULADO</span>
                        @endif

                    </p>
                </div>

            </div>

        </div>


        <!-- ============================
          SECCIÓN: DATOS DE HISTORIAS
    ============================== -->

        <div class="card p-4">
            <div class="titulo-seccion">
                📖 Antecedentes Historia
            </div>

            <div class="card shadow-sm border-0 mt-3">

                <div class="card-header bg-white border-bottom py-2">
                    <span class="fw-semibold text-primary">
                        Historial de consultas
                    </span>
                    <span class="text-muted small ms-2">
                        (<b>{{ $consultas->count() }}</b>)
                    </span>
                </div>

                <div class="list-group list-group-flush">

    

    @if (!empty($consultas))


                    @foreach ($consultas as $consulta)
                        <div class="list-group-item px-3 py-2 historial-row"
                            wire:click="printConsulta({{ $consulta->id_consulta }})">

                            <div class="d-flex justify-content-between align-items-center">


                                <span class="fw-semibold text-dark">
                                    {{ UserUtil::getUserMedicoByAtencionID($consulta->id_atencion)?->name }}
                                </span>

                                <span class="text-muted small">
                                    {{ DateUtil::getFechaCompleta($consulta->fecha_consulta) }}
                                </span>
                                <span class="text-primary small historial-link">
                                    💾 Descargar
                                </span>

                            </div>

                        </div>
                    @endforeach
@endif
                </div>

            </div>
        </div>

    </div>
    <!-- ============================
        EDICIÓN DE ATENCIÓN
        ============================== -->
    <div>
        <div class="info-clinica">
            <div class="card p-4 mt-3">

                <div class="titulo-seccion">
                    ✏️ Actualizar Datos de Atención
                </div>

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="dato-label">Tipo de Atención</label>

                        <select wire:model="tipo_atencion" class="form-control form-clinico">

                            <option value="">Seleccionar...</option>
                            <option value="01">Consulta Externa</option>
                            <option value="02">Emergencia</option>
                            <option value="03">Hospitalización</option>
                            <option value="05">Procedimiento Ambulatorio</option>

                        </select>
                    </div>


                    <div class="col-md-12">
                        <label class="dato-label">Relato de Consulta</label>

                        <textarea wire:model="relato_consulta" rows="4" class="form-control form-clinico"
                            placeholder="Describir motivo o relato de consulta..."></textarea>
                    </div>
                </div>


                <div class="text-end mt-3">

                    <button wire:click="actualizarAtencion" type="button" class="btn btn-clinico">

                        💾 Guardar Cambios

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

</div>
