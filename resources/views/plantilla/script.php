<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<!-- jQuery (OBLIGATORIO) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    window.addEventListener('alert', event => {
        console.log(event.detail[0].type)
        Swal.fire({
            icon: event.detail[0].type,
            title: event.detail[0].title,
            text: event.detail[0].message,
            showConfirmButton: false,
            timer: 2000
        })
    })
</script>

<script>
    window.addEventListener('gotoTop', event => {
        $("#top").animate({
            scrollTop: 0
        }, 500);
    })
</script>
<script>
    window.addEventListener('paciente-encontrado', event => {
        setTimeout(function() {
            $("#alerta").fadeIn(400);
        }, 1000);
        setTimeout(function() {
            $("#alerta").fadeOut(2000);
        }, 1000);
    })

    window.addEventListener('paciente-no-existe', event => {
        setTimeout(function() {
            $("#alerta-no-existe").fadeIn(400);
        }, 1000);
        setTimeout(function() {
            $("#alerta-no-existe").fadeOut(2000);
        }, 1000);
    })
</script>
<script>
    document.addEventListener('init-ckeditor', function() {

        // Si ya existe, no volver a iniciarlo
        if (CKEDITOR.instances['editor']) {
            CKEDITOR.instances['editor'].destroy(true);
        }

        CKEDITOR.replace('editor', {
            height: 250
        });

        CKEDITOR.config.versionCheck = false;
    });
</script>


<script>
    document.addEventListener('get-ckeditor', function() {

        // Crear CKEditor

        const data = CKEDITOR.instances.editor.getData();


        // ⚠️ Livewire 3 usa dispatch, NO emit
        Livewire.dispatch('editorUpdated', {
            value: data
        });
    });
</script>



<script>
    function iniciarCKEditors() {
        document.querySelectorAll('textarea[id^="editor_"]').forEach(textarea => {

            let id = textarea.id;

            // SI NO EXISTE → CREAR
            if (!CKEDITOR.instances[id]) {

                CKEDITOR.replace(id);

                CKEDITOR.instances[id].on('change', function() {

                    let key = id.replace('editor_', '');

                    let component = Livewire.find(
                        textarea.closest('[wire\\:id]').getAttribute('wire:id')
                    );

                    component.set('resultados.' + key, this.getData());
                });

            }
            // SI YA EXISTE → FORZAR CARGA DE DATOS
            else {  
                CKEDITOR.instances[id].setData(textarea.value);
            }
        });

        CKEDITOR.config.versionCheck = false;
    }

    // Al cargar
    document.addEventListener('DOMContentLoaded', iniciarCKEditors);

    // DESPUÉS de cada render de Livewire
    document.addEventListener('livewire:load', () => {
        Livewire.hook('message.processed', () => {
            iniciarCKEditors();
        });
    });
    iniciarCKEditors();
</script>
</script>
<script>
    function printEditor() {
        var contenido = CKEDITOR.instances.editor.getData();
        var ventana = window.open('', '', 'height=800,width=800');
        ventana.document.write('<html><head><title>Clinica Bahía</title>');
        ventana.document.write('</head><body>');
        ventana.document.write(contenido);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.print();
    }
</script>



<script>
    document.addEventListener('get-grafico-signo', () => {



        const canvas = document.getElementById('graficoPresion');

        if (!canvas) return;

        // ✅ AQUÍ YA NO FALLA
        const datos = JSON.parse(canvas.dataset.datos);

        if (!datos || datos.length === 0) {
            console.warn('No hay datos para el gráfico');
            return;
        }

        const labels = datos.map(d =>
            new Date(d.fecha_signo).toLocaleDateString()
        );

        const sistolica = datos.map(d => d.sistolica_derecha);
        const diastolica = datos.map(d => d.diastolica_derecha);
        const latidos = datos.map(d => d.frecuencia_cardiaca);




        const ctx = canvas.getContext('2d');
        graficoPresion = new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                        label: 'Sistólica',
                        data: sistolica,
                        tension: 0.4
                    },
                    {
                        label: 'Diastólica',
                        data: diastolica,
                        tension: 0.4
                    },
                    {
                        label: 'Lpm',
                        data: latidos,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'lpm'
                        }
                    }
                }
            }
        });
    });
</script>
<script>
    function imprimirGrafico() {

        const canvas = document.getElementById('graficoPresion');
        if (!canvas) return;

        const imagen = canvas.toDataURL('image/png');

        const ventana = window.open('', '_blank');

        ventana.document.write(`
        <html>
        <head>
            <title>Reporte Clínico</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 30px;
                }
                h4 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                img {
                    max-width: 100%;
                }
            </style>
        </head>
        <body>
            <h4>Evolución de Presión Arterial</h4>
            <img id="imgGrafico" src="${imagen}">
            <script>
                const img = document.getElementById('imgGrafico');
                img.onload = function () {
                    window.print();
                };
                window.onafterprint = function () {
                    window.close();
                };
            <\/script>
        </body>
        </html>
    `);

        ventana.document.close();
    }
</script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('actualizarGrafico', (datos) => {
            console.log('DATOS:', datos);
            actualizarGrafico(datos);
        });
    });
</script>

<script>
    function actualizarGrafico(signos) {
        console.log('🚀 actualizarGrafico ejecutado');
        console.log('Signos:', signos);
        console.log('graficoPresion:', graficoPresion);

        if (!graficoPresion) {
            console.error('❌ gráfico no creado');
            return;
        }
        console.log('final:', graficoPresion.data.datasets)

        graficoPresion.data.labels = signos.map(s =>
            new Date(s.fecha_signo.replace(' ', 'T')).toLocaleTimeString()
        );

        graficoPresion.data.datasets[0].data =
            signos.map(s => Number(s.sistolica_derecha));

        graficoPresion.data.datasets[1].data =
            signos.map(s => Number(s.diastolica_derecha));

        graficoPresion.update();
    }
</script>