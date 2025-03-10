<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Técnico - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Panel Técnico</h2>
                <p class="text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
            </div>
            <nav class="mt-4">
                <a href="#" class="block py-2.5 px-4 bg-blue-700">
                    <i class="fas fa-clipboard-list mr-2"></i>Mis Incidencias
                </a>
                <a href="#" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-history mr-2"></i>Historial
                </a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Mis Incidencias Asignadas</h1>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <select class="border rounded-lg px-3 py-2">
                        <option value="">Todas las prioridades</option>
                        <option value="alta">Alta</option>
                        <option value="media">Media</option>
                        <option value="baja">Baja</option>
                    </select>
                    <select class="border rounded-lg px-3 py-2">
                        <option value="">Todos los estados</option>
                        <option value="asignada">Asignada</option>
                        <option value="en_trabajo">En trabajo</option>
                        <option value="resuelta">Resuelta</option>
                    </select>
                    <select class="border rounded-lg px-3 py-2">
                        <option value="desc">Más recientes primero</option>
                        <option value="asc">Más antiguas primero</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de incidencias -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach(Auth::user()->incidenciasTecnico as $incidencia)
                        <tr>
                            <td class="px-6 py-4">#{{ $incidencia->id }}</td>
                            <td class="px-6 py-4">{{ $incidencia->cliente->name }}</td>
                            <td class="px-6 py-4">{{ Str::limit($incidencia->descripcion, 50) }}</td>
                            <td class="px-6 py-4">
                                <select class="border rounded px-2 py-1 text-sm"
                                        onchange="actualizarEstado({{ $incidencia->id }}, this.value)">
                                    <option value="2" {{ $incidencia->estado_id == 2 ? 'selected' : '' }}>Asignada</option>
                                    <option value="3" {{ $incidencia->estado_id == 3 ? 'selected' : '' }}>En trabajo</option>
                                    <option value="4" {{ $incidencia->estado_id == 4 ? 'selected' : '' }}>Resuelta</option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $incidencia->prioridad_id == 1 ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $incidencia->prioridad_id == 2 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $incidencia->prioridad_id == 3 ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $incidencia->prioridad->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" 
                                        onclick="verDetalles({{ $incidencia->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" 
                                        onclick="resolverIncidencia({{ $incidencia->id }})">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html> 