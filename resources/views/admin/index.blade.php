<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="#" class="block py-2.5 px-4 hover:bg-blue-700">
                    <i class="fas fa-users mr-2"></i>Gestión Usuarios
                </a>
                <a href="#" class="block py-2.5 px-4 hover:bg-blue-700">
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
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Gestión de Usuarios</h1>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Nuevo Usuario
                </button>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <select class="border rounded-lg px-3 py-2">
                        <option value="">Todas las sedes</option>
                        <option value="1">Barcelona</option>
                        <option value="2">Madrid</option>
                    </select>
                    <select class="border rounded-lg px-3 py-2">
                        <option value="">Todos los roles</option>
                        <option value="2">Gestor</option>
                        <option value="3">Técnico</option>
                        <option value="4">Cliente</option>
                    </select>
                    <input type="text"
                           placeholder="Buscar usuario..."
                           class="border rounded-lg px-3 py-2">
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sede</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->role->nombre }}</td>
                            <td class="px-6 py-4">{{ $user->sede->nombre }}</td>
                            <td class="px-6 py-4">
                                <button class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
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
