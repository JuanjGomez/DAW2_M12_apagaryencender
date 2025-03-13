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
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            id="openCreateModal">
                        + Nuevo Usuario
                    </button>
                </div>

                <!-- Filtros -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <select id="sedeFilter" class="rounded-lg border-gray-300">
                        <option value="">Todas las sedes</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" {{ request('sede') == $sede->id ? 'selected' : '' }}>
                                {{ $sede->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <select id="roleFilter" class="rounded-lg border-gray-300">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text"
                           id="searchInput"
                           class="rounded-lg border-gray-300"
                           placeholder="Buscar usuario name o email..."
                           value="{{ request('search') }}">

                    <button id="clearFilters"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Borrar filtros
                    </button>
                </div>

                <!-- Contenedor de la tabla -->
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
