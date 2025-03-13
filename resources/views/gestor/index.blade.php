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
            <form action="{{ route('gestor.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select name="prioridad_id" class="border rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Todas las prioridades</option>
                        @foreach($prioridades as $prioridad)
                            <option value="{{ $prioridad->id }}" {{ request('prioridad_id') == $prioridad->id ? 'selected' : '' }}>
                                {{ $prioridad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <select name="tecnico_id" class="border rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Todos los técnicos</option>
                        @foreach(Auth::user()->sede->tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" {{ request('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex items-center">
                        <input type="checkbox" id="ocultar_resueltas" name="ocultar_resueltas" value="1" 
                               {{ request('ocultar_resueltas') ? 'checked' : '' }} 
                               onchange="this.form.submit()" class="mr-2">
                        <label for="ocultar_resueltas">Ocultar resueltas</label>
                    </div>
                    <select name="fecha_entrada" class="border rounded-lg px-3 py-2" onchange="this.form.submit()">
                        <option value="">Todas las fechas</option>
                        <option value="asc" {{ request('fecha_entrada') == 'asc' ? 'selected' : '' }}>Más antiguas primero</option>
                        <option value="desc" {{ request('fecha_entrada') == 'desc' ? 'selected' : '' }}>Más recientes primero</option>
                    </select>
                </div>
            </form>

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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Técnico</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($incidencias as $incidencia)
                        <tr>
                            <td class="px-6 py-4">#{{ $incidencia->id }}</td>
                            <td class="px-6 py-4">{{ $incidencia->cliente->name }}</td>
                            <td class="px-6 py-4">{{ Str::limit($incidencia->descripcion, 50) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $incidencia->estado->nombre === 'Sin asignar' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $incidencia->estado->nombre === 'Asignada' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $incidencia->estado->nombre === 'En trabajo' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $incidencia->estado->nombre === 'Resuelta' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $incidencia->estado->nombre === 'Cerrada' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $incidencia->estado->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <select class="border rounded px-2 py-1 text-sm"
                                        onchange="actualizarPrioridad({{ $incidencia->id }}, this.value)">
                                    <option value="">Sin prioridad</option>
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->id }}" {{ $incidencia->prioridad_id == $prioridad->id ? 'selected' : '' }}>
                                            {{ $prioridad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <select class="border rounded px-2 py-1 text-sm"
                                        onchange="asignarTecnico({{ $incidencia->id }}, this.value)">
                                    <option value="">Sin asignar</option>
                                    @foreach(Auth::user()->sede->tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}"
                                                {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('gestor.show', $incidencia->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
    </script>
</body>
</html>
