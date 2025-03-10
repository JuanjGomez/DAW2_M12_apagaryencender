document.addEventListener('DOMContentLoaded', function() {
    const loginMessage = document.body.dataset.loginMessage

    if(loginMessage && loginMessage !== 'null' && loginMessage !== '') {
        Swal.fire({
            icon: 'success',
            title: loginMessage,
            text: 'Has iniciado sesión correctamente',
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            customClass: {
                popup: 'rounded-xl',
                title: 'text-xl font-bold',
                confirmButton: 'px-4 py2 rounded-lg'
            }
        })
    }
})
