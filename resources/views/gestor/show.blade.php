<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Incidencia - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col sm:flex-row bg-gray-100">
        <!-- Barra lateral - vertical en desktop, horizontal en móvil -->
        <aside class="w-full sm:w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Panel Gestor</h2>
                <p class="text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
            </div>
            
            <!-- Menú -->
            <nav class="mt-4">
                <a href="{{ route('gestor.index') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-clipboard-list mr-2"></i>Incidencias
                </a>
                <a href="{{ route('gestor.incidencias.tecnico') }}" class="block py-2.5 px-4 hover:bg-blue-700">
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
                <!-- Encabezado de la incidencia -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <h2 class="text-xl font-bold mb-2">Incidencia #{{ $incidencia->id }}</h2>
                    <p class="text-gray-600 text-sm">Creada el {{ $incidencia->fecha_creacion }}</p>
                </div>

                <!-- Información de la incidencia -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Información Básica -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Información Básica</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Cliente</label>
                                <p class="mt-1">{{ $incidencia->cliente->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Categoría</label>
                                <p class="mt-1">{{ $incidencia->categoria->nombre }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Subcategoría</label>
                                <p class="mt-1">{{ $incidencia->subcategoria->nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Prioridad -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Estado y Prioridad</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2">Técnico Asignado</label>
                                <select onchange="asignarTecnico({{ $incidencia->id }}, this.value)"
                                        class="w-full border rounded-lg px-3 py-2 bg-white">
                                    <option value="">Sin asignar</option>
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}" 
                                            {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                                <select onchange="actualizarPrioridad({{ $incidencia->id }}, this.value)"
                                        class="w-full border rounded-lg px-3 py-2 bg-white">
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->id }}"
                                            {{ $incidencia->prioridad_id == $prioridad->id ? 'selected' : '' }}>
                                            {{ $prioridad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Descripción</h3>
                        <p class="text-gray-700">{{ $incidencia->descripcion }}</p>
                    </div>

                    @if($incidencia->fecha_resolucion)
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Fecha de Resolución</h3>
                        <p class="text-gray-700">{{ $incidencia->fecha_resolucion }}</p>
                    </div>
                    @endif
                </div>
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
                    window.location.reload();
                }
            });
        }
    </script>
</body>
</html>
