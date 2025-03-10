// Validación de formulario de registro -----------------------------------------------------------------------------------
document.getElementById('name').oninput = function() {
    let name = this.value.trim()
    let nameError = ""
    let regex = /^[a-zA-Z0-9]+$/

    if(name.length == 0 || name == null || /^\s+$/.test(name)) {
        nameError = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else if (name.length < 3) {
        nameError = "El nombre no puede tener menos de 3 caracteres."
        this.style.border = "2px solid red"
    } else if (name.length > 30) {
        nameError = "El nombre no puede tener más de 30 caracteres."
        this.style.border = "2px solid red"
    } else if (!regex.test(name)) {
        nameError = "El nombre no puede contener caracteres especiales."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('name-error').innerHTML = nameError
    verificarForm()
}

document.getElementById('email').oninput = function() {
    let email = this.value.trim()
    let emailError = ""
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    if(email.length == 0 || email == null || /^\s+$/.test(email)) {
        emailError = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else if (!regex.test(email)) {
        emailError = "El email no es válido."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('email-error').innerHTML = emailError
    verificarForm()
}

document.getElementById('password').oninput = function() {
    let password = this.value.trim()
    let passwordError = ""

    if(password.length == 0 || password == null || /^\s+$/.test(password)) {
        passwordError = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else if (password.length < 8) {
        passwordError = "La contraseña no puede tener menos de 8 caracteres."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('password-error').innerHTML = passwordError
    verificarForm()
}

document.getElementById('password_confirmation').oninput = function() {
    let passwordConfirmation = this.value.trim()
    let passwordConfirmationError = ""
    let password = document.getElementById('password').value.trim()

    if(passwordConfirmation.length == 0 || passwordConfirmation == null || /^\s+$/.test(passwordConfirmation)) {
        passwordConfirmationError = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else if (passwordConfirmation != password) {
        passwordConfirmationError = "Las contraseñas no coinciden."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('password_confirmation-error').innerHTML = passwordConfirmationError
    verificarForm()
}

document.getElementById('sede_id').onchange = function() {
    let sedeError = ""
    let sede = this.value

    if(sede == null || sede == "") {
        sedeError = "Debe seleccionar una sede."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('sede_id-error').innerHTML = sedeError
    verificarForm()
}

function verificarForm() {
    let campos = [
        document.getElementById('name').value.trim(),
        document.getElementById('email').value.trim(),
        document.getElementById('password').value.trim(),
        document.getElementById('password_confirmation').value.trim(),
        document.getElementById('sede_id').value
    ]

    let errores = [
        document.getElementById('name-error').innerHTML,
        document.getElementById('email-error').innerHTML,
        document.getElementById('password-error').innerHTML,
        document.getElementById('password_confirmation-error').innerHTML,
        document.getElementById('sede_id-error').innerHTML
    ]

    let hayErrores = errores.some(error => error !== "")
    let camposVacios = campos.some(campo => campo === "")

    let sedeSeleccionada = document.getElementById('sede_id').value !== "" &&
                            document.getElementById('sede_id').value !== "Selecciona una sede" &&
                            document.getElementById('sede_id').selectedIndex !== 0

    document.getElementById('btnSesion').disabled = hayErrores || camposVacios || !sedeSeleccionada
}
// ------------------------------------------------------------------------------------------------------------------------

// Manejo de alerts -------------------------------------------------------------------------------------------------------
function obtenerMensajesSesion() {
    const body = document.body;
    return {
        error: body.dataset.error,
        emailDuplicado: body.dataset.emailDuplicado,
        validationErrors: body.dataset.validationErrors
    }
}

function mostrarMensajes() {
    const { error, emailDuplicado, validationErrors } = obtenerMensajesSesion();

    if (emailDuplicado) {
        Swal.fire({
            icon: 'error',
            title: 'Correo duplicado',
            text: emailDuplicado,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'rounded-xl',
                title: 'text-xl font-bold',
                confirmButton: 'px-4 py-2 rounded-lg'
            }
        });
    }

    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error de registro',
            text: error,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: 'Intentar de nuevo'
        });
    }

    if (validationErrors) {
        Object.keys(validationErrors).forEach(key => {
            const errorMessage = validationErrors[key];
            const input = document.getElementById(key);
            if (input) {
                input.style.border = "2px solid red";
                document.getElementById(`${key}-error`).innerHTML = errorMessage;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    mostrarMensajes();
});
// ------------------------------------------------------------------------------------------------------------------------
