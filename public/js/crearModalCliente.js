document.addEventListener("DOMContentLoaded", function () {
    // Abrir el modal para crear una nueva incidencia
    document.getElementById('btnAbrirModal').addEventListener('click', function () {
        document.getElementById('modalCrearIncidencia').classList.remove('hidden');
    });

    // Cerrar el modal al hacer clic en el botón de cancelar
    document.getElementById('btnCerrarModal').addEventListener('click', function () {
        document.getElementById('modalCrearIncidencia').classList.add('hidden');
    });

    // Enviar el formulario para crear la incidencia
    document.getElementById('formCrearIncidencia').addEventListener('submit', function (event) {
        event.preventDefault();

        // Crear un nuevo objeto FormData
        const formData = new FormData();

        // Obtener los datos del formulario y añadirlos al objeto formData
        formData.append('categoria_id', document.getElementById('categoria_id').value);
        formData.append('subcategoria_id', document.getElementById('subcategoria_id').value);
        formData.append('descripcion', document.getElementById('descripcion').value);

        // Añadir el archivo de la imagen si existe
        const imagen = document.getElementById('imagen').files[0];
        if (imagen) {
            formData.append('imagen', imagen);
        }

        // Enviar la solicitud AJAX
        fetch('/incidencias', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Creado', 'La incidencia se ha creado con éxito.', 'success')
                    .then(() => {
                        // Limpiar el formulario y cerrar el modal
                        document.getElementById('formCrearIncidencia').reset();
                        document.getElementById('modalCrearIncidencia').classList.add('hidden');

                        // Recargar la página después de un segundo
                        window.location.reload();
                    });
            }
        })
        .catch(error => {
            console.error('Error al crear la incidencia:', error);
            Swal.fire('Error', 'Hubo un problema al crear la incidencia.', 'error');
        });
    });
});
