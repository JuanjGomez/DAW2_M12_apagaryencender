<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sistema de Incidencias</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/toolsAdmin.css') }}">
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
                <a href="{{ route('admin.index') }}"class="flex items-center px-4 py-3 bg-blue-900">
                    <i class="fas fa-clipboard-list mr-3 w-6"></i>
                    <span>Gestión de Usuarios</span>
                </a>

                <a href="{{ route('admin.categorias') }}"class="flex items-center px-4 py-3 hover:bg-blue-700">
                    <i class="fas fa-tasks mr-3 w-6"></i>
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
        <main class="flex-1 p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Header con título y botón -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Gestión de Usuarios</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg"
                            id="openCreateModal">
                        + Nuevo Usuario
                    </button>
                </div>

                <!-- Filtros - grid en móvil, flex en desktop -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="grid grid-cols-1 sm:flex sm:items-center sm:space-x-4 space-y-4 sm:space-y-0">
                        <!-- Select de sedes -->
                        <div class="relative sm:w-1/4">
                            <select id="sedeFilter"
                                    class="w-full p-4 sm:p-2 rounded-lg border border-gray-200 bg-white appearance-none text-gray-700">
                                <option value="">Todas las sedes</option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Select de roles -->
                        <div class="relative sm:w-1/4">
                            <select id="roleFilter"
                                    class="w-full p-4 sm:p-2 rounded-lg border border-gray-200 bg-white appearance-none text-gray-700">
                                <option value="">Todos los roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Barra de búsqueda -->
                        <div class="sm:flex-1">
                            <input type="text"
                                id="searchInput"
                                class="w-full p-4 sm:p-2 rounded-lg border border-gray-200 bg-white text-gray-700 placeholder-gray-400"
                                placeholder="Buscar usuario name o email...">
                        </div>

                        <!-- Botón borrar filtros -->
                        <button id="clearFilters"
                                class="w-full sm:w-auto p-4 sm:p-2 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center gap-2 sm:whitespace-nowrap">
                            <i class="fas fa-times"></i>
                            <span>Borrar filtros</span>
                        </button>
                    </div>
                </div>

                <!-- Lista de usuarios -->
                <div id="usersTable">
                    @include('admin.users_list')
                </div>
            </div>

            <!-- Modal de Crear Usuario -->
            <div id="createUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Usuario</h3>
                        <form id="createUserForm" class="mt-4">
                            @csrf
                            <!-- Nombre -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                                <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-sm" id="nameError"></span>
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-sm" id="emailError"></span>
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                                <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-sm" id="passwordError"></span>
                            </div>

                            <!-- Confirmar contraseña -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-sm" id="passwordConfirmationError"></span>
                            </div>

                            <!-- Rol -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rol</label>
                                <select name="role_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option selected disabled>Selecciona un rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="text-red-500 text-sm" id="roleError"></span>
                            </div>

                            <!-- Sede -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sede</label>
                                <select name="sede_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option selected disabled>Selecciona una sede</option>
                                    @foreach($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                                <span class="text-red-500 text-sm" id="sedeError"></span>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end mt-6">
                                <button type="button" id="closeCreateModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">
                                    Cancelar
                                </button>
                                <button id="createUserButton" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    Crear Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal de Editar Usuario -->
            <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900">Editar Usuario</h3>
                        <form id="editUserForm" class="mt-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" id="editUserId">

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                            <input type="text" name="name" id="editName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-sm" id="editNameError"></span>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" id="editEmail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-sm" id="editEmailError"></span>
                        </div>

                        <!-- Rol -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Rol</label>
                            <select name="role_id" id="editRole" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 text-sm" id="editRoleError"></span>
                        </div>

                        <!-- Sede -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sede</label>
                            <select name="sede_id" id="editSede" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="text-red-500 text-sm" id="editSedeError"></span>
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
        </main>
    </div>
    <script src="{{ asset('js/toolsAdmin.js') }}"></script>
    <script src="{{ asset('js/validateCreateUser.js') }}"></script>
</body>
</html>
