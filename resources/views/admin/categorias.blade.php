<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sistema de Categorías de Incidencias</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/toolsAdmin.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">Panel Admin</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.index') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-users mr-2"></i>Gestión Usuarios
                </a>
                <a href="{{ route('admin.categorias') }}" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-tasks mr-2"></i>Tipos de Incidencias
                </a>
                <a href="#" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-building mr-2"></i>Sedes
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
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Categorías de Incidencias</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            id="openCreateModal">
                        + Nueva Categoría
                    </button>
                </div>

                <!-- Filtros -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select id="categoriaFilter" class="rounded-lg border-gray-300">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text"
                           id="searchInput"
                           class="rounded-lg border-gray-300"
                           placeholder="Buscar categoría o Subcategoría..."
                           value="{{ request('search') }}">

                    <button id="clearFilters"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Borrar filtros
                    </button>
                </div>

                <!-- Contenedor de la tabla -->
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
                                        <button type="button" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 deleteSubcategoria">
                                            <i class="fas fa-times"></i>
                                        </button>
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
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900">Editar Categoría</h3>
                        <form id="editCategoriaForm" class="mt-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="categoria_id" id="editCategoriaId">

                        <!-- Nombre de la Categoría -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Categoría</label>
                            <input type="text" name="nombre" id="editCategoriaNombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-sm" id="editNombreCategoriaError"></span>
                        </div>

                        <!-- Subcategorías -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Subcategorías</label>
                            <div id="editSubcategoriasContainer">
                                <!-- Las subcategorías se añadirán dinámicamente aquí -->
                            </div>
                            <button type="button" id="addEditSubcategoria" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-plus mr-2"></i>Añadir Subcategoría
                            </button>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <button type="button" id="closeEditModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
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
