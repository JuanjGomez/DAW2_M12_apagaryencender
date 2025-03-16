<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <img src="{{ asset('img/adje.png') }}" alt="Logo" class="h-10 mr-4">
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
                <!-- Mis Incidencias -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-2">Mis Incidencias</h3>
                    <p class="text-3xl font-bold text-blue-600">
                    {{ Auth::user()->incidenciasCliente->whereIn('estado_id', [1, 2, 3, 4])->count() }}
                    </p>
                </div>

                <!-- Incidencias Pendientes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-2">Incidencias Pendientes</h3>
                    <p class="text-3xl font-bold text-yellow-600">
                        {{ Auth::user()->incidenciasCliente->whereIn('estado_id', [1, 2, 3])->count() }}
                    </p>
                </div>

                <!-- Incidencias Resueltas -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-2">Incidencias Resueltas</h3>
                    <p class="text-3xl font-bold text-green-600">
                        {{ Auth::user()->incidenciasCliente->where('estado_id', 4)->count() }}
                    </p>
                </div>
            </div>

            <!-- Botón para abrir el modal -->
            <div class="mt-6">
                <button id="btnAbrirModal" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Crear nueva incidencia
                </button>
            </div>

           <!-- Modal -->
            <div id="modalCrearIncidencia" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-xl font-semibold mb-4">Crear Incidencia</h2>

                    <!-- Formulario de creación -->
                    <form id="formCrearIncidencia" enctype="multipart/form-data">
                        @csrf

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="categoria_id" class="block text-gray-700">Categoría</label>
                            <select name="categoria_id" id="categoria_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Selecciona una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 hidden" id="errorCategoria"></span>
                        </div>

                        <!-- Subcategoría -->
                        <div class="mb-4">
                            <label for="subcategoria_id" class="block text-gray-700">Subcategoría</label>
                            <select name="subcategoria_id" id="subcategoria_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Selecciona una subcategoría</option>
                                @foreach ($subcategorias as $subcategoria)
                                    <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 hidden" id="errorSubcategoria"></span>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="block text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                            <span class="text-red-500 hidden" id="errorDescripcion"></span>
                        </div>

                        <!-- Imagen -->
                        <div class="mb-4">
                            <label for="imagen" class="block text-gray-700">Adjuntar Imagen (Opcional)</label>
                            <input type="file" name="imagen" id="imagen" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" id="btnCerrarModal" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Crear</button>
                        </div>
                    </form>
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

            <!-- Lista de incidencias resueltas -->
            <div class="bg-white p-6 mt-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Incidencias Resueltas</h3>
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 text-left">Descripción</th>
                            <th class="py-2 px-4 text-left">Estado</th>
                            <th class="py-2 px-4 text-left">Técnico</th>
                            <th class="py-2 px-4 text-left">Fecha de Resolución</th>
                            <th class="py-2 px-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidenciasResueltas as $incidencia)
                        <tr class="border-t border-gray-200">
                            <td class="py-2 px-4">{{ $incidencia->descripcion }}</td>
                            <td class="py-2 px-4">{{ $incidencia->estado->nombre }}</td>
                            <td class="py-2 px-4">{{ $incidencia->tecnico->name }}</td>
                            <td class="py-2 px-4">{{ $incidencia->fecha_resolucion }}</td>
                            <td class="py-2 px-4 flex gap-2">
                                <!-- Botón de chat -->
                                <a href="{{ route('chat.show', $incidencia->id) }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                                <!-- Botón de ver detalles -->
                                <a href="{{ route('cliente.show', $incidencia->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Ver detalles
                                </a>
                                <!-- Botón de cerrar incidencia -->
                                <form method="POST" action="{{ route('incidencia.cerrar', $incidencia->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-block px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        <i class="fas fa-check-circle"></i> Cerrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Incluir el script -->
    <script src="{{ asset('js/crearModalCliente.js') }}"></script>
    <script src="{{ asset('js/toolsDashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
</body>
</html>
