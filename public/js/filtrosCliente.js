document.addEventListener("DOMContentLoaded", function () {
    const formFiltros = document.getElementById("form-filtros");
    const tablaIncidencias = document.querySelector("table tbody");

    // Escuchar cambios en los filtros
    formFiltros.addEventListener("change", function (event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        // Obtener los datos del formulario
        const formData = new FormData(formFiltros);

        // Enviar la solicitud AJAX
        fetch("/cliente/filtrar", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json",
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Limpiar la tabla actual
                tablaIncidencias.innerHTML = "";

                // Actualizar la tabla con las nuevas incidencias
                data.incidencias.forEach(incidencia => {
                    const fila = document.createElement("tr");
                    fila.classList.add("border-t", "border-gray-200");
                    fila.innerHTML = `
                        <td class="py-2 px-4">${incidencia.descripcion}</td>
                        <td class="py-2 px-4">${incidencia.estado.nombre}</td>
                        <td class="py-2 px-4">${incidencia.tecnico ? incidencia.tecnico.name : 'No asignado'}</td>
                        <td class="py-2 px-4">${incidencia.fecha_creacion}</td>
                        <td class="py-2 px-4">${incidencia.fecha_resolucion || 'Aún no resuelta'}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <a href="/chat/${incidencia.id}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <i class="fas fa-comments"></i> Chat
                            </a>
                            <a href="/cliente/${incidencia.id}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Ver detalles
                            </a>
                        </td>
                    `;
                    tablaIncidencias.appendChild(fila);
                });
            } else {
                console.error("Error al filtrar incidencias:", data.message);
            }
        })
        .catch(error => {
            console.error("Error en la solicitud AJAX:", error);
        });
    });
});