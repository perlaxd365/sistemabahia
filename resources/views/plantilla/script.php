<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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