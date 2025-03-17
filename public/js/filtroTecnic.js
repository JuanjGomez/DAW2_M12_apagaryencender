document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencias a los selectores de filtro
    const prioridadSelect = document.querySelector('select[name="prioridad"]');
    const estadoSelect = document.querySelector('select[name="estado"]');
    const ordenSelect = document.querySelector('select[name="orden"]');

    // Función para aplicar los filtros
    async function aplicarFiltros() {
        try {
            const filtros = {
                prioridad: prioridadSelect.value,
                estado: estadoSelect.value,
                orden: ordenSelect.value
            };

            const response = await fetch('/tecnico/filtrar-incidencias', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(filtros)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Error en la respuesta del servidor');
            }

            if (data.success) {
                actualizarTabla(data.incidencias);
            } else {
                throw new Error(data.message || 'Error al filtrar las incidencias');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No se pudieron cargar las incidencias filtradas'
            });
        }
    }

    // Función para actualizar la tabla con los resultados filtrados
    function actualizarTabla(incidencias) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = ''; // Limpiar tabla actual

        if (incidencias.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No se encontraron incidencias con los filtros seleccionados
                    </td>
                </tr>
            `;
            return;
        }

        incidencias.forEach(incidencia => {
            const prioridadClass = getPrioridadClass(incidencia.prioridad_id);
            
            const row = `
                <tr>
                    <td class="px-6 py-4">#${incidencia.id}</td>
                    <td class="px-6 py-4">${incidencia.cliente.name}</td>
                    <td class="px-6 py-4">${incidencia.descripcion.substring(0, 50)}${incidencia.descripcion.length > 50 ? '...' : ''}</td>
                    <td class="px-6 py-4">
                        <select class="border rounded px-2 py-1 text-sm" 
                                onchange="actualizarEstado(${incidencia.id}, this.value)">
                            <option value="2" ${incidencia.estado_id == 2 ? 'selected' : ''}>Asignada</option>
                            <option value="3" ${incidencia.estado_id == 3 ? 'selected' : ''}>En trabajo</option>
                            <option value="4" ${incidencia.estado_id == 4 ? 'selected' : ''}>Resuelta</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs ${prioridadClass}">
                            ${incidencia.prioridad.nombre}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <button class="text-blue-600 hover:text-blue-800"
                                onclick="verDetalles(${incidencia.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-green-600 hover:text-green-800"
                                onclick="window.location.href='/chat/${incidencia.id}'">
                            <i class="fas fa-comments"></i> Chat
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Función auxiliar para obtener la clase CSS de prioridad
    function getPrioridadClass(prioridadId) {
        switch (parseInt(prioridadId)) {
            case 1: return 'bg-red-100 text-red-800';    // Urgente
            case 2: return 'bg-yellow-100 text-yellow-800'; // Alta
            case 3: return 'bg-blue-100 text-blue-800';   // Media
            case 4: return 'bg-green-100 text-green-800'; // Baja
            default: return '';
        }
    }

    // Añadir event listeners a los selectores
    prioridadSelect.addEventListener('change', aplicarFiltros);
    estadoSelect.addEventListener('change', aplicarFiltros);
    ordenSelect.addEventListener('change', aplicarFiltros);
});
