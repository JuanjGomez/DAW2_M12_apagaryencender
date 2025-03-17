<div class="bg-white rounded-lg shadow">
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
                    <td class="px-6 py-4 text-sm">
                        <button onclick="openEditModal({{ $categoria->load('subcategorias')->toJson() }})"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCategoria({{ $categoria->id }})"
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
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

<!-- Loading spinner -->
<div id="loadingSpinner" class="hidden flex justify-center items-center p-4">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
</div>