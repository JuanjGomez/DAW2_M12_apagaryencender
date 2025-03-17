<!-- Vista de tabla para desktop -->
<div class="hidden sm:block bg-white rounded-lg shadow">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NOMBRE</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subcategorías</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ACCIONES</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($categoriasFiltradas as $categoria)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium">{{ $categoria->nombre }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center">
                            @foreach ($categoria->subcategorias as $index => $subcategoria )
                                <div class="flex items-center">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        @switch($index % 5)
                                            @case(0)
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case(1)
                                                bg-green-100 text-green-800
                                                @break
                                            @case(2)
                                                bg-purple-100 text-purple-800
                                                @break
                                            @case(3)
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @default
                                                bg-pink-100 text-pink-800
                                        @endswitch">
                                        {{ $subcategoria->nombre }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-8 py-6 text-sm">
                        <button onclick="openEditModal({{ $categoria->load('subcategorias')->toJson() }})"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="px-6 py-4 border-t">
        {!! $categoriasFiltradas->links() !!}
    </div>
</div>

<!-- Vista de cards para móvil -->
<div class="sm:hidden space-y-4">
    @foreach($categoriasFiltradas as $categoria)
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- Header con ID y estado -->
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="text-lg font-semibold">#{{ $categoria->id }}</span>
                <p class="text-gray-600">{{ $categoria->nombre }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                Activa
            </span>
        </div>

        <!-- Subcategorías -->
        <div class="mt-4">
            <div class="text-sm text-gray-500">Subcategorías:</div>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($categoria->subcategorias as $subcategoria)
                    <span class="inline-block px-3 py-1 rounded-full text-sm
                        @if($loop->iteration % 4 == 1) bg-blue-100 text-blue-800
                        @elseif($loop->iteration % 4 == 2) bg-cyan-100 text-cyan-800
                        @elseif($loop->iteration % 4 == 3) bg-green-100 text-green-800
                        @else bg-amber-100 text-amber-800
                        @endif">
                        {{ $subcategoria->nombre }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-4 flex justify-end gap-4">
            <button onclick="openEditModal({{ $categoria->load('subcategorias')->toJson() }})"
                    class="text-blue-600 flex items-center">
                <i class="fas fa-edit mr-1"></i>
                <span>Editar</span>
            </button>
        </div>
    </div>
    @endforeach
</div>

<!-- Loading spinner -->
<div id="loadingSpinner" class="hidden flex justify-center items-center p-4">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
</div>