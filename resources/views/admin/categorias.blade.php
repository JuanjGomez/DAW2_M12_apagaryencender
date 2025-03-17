<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sistema de Categorías de Incidencias</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/toolsAdminCategorias.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col sm:flex-row">
       <!-- Sidebar como columna -->
       <aside class="bg-blue-800 text-white">
        <!-- Header del panel -->
        <div class="p-4 border-b border-blue-700">
            <h2 class="text-2xl font-semibold">Panel Administrador</h2>
            <p class="text-sm text-blue-200">{{ Auth::user()->sede->nombre }}</p>
        </div>

        <!-- Menú en columna -->
        <nav class="flex flex-col">
            <a href="{{ route('admin.index') }}"class="flex items-center px-4 py-3 hover:bg-blue-700">
                <i class="fas fa-tasks mr-3 w-6"></i>
                <span>Gestión de Usuarios</span>
            </a>

            <a href="{{ route('admin.categorias') }}"class="flex items-center px-4 py-3 bg-blue-900">
                <i class="fas fa-clipboard-list mr-3 w-6"></i>
                <span>Tipos de Incidencias</span>
            </a>

            <!-- Botón cerrar sesión -->
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center px-4 py-3 text-red-500 mt-auto">
                <i class="fas fa-sign-out-alt mr-3 w-6"></i>
                <span>Cerrar sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header con título y botón -->
                <div class="max-w-7xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Gestión de Categorías</h1>
                        <button id="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg"
                                id="openCreateModal">
                            + Nueva Categoría
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="filter-container">
                    <!-- Select de categorías -->
                    <div class="filter-item">
                        <select id="categoriaFilter">
                            <option value="" selected disabled>Seleccionar categoría</option>
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Barra de búsqueda -->
                    <div class="filter-item">
                        <input type="text"
                               id="searchInput"
                               placeholder="Buscar por nombre de categoría...">
                    </div>

                    <!-- Botón borrar filtros -->
                    <div class="filter-item">
                        <button id="clearFilters">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Borrar filtros
                        </button>
                    </div>
                </div>

                <!-- Lista de categorías -->
                <div id="categoriasTable">
                    @include('admin.categorias_list')
                </div>
            </div>

            <!-- Modal de Crear Categoría -->
            <div id="createCategoriaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nueva Categoría</h3>
                        <form id="createCategoriaForm" class="mt-4">
                            @csrf
                            <!-- Nombre de la Categoría -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Categoría</label>
                                <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-sm" id="nombreCategoriaError"></span>
                            </div>

                            <!-- Subcategorías -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Subcategorías</label>
                                <div id="subcategoriasContainer">
                                    <div class="flex items-center gap-2 mb-2">
                                        <input type="text" name="subcategorias[]" class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>
                                <button type="button" id="addSubcategoria" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus mr-2"></i>Añadir Subcategoría
                                </button>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end mt-6">
                                <button type="button" id="closeCreateModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">
                                    Cancelar
                                </button>
                                <button id="createCategoriaButton" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    Crear Categoría
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal de Editar Categoría -->
            <div id="editCategoriaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-full sm:w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900">Editar Categoría</h3>
                        <form id="editCategoriaForm" class="mt-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="categoria_id" id="editCategoriaId">

                            <!-- Nombre de la Categoría -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Nombre de la Categoría</label>
                                <input type="text"
                                       name="nombre"
                                       id="editCategoriaNombre"
                                       class="w-full">
                                <span class="text-red-500" id="editNombreCategoriaError"></span>
                            </div>

                            <!-- Subcategorías -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2">Subcategorías</label>
                                <div id="editSubcategoriasContainer">
                                    <!-- Las subcategorías se añadirán dinámicamente aquí -->
                                </div>
                                <button type="button" id="addEditSubcategoria">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Añadir Subcategoría
                                </button>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end mt-6">
                                <button type="button" id="closeEditModal">
                                    Cancelar
                                </button>
                                <button type="submit">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/toolsAdminCategorias.js') }}"></script>
    <script src="{{ asset('js/validateCreateUser.js') }}"></script>
</body>
</html>
