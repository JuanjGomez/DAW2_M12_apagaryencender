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
                <h2 class="text-2xl font-semibold">Panel Técnico</h2>
                <p class="text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
            </div>
            
            <!-- Menú -->
            <nav class="mt-4">
                <a href="{{ route('tecnico.index') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-clipboard-list mr-2"></i>Mis Incidencias
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
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold mb-2">Incidencia #{{ $incidencia->id }}</h2>
                            <p class="text-gray-600 text-sm">Creada el {{ $incidencia->fecha_creacion }}</p>
                        </div>
                        <a href="{{ route('tecnico.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left mr-2"></i>Volver
                        </a>
                    </div>
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
                                <label class="text-sm font-medium text-gray-700">Estado</label>
                                <select onchange="actualizarEstado({{ $incidencia->id }}, this.value)"
                                        class="w-full border rounded-lg px-3 py-2 bg-white mt-1">
                                    <option value="2" {{ $incidencia->estado_id == 2 ? 'selected' : '' }}>Asignada</option>
                                    <option value="3" {{ $incidencia->estado_id == 3 ? 'selected' : '' }}>En trabajo</option>
                                    <option value="4" {{ $incidencia->estado_id == 4 ? 'selected' : '' }}>Resuelta</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Prioridad</label>
                                <p class="mt-1">{{ $incidencia->prioridad->nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Descripción</h3>
                        <p class="text-gray-700">{{ $incidencia->descripcion }}</p>
                    </div>

                    @if($incidencia->resolucion)
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Resolución</h3>
                        <p class="text-gray-700">{{ $incidencia->resolucion }}</p>
                    </div>
                    @endif

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
        function actualizarEstado(incidenciaId, estadoId) {
            fetch(`/tecnico/incidencias/${incidenciaId}/actualizar-estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ estado_id: estadoId })
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
