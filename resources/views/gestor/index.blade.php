<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gestor - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Panel Gestor</h2>
                <p class="text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
            </div>
            <nav class="mt-4">
                <a href="{{ route('gestor.index') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-clipboard-list mr-2"></i>Incidencias
                </a>
                <a href="{{ route('gestor.incidencias.tecnico') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-users-cog mr-2"></i>Técnicos
                </a>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 p-4 hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-out-alt text-red-500 text-xl"></i>
                        <span class="text-red-500 text-xl">Cerrar sesión</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Gestión de Incidencias</h1>
            </div>

            <!-- Filtros -->
            <form id="filtrosForm" class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select name="prioridad_id" class="border rounded-lg px-3 py-2">
                        <option value="">Todas las prioridades</option>
                        @foreach($prioridades as $prioridad)
                            <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                        @endforeach
                    </select>
                    <select name="tecnico_id" class="border rounded-lg px-3 py-2">
                        <option value="">Todos los técnicos</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                        @endforeach
                    </select>
                    <div class="flex items-center">
                        <input type="checkbox" id="ocultar_resueltas" name="ocultar_resueltas" value="1" class="mr-2">
                        <label for="ocultar_resueltas">Ocultar resueltas</label>
                    </div>
                    <select name="fecha_entrada" class="border rounded-lg px-3 py-2">
                        <option value="desc">Más recientes primero</option>
                        <option value="asc">Más antiguas primero</option>
                    </select>
                </div>
            </form>

            <!-- Tabla de incidencias -->
            <div id="tablaIncidencias">
                @include('gestor.partials.tabla-incidencias')
            </div>
        </main>
    </div>

    <script>
    function actualizarPrioridad(incidenciaId, prioridadId) {
        fetch(`/gestor/incidencia/${incidenciaId}/actualizar-prioridad`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ prioridad_id: prioridadId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { 
                // Opcional: mostrar mensaje de éxito
            }
        });
    }

    function asignarTecnico(incidenciaId, tecnicoId) {
        fetch(`/gestor/incidencia/${incidenciaId}/asignar-tecnico`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tecnico_id: tecnicoId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Opcional: mostrar mensaje de éxito o recargar la página
                window.location.reload();
            }
        });
    }

    function aplicarFiltros() {
        const formData = new FormData(document.getElementById('filtrosForm'));
        
        fetch('{{ route("gestor.filtrar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('tablaIncidencias').innerHTML = data.html;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Agregar event listeners a los filtros
    document.querySelectorAll('#filtrosForm select, #filtrosForm input').forEach(element => {
        element.addEventListener('change', aplicarFiltros);
    });
    </script>
</body>
</html>
