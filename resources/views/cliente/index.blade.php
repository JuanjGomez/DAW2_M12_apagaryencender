<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100" data-login-message="{{ session('loginSuccess') }}">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold">Sistema de Incidencias</h1>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">Bienvenido, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto py-6 px-4">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Panel de Control</h2>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-2">Mis Incidencias</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ Auth::user()->incidenciasCliente->count() }}
                    </p>
                </div>
            </div>

            <!-- Filtros y tabla de incidencias -->
            <div class="bg-white p-6 mt-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Mis Incidencias</h3>

                <!-- Filtros -->
                <form method="GET" action="{{ route('cliente.index') }}" class="mb-4">
                    <div class="flex flex-wrap gap-4">
                        <!-- Filtro por estado -->
                        <select name="estado" class="form-select border-gray-300 rounded-md" onchange="this.form.submit()">
                            <option value="">Filtrar por estado</option>
                            <option value="1" @if(request('estado_id') == '1') selected @endif>Sin asignar</option>
                            <option value="2" @if(request('estado_id') == '2') selected @endif>Activo</option>
                            <option value="3" @if(request('estado_id') == '3') selected @endif>Inactivo</option>
                            <option value="4" @if(request('estado_id') == '4') selected @endif>Pendiente</option>
                        </select>

                        <!-- Filtro por "resueltas" -->
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="resueltas" value="no" onchange="this.form.submit()" @if(request('resueltas') == 'no') checked @endif>
                            <span class="ml-2 text-sm">No mostrar incidencias resueltas</span>
                        </label>

                        <!-- Filtro de ordenación -->
                        <select name="orden" class="form-select border-gray-300 rounded-md" onchange="this.form.submit()">
                            <option value="asc" @if(request('orden') == 'asc') selected @endif>Orden Ascendente</option>
                            <option value="desc" @if(request('orden') == 'desc') selected @endif>Orden Descendente</option>
                        </select>
                    </div>
                </form>

                <!-- Tabla de incidencias -->
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 text-left">ID</th>
                            <th class="py-2 px-4 text-left">Descripción</th>
                            <th class="py-2 px-4 text-left">Estado</th>
                            <th class="py-2 px-4 text-left">Técnico</th>
                            <th class="py-2 px-4 text-left">Fecha de Creación</th>
                            <th class="py-2 px-4 text-left">Fecha de Resolución</th>
                            <th class="py-2 px-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidencias as $incidencia)
                        <tr class="border-t border-gray-200">
                            <td class="py-2 px-4">{{ $incidencia->id }}</td>
                            <td class="py-2 px-4">{{ $incidencia->descripcion }}</td>
                            <td class="py-2 px-4">{{ $incidencia->estado->nombre }}</td>
                            <td class="py-2 px-4">
                                {{ $incidencia->tecnico ? $incidencia->tecnico->name : 'No asignado' }}
                            </td>
                            <td class="py-2 px-4">{{ $incidencia->fecha_creacion }}</td>
                            <td class="py-2 px-4">
                                {{ $incidencia->fecha_resolucion ?? 'Aún no resuelta' }}
                            </td>
                            <td class="py-2 px-4 flex gap-2">
                                <!-- Botón de chat -->
                                <a href="{{ route('chat.show', $incidencia->id) }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                                <!-- Botón de ver detalles -->
                                <a href="{{ route('cliente.show', $incidencia->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Botón para crear incidencia -->
            <div class="mt-6">
                <a href="{{ route('cliente.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Crear nueva incidencia
                </a>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/toolsDashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
</body>
</html>
