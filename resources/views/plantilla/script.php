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