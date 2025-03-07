document.getElementById('email').oninput = function() {
    let email = this.value.trim()
    let emailError = ""
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    if (email.length == 0 || email.length == 0 || /^\s+$/.test(email)) {
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

    if (password.length == 0 || password.length == 0 || /^\s+$/.test(password)) {
        passwordError = "El campo no puede estar vacío."
        this.style.border = "2px solid red"
    } else {
        this.style.border = "2px solid green"
    }

    document.getElementById('password-error').innerHTML = passwordError
    verificarForm()
}

// Valida si todo el formulario esta bien
function verificarForm() {
    let errores = [
        document.getElementById("email-error").innerHTML,
        document.getElementById("password-error").innerHTML,
    ]
    let campos = [
        document.getElementById("email").value.trim(),
        document.getElementById("password").value.trim(),
    ]

    const hayErrores = errores.some(error => error !== "")
    const camposVacios = campos.some(campo => campo === "")

    document.getElementById('btnSesion').disabled = hayErrores || camposVacios
}

// Muestra el mensaje de error en el formulario
if(typeof errorMessage !== 'undefined' && errorMessage !== "") {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: errorMessage,
    })
}
