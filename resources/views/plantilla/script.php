<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>


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