<div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <style>
        body {
            background: #f4f7fb;
        }

        /* PANEL */
        .panel-clinico {
            background: white;
            border-radius: 12px;
            border: 1px solid #e8eef5;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        /* TITULOS */
        .titulo-panel {
            font-weight: 600;
            font-size: 15px;
            color: #0f3b63;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        /* BOTON */
        .btn-clinico {
            background: linear-gradient(135deg, #0f3b63, #1f6fb2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 500;
            transition: .2s;
        }

        .btn-clinico:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        /* CALENDARIO */
        #calendar {
            background: white;
            border-radius: 12px;
            padding: 15px;
        }

        /* TITULO DEL MES */
        .fc-toolbar-title {
            font-size: 18px !important;
            font-weight: 600;
            color: #0f3b63;
        }

        /* BOTONES DEL CALENDARIO */
        .fc-button {
            background: #f1f5f9 !important;
            border: none !important;
            color: #334155 !important;
            border-radius: 6px !important;
            font-weight: 500;
        }

        .fc-button:hover {
            background: #e2e8f0 !important;
        }

        .fc-button-active {
            background: #1f6fb2 !important;
            color: white !important;
        }

        /* EVENTOS */
        .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 4px;
            font-size: 12px;
            font-weight: 500;
        }

        /* HOVER EVENTO */
        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* HOY */
        .fc-day-today {
            background: #f0f9ff !important;
        }

        /* GRID */
        .fc-scrollgrid {
            border-radius: 10px;
            overflow: hidden;
        }

        /* DETALLE CITA */
        .info-cita {
            background: #f8fafc;
            border-radius: 10px;
            padding: 14px;
            border: 1px solid #e2e8f0;
        }

        .info-cita strong {
            color: #0f3b63;
        }
    </style>

    <div class="row g-3">

        <!-- PANEL IZQUIERDO -->
        <div class="col-md-3">

            <div class="panel-clinico">

                <div class="titulo-panel">
                    🗓 Nueva Cita
                </div>

                <div class="mb-2">
                    <label>Fecha</label>
                    <input type="date" class="form-control" wire:model="fecha">
                </div>

                <div class="mb-2">
                    <label>Hora</label>
                    <input type="time" class="form-control" wire:model="hora">
                </div>

                <div class="mb-2" wire:ignore>
                    <label>Paciente</label>

                    <select id="selectPaciente">
                        <option value="">Buscar paciente...</option>

                        @foreach ($pacientes as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label>Médico</label>
                    <select class="form-control" wire:model="id_medico">
                        <option value="">Seleccionar</option>

                        @foreach ($medicos as $m)
                            <option value="{{ $m->id }}">
                                {{ $m->nombre_completo }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label>Motivo</label>
                    <textarea class="form-control" rows="2" wire:model="motivo"></textarea>
                </div>

                <button class="btn-clinico w-100" wire:click="guardarCita">
                    Guardar Cita
                </button>

                <!-- DETALLE CITA -->
                <div class="info-cita mt-4" id="detalleCita" style="display:none">

                    <div class="titulo-panel">
                        📋 Detalle de Cita
                    </div>

                    <p><strong>Paciente:</strong> <span id="dcPaciente"></span></p>


                    <p><strong>Médico:</strong> <span id="dcMedico"></span></p>
                    <p><strong>Motivo:</strong></p>
                    <div id="dcMotivo" style="font-size:13px;color:#374151"></div>

                    <button class="btn btn-danger btn-sm mt-2 w-100" id="btnEliminar">
                        Eliminar Cita
                    </button>

                </div>

            </div>

        </div>

        <!-- CALENDARIO -->
        <div class="col-md-9">

            <div class="panel-clinico">

                <div wire:ignore>
                    <div id="calendar"></div>
                </div>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectPaciente = new TomSelect("#selectPaciente", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Buscar paciente...",
                maxOptions: 500
            });

            selectPaciente.on("change", function(value) {

                Livewire.find(@this.__instance.id).set('id_paciente', value);

            });

        });
        let citaSeleccionada = null;

        document.addEventListener('DOMContentLoaded', function() {

            let calendarEl = document.getElementById('calendar');

            window.calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',
                locale: 'es',
                height: 700,
                nowIndicator: true,
                eventDisplay: 'block',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                events: @json($citas),

                eventClick: function(info) {

                    citaSeleccionada = info.event.id;

                    let data = info.event.extendedProps;

                    document.getElementById('dcPaciente').innerText = data.paciente;
                    document.getElementById('dcMedico').innerText = data.medico;
                    document.getElementById('dcMotivo').innerText = data.motivo ?? '';

                    document.getElementById('detalleCita').style.display = 'block';

                }

            });

            calendar.render();

        });


        document.getElementById('btnEliminar').addEventListener('click', function() {

            if (!citaSeleccionada) return;

            if (confirm("¿Eliminar esta cita?")) {

                Livewire.dispatch('eliminarCita', {
                    id: citaSeleccionada
                });

                document.getElementById('detalleCita').style.display = 'none';

            }

        });
        document.addEventListener('livewire:init', () => {

            Livewire.on('recargarPagina', () => {
                location.reload();
            });

        });

        Livewire.on('refrescarCalendario', (data) => {

            calendar.removeAllEvents();
            calendar.addEventSource(data.citas);

        });
    </script>

</div>
