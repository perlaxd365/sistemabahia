<div>
    <style>
        body {
            background: #f7f9fb;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 13px;
        }

        .hc-card {
            background: #fff;
            border: 1px solid #dce3ea;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .hc-header {
            background: #f1f5f9;
            border-bottom: 1px solid #dce3ea;
            padding: 6px 12px;
            font-weight: 600;
            color: #0b3c5d;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .6px;
        }

        .hc-body {
            padding: 8px 12px;
        }

        label {
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 2px;
        }

        .form-control,
        .form-select {
            font-size: 12px;
            padding: 4px 6px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        textarea.form-control {
            resize: none;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .vital {
            text-align: center;
            font-weight: 600;
        }

        .btn-save {
            font-size: 13px;
            padding: 6px 20px;
            border-radius: 6px;
        }

        /* ===== BOTONES CLÍNICOS ===== */
        .btn-clinico {
            background-color: #0b3c5d;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(11, 60, 93, 0.25);
            transition: all 0.2s ease-in-out;
        }

        .btn-clinico:hover {
            background-color: #092f48;
            box-shadow: 0 5px 12px rgba(11, 60, 93, 0.35);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-clinico:active {
            transform: translateY(0);
            box-shadow: 0 3px 6px rgba(11, 60, 93, 0.25);
        }

        /* ===== BOTÓN IMPRIMIR ===== */
        .btn-imprimir {
            background-color: #ffffff;
            color: #0b3c5d;
            border: 1px solid #0b3c5d;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .btn-imprimir:hover {
            background-color: #0b3c5d;
            color: #ffffff;
        }

        /* Iconos */
        .btn-imprimir i,
        .btn-clinico i {
            margin-left: 6px;
        }
    </style>

    <div class="card border-0 shadow-sm mt-3">
        @if ($medico_responsable)
            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-3">
                    <div class="icon-clinico mr-2">
                        <i class="fa fa-notes-medical fa-lg"></i>
                    </div>

                    <div>
                        <div class="fw-semibold text-clinico">
                            Consulta Médica para <b>{{ $nombre_paciente }}</b>
                        </div>
                        <div class="small text-muted">
                            Registro histórico de medicamentos entregados en esta atención
                        </div>
                    </div>
                </div>

            </div>
            <br>

            <!-- MOTIVO DE CONSULTA -->
            <div class="hc-card">
                <div class="hc-header">Enfermedad Actual</div>
                <div class="hc-body row g-2">
                    <div class="col-md-5">
                        <label>Molestia principal</label>
                        <input type="text" class="form-control" wire:model="molestia_consulta">
                    </div>
                    <div class="col-md-2">
                        <label>Tiempo</label>
                        <input type="text" class="form-control" wire:model="tiempo_consulta">
                    </div>
                    <div class="col-md-2">
                        <label>Inicio</label>
                        <select class="form-control" wire:model="inicio_consulta">
                            <option>Brusco</option>
                            <option>Progresivo</option>
                            <option>Insidioso</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Curso</label>
                        <select class="form-control" wire:model="curso_consulta">
                            <option>Estacionario</option>
                            <option>Progresivo</option>
                            <option>Intermitente</option>
                            <option>Mejoría</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Enfermedad Actual</label>
                        <textarea class="form-control" rows="2" wire:model="enfermedad_consulta"></textarea>

                    </div>
                </div>
            </div>


            <!-- ANTECEDENTES -->
            <div class="hc-card">
                <div class="hc-header">Antecedentes</div>
                <div class="hc-body row g-2">
                    <div class="col-md-6">
                        <label>Familiares</label>
                        <textarea class="form-control" rows="2" wire:model="atecedente_familiar_consulta"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Patológicos</label>
                        <textarea class="form-control" rows="2" wire:model="atecedente_patologico_consulta"></textarea>
                    </div>
                </div>
            </div>

            <!-- ANTROPOMETRÍA -->
            <div class="hc-card">
                <div class="hc-header">Somatometría</div>
                <div class="hc-body row g-2">
                    <div class="col-md-2">
                        <label>Peso</label>
                        <input type="number" class="form-control vital" wire:model.live="peso_consulta">
                    </div>
                    <div class="col-md-2">
                        <label>Talla</label>
                        <input type="number" class="form-control vital" wire:model.live="talla_consulta">
                    </div>
                    <div class="col-md-2">
                        <label>IMC</label>
                        <input type="text" class="form-control vital" wire:model="imc_consulta">
                    </div>
                </div>
            </div>

            <!-- SIGNOS VITALES -->
            <div class="hc-card">
                <div class="hc-header">Funciones Vitales</div>
                <div class="hc-body row g-2">
                    <div class="col-md-2">
                        <label>Temp °C</label>
                        <input type="number" step="0.1" class="form-control vital"
                            wire:model="temperatura_consulta">
                    </div>
                    <div class="col-md-3">
                        <label>PA</label>
                        <input type="text" class="form-control vital" wire:model="presion_consulta"
                            placeholder="120/80">
                    </div>
                    <div class="col-md-2">
                        <label>FC</label>
                        <input type="number" class="form-control vital" wire:model="frecuencia_consulta">
                    </div>
                    <div class="col-md-2">
                        <label>Sat O₂ %</label>
                        <input type="number" class="form-control vital" wire:model="saturacion_consulta">
                    </div>
                </div>
            </div>

            <!-- EXAMEN -->
            <div class="hc-card">
                <div class="hc-header">Examen Físico</div>
                <div class="hc-body">
                    <textarea class="form-control" rows="2" wire:model="examen_consulta"></textarea>
                </div>
            </div>

            <!-- DIAGNÓSTICO -->
            <div class="hc-card">
                <div class="hc-header">Impresión</div>
                <div class="hc-body row g-2">
                    <div class="col-md-12">
                        <label>Impresión diagnóstica</label>
                        <textarea class="form-control" rows="2" wire:model="impresion_consulta"></textarea>
                    </div>
                </div>
            </div>
            <!-- DIAGNÓSTICO -->
            <div class="hc-card">
                <div class="hc-header">Plan</div>
                <div class="hc-body row g-2">
                    <div class="col-md-12">
                        <label>Exámenes auxiliares</label>
                        <textarea class="form-control" rows="2" wire:model="examen_auxiliar_consulta"></textarea>
                    </div>
                    <br>
                    <div class="container">
<br>
                        <div class="col-md-12">
                            <div class="alert alert-primary border-0 shadow-sm" style="background-color:#f1f7ff;">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 fs-4 text-primary">
                                        💊
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold text-primary">
                                            Tratamiento farmacológico
                                        </h6>
                                        <p class="mb-0 text-secondary small">
                                            En este apartado registre los medicamentos indicados al paciente,
                                            especificando <strong>dosis</strong>, <strong>vía de
                                                administración</strong>,
                                            <strong>frecuencia</strong> y <strong>duración del tratamiento</strong>
                                            según criterio médico.
                                        </p>
                                        <br>
                                        <p class="mb-0 text-secondary small">
                                            Lo indicado será recibido por farmacia</p>
                                        <ul class="text-primary small">
                                            <li>Solo se emitirá al paciente los farmacos disponibles.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <textarea class="form-control" rows="2" wire:model="tratamiento_consulta"></textarea>
                    </div>
                </div>
            </div>

            <!-- BOTÓN -->
            <div class="text-end mt-3">
                <button type="button" wire:click="agregarConsulta" class="btn btn-clinico">
                    Guardar Consulta
                </button>
            </div>
        @endif

        <!-- BOTÓN -->
        <div class="text-end mt-2">
            <button type="button" wire:click="printConsulta" class="btn btn-imprimir">
                Imprimir Historia
                <i class="fa fa-print"></i>
            </button>
        </div>
    </div>
</div>
