<div class="bg-white rounded-lg shadow">
    <!-- Tabla para desktop -->
    <table class="min-w-full hidden sm:table">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NOMBRE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">EMAIL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ROL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SEDE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ACCIONES</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 rounded-full text-xs
                        @if($user->role->nombre === 'administrador') bg-purple-100 text-purple-800
                        @elseif($user->role->nombre === 'tecnico') bg-green-100 text-green-800
                        @elseif($user->role->nombre === 'gestor equipo') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ $user->role->nombre }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">{{ $user->sede->nombre }}</td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="openEditModal({{ $user->toJson() }})"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteUser({{ $user->id }})"
                            class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Cards para móvil -->
    <div class="sm:hidden">
        @foreach($users as $user)
        <div class="p-4 border-b last:border-b-0">
            <!-- Cabecera de la card -->
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-semibold text-lg">#{{ $user->id }}</h3>
                    <p class="text-gray-600">{{ $user->name }}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs
                    @if($user->role->nombre === 'administrador') bg-purple-100 text-purple-800
                    @elseif($user->role->nombre === 'tecnico') bg-green-100 text-green-800
                    @elseif($user->role->nombre === 'gestor equipo') bg-blue-100 text-blue-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ $user->role->nombre }}
                </span>
            </div>

            <!-- Información del usuario -->
            <div class="space-y-2 mb-3">
                <div class="text-sm">
                    <span class="text-gray-500">Email:</span>
                    <span class="text-gray-900">{{ $user->email }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-gray-500">Sede:</span>
                    <span class="text-gray-900">{{ $user->sede->nombre }}</span>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-4 mt-4">
                <button onclick="openEditModal({{ $user->toJson() }})"
                        class="text-blue-600 hover:text-blue-900">
                    <i class="fas fa-edit"></i>
                    <span class="ml-1">Editar</span>
                </button>
                <button onclick="deleteUser({{ $user->id }})"
                        class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                    <span class="ml-1">Eliminar</span>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="px-6 py-4 border-t">
        {!! $users->links() !!}
    </div>
</div>

<!-- Loading spinner -->
<div id="loadingSpinner" class="hidden flex justify-center items-center p-4">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
</div>
