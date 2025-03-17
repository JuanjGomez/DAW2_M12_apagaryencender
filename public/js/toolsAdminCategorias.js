// Variables globales
let params

// Función para refrescar la tabla
function refreshTable() {
    params = new URLSearchParams()

    // Obtener valores de los filtros
    const searchValue = document.getElementById('searchInput').value
    const categoriaValue = document.getElementById('categoriaFilter').value

    // Añadir parámetros si tienen valor
    if (searchValue) {
        params.append('search', searchValue);
    }
    if (categoriaValue) {
        params.append('categoria', categoriaValue);
    }

    // Mostrar el spinner
    document.getElementById('loadingSpinner').classList.remove('hidden')

    // Realizar la petición AJAX
    fetch(`${window.location.pathname}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('categoriasTable').innerHTML = html
        document.getElementById('loadingSpinner').classList.add('hidden');
        inicializarEventosPaginacion() // Reinicializar eventos de paginación
    })
    .catch(error => {
        console.error('Error:', error)
        document.getElementById('loadingSpinner').classList.add('hidden')
    }).finally(() => {
        document.getElementById('loadingSpinner').classList.add('hidden')
    })
}

// Función para manejar la paginación
function inicializarEventosPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault()
            const url = new URL(this.href)
            params.set('page', url.searchParams.get('page'))
            refreshTable()
        })
    })
}

// Función para inicializar el modal de crear categoría y subcategoría --------------------------------
function inicializarCategoriaModa() {
    const modal = document.getElementById('createCategoriaModal')
    const openButton = document.getElementById('openCreateModal')
    const closeButton = document.getElementById('closeCreateModal')
    const addSubcategoriaButton = document.getElementById('addSubcategoria')
    const form = document.getElementById('createCategoriaForm')

    // Abrir el modal
    openButton.addEventListener('click', function() {
        modal.classList.remove('hidden')
    })

    // Cerrar el modal
    closeButton.addEventListener('click', function() {
        modal.classList.add('hidden')
        form.reset()
    })

    // Cerrar el modal si se hace click fuera de él
    modal.addEventListener('click', function(e) {
        if(e.target === this) {
            modal.classList.add('hidden')
            form.reset()
        }
    })

    // Añadir subcategoría
    addSubcategoriaButton.addEventListener('click', () => {
        const container = document.getElementById('subcategoriasContainer')
        const newSubcategoria = document.createElement('div')
        newSubcategoria.className = 'flex items-center gap-2 mb-2'
        newSubcategoria.innerHTML = `
            <input type="text" name="subcategorias[]" class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        `
        container.appendChild(newSubcategoria)
    })

    // Manejar el envío del formulario
    form.addEventListener('submit', async (e) => {
        e.preventDefault()
        const formData = new FormData(form)

        try{
            const response = await fetch('/admin/createCategorias', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })

            const data = await response.json()

            if (data.success) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Categoría creada correctamente'
                })
                modal.classList.add('hidden')
                form.reset()
                refreshTable()
            } else {
                throw new Error(data.message)
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al crear la categoría'
            });
        }
    })
}

// Funcion para resetear el formulario
function resetForm() {
    const form = document.getElementById('createCategoriaForm')
    form.reset()
    const container = document.getElementById('subcategoriasContainer')
    container.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <input type="text" name="subcategorias[]" class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
    `
}
// -----------------------------------------------------------------------------------------------------

// Funciones para inicializar el modal de editar categoría -----------------------------------------------
// Funcion para abrir el modal de edicion
function openEditModal(categoria) {
    console.log('Datos recibidos:', categoria) // Para debugging

    const modal = document.getElementById('editCategoriaModal')
    const form = document.getElementById('editCategoriaForm')
    const container = document.getElementById('editSubcategoriasContainer')

    // Establecer los valores de la categoría
    document.getElementById('editCategoriaId').value = categoria.id;
    document.getElementById('editCategoriaNombre').value = categoria.nombre;

    // Limpiar el contenedor de subcategorías
    container.innerHTML = '';

    // Añadir las subcategorías existentes
    if (categoria.subcategorias && categoria.subcategorias.length > 0) {
        categoria.subcategorias.forEach(subcategoria => {
            const div = document.createElement('div')
            div.className = 'flex items-center gap-2 mb-2'
            div.innerHTML = `
                <input type="hidden" name="subcategoria_ids[]" value="${subcategoria.id}">
                <input type="text"
                       name="subcategorias[]"
                       value="${subcategoria.nombre}"
                       class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            `
            container.appendChild(div)
        })
    }

    // Mostrar el modal
    modal.classList.remove('hidden')
}

// Funcion para agregar una subcategoria al formulario de edicion
function addSubcategoriaToEdit(container, subcategoria) {
    const div = document.createElement('div')
    div.className = 'flex items-center gap-2 mb-2'
    div.innerHTML = `
        <input type="hidden" name="subcategoria_ids[]" value="${subcategoria ? subcategoria.id : ''}">
        <input type="text"
                name="subcategorias[]"
                value="${subcategoria ? subcategoria.nombre : ''}"
                class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    `
    container.appendChild(div)
}

// Inicializar el modal de edicion
function inicializarEditarCategoriaModal() {
    const modal = document.getElementById('editCategoriaModal')
    const closeButton = document.getElementById('closeEditModal')
    const addButton = document.getElementById('addEditSubcategoria')
    const form = document.getElementById('editCategoriaForm')

    // Añadir subcategoria
    addButton.addEventListener('click', () => {
        const container = document.getElementById('editSubcategoriasContainer')
        addSubcategoriaToEdit(container)
    })

    // Cerrar el modal
    closeButton.addEventListener('click', () => {
        modal.classList.add('hidden')
        form.reset()
    })

    // Cerrar el modal si se hace click fuera de él
    modal.addEventListener('click', function(e) {
        if(e.target === this) {
            modal.classList.add('hidden')
            form.reset()
        }
    })

    // Manejar el envío del formulario
    form.addEventListener('submit', async (e) => {
        e.preventDefault()
        const categoriaId = document.getElementById('editCategoriaId').value
        const formData = new FormData(form)

        try {
            const response = await fetch(`/admin/categorias/${categoriaId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Categoría actualizada correctamente'
                });
                modal.classList.add('hidden');
                refreshTable();
            } else {
                throw new Error(data.message || 'Error al actualizar la categoría');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al actualizar la categoría'
            })
        }
    })
}
// -----------------------------------------------------------------------------------------------------

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar paginación
    inicializarEventosPaginacion()

    // Filtro de categoría
    const categoriaFilter = document.getElementById('categoriaFilter')
    if (categoriaFilter) {
        categoriaFilter.addEventListener('change', refreshTable)
    }

    // Campo de búsqueda con debounce
    const searchInput = document.getElementById('searchInput')
    if (searchInput) {
        let timeout
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout)
            timeout = setTimeout(refreshTable, 300)
        })
    }

    // Botón de limpiar filtros
    const clearFiltersButton = document.getElementById('clearFilters')
    if (clearFiltersButton) {
        clearFiltersButton.addEventListener('click', function() {
            // Limpiar los valores de los filtros
            if (searchInput) searchInput.value = ''
            if (categoriaFilter) categoriaFilter.value = ''
            refreshTable()
        })
    }

    // Inicializar el modal de crear categoría
    inicializarCategoriaModa()

    // Inicializar el modal de editar categoría
    inicializarEditarCategoriaModal()

})
