<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Incidencias por Técnico - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col sm:flex-row bg-gray-100">
        <!-- Barra lateral - vertical en desktop, horizontal en móvil -->
        <aside class="w-full sm:w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Panel Gestor</h2>
                <p class=" text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
            </div>
            
            <!-- Menú -->
            <nav class="mt-4">
                <a href="{{ route('gestor.index') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-clipboard-list mr-2"></i>Incidencias
                </a>
                <a href="{{ route('gestor.incidencias.tecnico') }}" class="block py-2.5 px-4 bg-blue-900">
                    <i class="fas fa-users mr-2"></i>Por Técnico
                </a>
                
                <!-- Botón cerrar sesión -->
                <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 p-4 hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-out-alt text-red-500 text-xl"></i>
                        <span class="text-red-500">Cerrar sesión</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-4 sm:p-8">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-xl sm:text-2xl font-bold mb-6">Incidencias por Técnico</h1>
                
                <!-- Filtros -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <select id="ordenar_por" name="ordenar_por" class="w-full sm:w-auto border rounded-lg px-3 py-2">
                            <option value="desc">Más recientes primero</option>
                            <option value="asc">Más antiguas primero</option>
                            <option value="prioridad">Por prioridad</option>
                        </select>
                        <select id="filtrar_estado" name="filtrar_estado" class="w-full sm:w-auto border rounded-lg px-3 py-2">
                            <option value="">Todos los estados</option>
                            <option value="2">Asignadas</option>
                            <option value="3">En trabajo</option>
                            <option value="4">Resueltas</option>
                        </select>
                    </div>
                </div>

                <!-- Contenedor para la tabla -->
                <div id="tablaIncidenciasTecnico">
                    @include('gestor.partials.tabla-incidencias-tecnico')
                </div>
            </div>
        </main>
    </div>

    <!-- Script para AJAX -->
    <script>
    function aplicarFiltros() {
        const ordenar = document.getElementById('ordenar_por').value;
        const estado = document.getElementById('filtrar_estado').value;

        fetch('/gestor/incidencias-tecnico/filtrar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ordenar_por: ordenar,
                estado_id: estado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('tablaIncidenciasTecnico').innerHTML = data.html;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Event listeners para los selectores
    document.getElementById('ordenar_por').addEventListener('change', aplicarFiltros);
    document.getElementById('filtrar_estado').addEventListener('change', aplicarFiltros);
    </script>
</body>
</html> 