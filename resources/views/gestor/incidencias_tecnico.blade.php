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
                <a href="{{ route('gestor.incidencias.tecnico') }}" class="block py-2.5 px-4 bg-blue-900">
                    <i class="fas fa-users-cog mr-2"></i>Técnicos
                </a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-6">Incidencias por Técnico</h1>
            
            <!-- Filtros -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex items-center gap-4">
                    <select name="ordenar_por" class="border rounded-lg px-3 py-2" onchange="aplicarFiltros(this.value)">
                        <option value="desc">Más recientes primero</option>
                        <option value="asc">Más antiguas primero</option>
                        <option value="prioridad">Por prioridad</option>
                    </select>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="ocultar_cerradas" name="ocultar_cerradas" 
                               class="mr-2" onchange="aplicarFiltros()">
                        <label for="ocultar_cerradas">Ocultar cerradas</label>
                    </div>
                </div>
            </div>

            @foreach($tecnicos as $tecnico)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ $tecnico->name }}</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tecnico->incidencias as $incidencia)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">#{{ $incidencia->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $incidencia->cliente->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $incidencia->estado->nombre === 'Asignada' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $incidencia->estado->nombre === 'En trabajo' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $incidencia->estado->nombre === 'Resuelta' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $incidencia->estado->nombre === 'Cerrada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $incidencia->estado->nombre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $incidencia->prioridad->nombre === 'Alta' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $incidencia->prioridad->nombre === 'Media' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $incidencia->prioridad->nombre === 'Baja' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $incidencia->prioridad->nombre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $incidencia->fecha_creacion }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('gestor.show', $incidencia->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </main>
    </div>

    <script>
    function aplicarFiltros() {
        const ordenar = document.querySelector('[name="ordenar_por"]').value;
        const ocultarCerradas = document.getElementById('ocultar_cerradas').checked;
        
        const params = new URLSearchParams({
            ordenar_por: ordenar,
            ocultar_cerradas: ocultarCerradas ? '1' : ''
        });
        
        window.location.href = `{{ route('gestor.incidencias.tecnico') }}?${params.toString()}`;
    }
    </script>
</body>
</html> 