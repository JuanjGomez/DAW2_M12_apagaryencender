// Funcion para obtener todos los filtros actuales
function getCurrentFilters() {
    return {
        search: document.getElementById('searchInput').value,
        role: document.getElementById('roleFilter').value,
        sede: document.getElementById('sedeFilter').value,
        page: 1 // Resetear a pagina 1 cuando se aplican filtros
    }
}

function refreshTable(newParams = {}) {
    // Mostrar spinner
    document.getElementById('loadingSpinner').classList.remove('hidden');

    // Obtener los filtros actuales y combinarlos con los nuevos parametros
    const currentParams = getCurrentFilters()
    const params = { ...currentParams, ...newParams }

    // Construir URL con parametros
    const url = new URL(window.location.href)

    // Limpiar parametros existentes
    url.searchParams.forEach((value, key) => {
        url.searchParams.delete(key)
    })

    // Agregar nuevos parametros
    Object.keys(params).forEach(key => {
        if(params[key]) {
            url.searchParams.set(key, params[key]);
        }
    })

    // Realizar la peticion AJAX
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Reemplazar el contenido de la tabla
        const tableContainer = document.getElementById('usersTable')
        tableContainer.innerHTML = html

        // Reiniciar los eventos de paginacion
        inicializarEventosPaginacion()

        // Actualizar la URL sin recargar la pagina
        window.history.pushState({}, '', url)
    })
    .catch(error => {
        console.error('Error al actualizar la tabla:', error)
    })
    .finally(() => {
        // Ocultar el spinner
        document.getElementById('loadingSpinner').classList.add('hidden')
    })
}

function inicializarEventosPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault()
            const url = new URL(this.href)
            const page = url.searchParams.get('page')
            refreshTable({ page })
        })
    })
}

function openEditModal(user) {
    document.getElementById('editUserId').value = user.id
    document.getElementById('editName').value = user.name
    document.getElementById('editEmail').value = user.email
    document.getElementById('editRole').value = user.role_id
    document.getElementById('editSede').value = user.sede_id
    document.getElementById('editUserModal').classList.remove('hidden')
}

function deleteUser(userId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed) {
            // Mostrar spinner
            document.getElementById('loadingSpinner').classList.remove('hidden')

            fetch(`/admin/deleteUsers/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(eliminar => {
                if(eliminar.success) {
                    // Mostrar mensaje de exito
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario eliminado!',
                        text: 'El usuario ha sido eliminado exitosamente',
                        confirmButtonColor: '#3B82F6'
                    })

                    // Refrescar la tabla
                    refreshTable()
                } else {
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: eliminar.message || 'Hubo un error al eliminar el usuario',
                        confirmButtonColor: '#3B82F6'
                    })
                }
            })
            .catch(error => {
                console.error('Error:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al procesar la solicitud',
                    confirmButtonColor: '#3B82F6'
                })
            })
            .finally(() => {
                // Ocultar el spinner
                document.getElementById('loadingSpinner').classList.add('hidden')
            })
        }
    })
}

// Inicializar eventos cuando se carga la pagina
document.addEventListener('DOMContentLoaded', function() {
    inicializarEventosPaginacion()

    // Evento para el filtro de sede
    document.getElementById('sedeFilter').addEventListener('change', function() {
        refreshTable()
    })

    // Evento para el filtro de rol
    document.getElementById('roleFilter').addEventListener('change', function() {
        refreshTable()
    })

    // Evento para el input de busqueda con debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            refreshTable()
        }, 300)
    })

    // Event listener para el botón de borrar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.getElementById('sedeFilter').value = ''
        document.getElementById('roleFilter').value = ''
        document.getElementById('searchInput').value = ''
        refreshTable()
    })

    // Eventos para crear usuario -----------------------------------------------------------------
    // Evento para el boton de abrir el modal de crear usuario
    document.getElementById('openCreateModal').addEventListener('click', function() {
        document.getElementById('createUserModal').classList.remove('hidden')
    })

    // Evento para el boton de cerrar el modal de crear usuario
    document.getElementById('createUserModal').addEventListener('click', function (e) {
        if(e.target === this) {
            this.classList.add('hidden')
            document.getElementById('createUserForm').reset()
        }
    })

    // Evento cerrar el formulario de crear usuario por boton
    document.getElementById('closeCreateModal').addEventListener('click', function() {
        document.getElementById('createUserModal').classList.add('hidden')
        document.getElementById('createUserForm').reset()
    })

    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        e.preventDefault()

        const formData = new FormData(this)

        // Mostrar spinner o indicador de carga
        document.getElementById('loadingSpinner').classList.remove('hidden')

        fetch(`/admin/createUsers`, {
            method: 'POST',
            headers: {
                'X-Requested-With': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(datos => {
            if(datos.success) {
                // Mostrar mensaje de exito
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario creado!',
                    text: 'El usuario ha sido creado esxitosamente',
                    confirmButtonColor: '#3B82F6'
                })

                document.getElementById('createUserModal').classList.add('hidden')
                this.reset()

                // Refrescar la tabla
                refreshTable()
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Hubo un error al crear el usuario',
                    confirmButtonColor: '#3B82F6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar la solicitud',
                confirmButtonColor: '#3B82F6'
            });
        })
        .finally(() => {
            // Ocultar el spinner
            document.getElementById('loadingSpinner').classList.add('hidden')
        })
    })
    // --------------------------------------------------------------------------------------------

    // Eventos para editar usuario ----------------------------------------------------------------

    // Evento para cerrar el modal de editar usuario al hacer click fuera del modal
    document.getElementById('editUserModal').addEventListener('click', function(e) {
        if(e.target === this) {
            this.classList.add('hidden')
            document.getElementById('editUserForm').reset()
        }
    })

    // Evento para cerrar el formulario de editar usuario por boton
    document.getElementById('closeEditModal').addEventListener('click', function() {
        document.getElementById('editUserModal').classList.add('hidden')
        document.getElementById('editUserForm').reset()
    })

    // Evento para el envio del formulario de editar usuario
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault()

        const userId = document.getElementById('editUserId').value
        const formData = new FormData(this)

        // Mostrar spinner o indicador de carga
        document.getElementById('loadingSpinner').classList.remove('hidden')

        fetch(`/admin/updateUsers/${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(datos => {
            if(datos.success) {
                // Mostrar mensaje de exito
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario actualizado!',
                    text: 'El usuario ha sido actualizado exitosamente',
                    confirmButtonColor: '#3B82F6'
                })

                document.getElementById('editUserModal').classList.add('hidden')
                this.reset()

                // Refrescar la tabla
                refreshTable()
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: datos.message || 'Hubo un error al actualizar el usuario',
                    confirmButtonColor: '#3B82F6'
                })
            }
        })
        .catch(error => {
            console.error('Error:', error)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar la solicitud',
                confirmButtonColor: '#3B82F6'
            })
        })
        .finally(() => {
            // Ocultar el spinner
            document.getElementById('loadingSpinner').classList.add('hidden')
        })
    })
    // --------------------------------------------------------------------------------------------

})
