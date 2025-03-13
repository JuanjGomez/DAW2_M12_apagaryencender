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
                <a href="#" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-users-cog mr-2"></i>Técnicos
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 p-4 hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-out-alt text-red-500"></i>
                        <span class="text-red-500">Cerrar sesión</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Detalles de la Incidencia #{{ $incidencia->id }}</h1>
                    <a href="{{ route('gestor.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Información básica</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Cliente:</span> {{ $incidencia->cliente->name }}</p>
                            <p><span class="font-medium">Categoría:</span> {{ $incidencia->categoria->nombre }}</p>
                            <p><span class="font-medium">Subcategoría:</span> {{ $incidencia->subcategoria->nombre }}</p>
                            <p><span class="font-medium">Fecha de creación:</span> {{ $incidencia->fecha_creacion }}</p>
                        </div>
                    </div>

                    <!-- Estado y asignación -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Estado y asignación</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="font-medium">Estado actual:</span>
                                <span class="ml-2 px-3 py-1 rounded-full text-sm
                                    {{ $incidencia->estado_id == 1 ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $incidencia->estado_id == 2 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $incidencia->estado_id == 3 ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $incidencia->estado_id == 4 ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $incidencia->estado->nombre }}
                                </span>
                            </div>

                            <div>
                                <span class="font-medium">Prioridad:</span>
                                <select class="ml-2 border rounded px-3 py-1"
                                        onchange="actualizarPrioridad({{ $incidencia->id }}, this.value)">
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->id }}" 
                                                {{ $incidencia->prioridad_id == $prioridad->id ? 'selected' : '' }}>
                                            {{ $prioridad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <span class="font-medium">Técnico asignado:</span>
                                <select class="ml-2 border rounded px-3 py-1"
                                        onchange="asignarTecnico({{ $incidencia->id }}, this.value)">
                                    <option value="">Sin asignar</option>
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}"
                                                {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Descripción</h3>
                        <p class="whitespace-pre-wrap">{{ $incidencia->descripcion }}</p>
                    </div>

                    @if($incidencia->imagen)
                    <!-- Imagen -->
                    <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Imagen adjunta</h3>
                        <img src="{{ asset($incidencia->imagen) }}" alt="Imagen de la incidencia" class="max-w-xl rounded">
                    </div>
                    @endif

                    @if($incidencia->fecha_resolucion)
                    <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Fecha de Resolución</h3>
                        <p>{{ $incidencia->fecha_resolucion }}</p>
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
