<div class="overflow-x-auto">
    <!-- Vista móvil -->
    <div class="sm:hidden">
        @foreach($incidencias as $incidencia)
        <div class="bg-white p-4 mb-4 rounded-lg shadow">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <span class="font-bold">#{{ $incidencia->id }}</span>
                    <p class="text-sm text-gray-600">{{ $incidencia->cliente->name }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $incidencia->estado->color }}">
                    {{ $incidencia->estado->nombre }}
                </span>
            </div>
            
            <p class="text-sm mb-3">{{ Str::limit($incidencia->descripcion, 100) }}</p>
            
            <!-- Controles críticos -->
            <div class="grid grid-cols-2 gap-2 mt-3">
                <div>
                    <label class="text-xs text-gray-600">Técnico</label>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            onchange="asignarTecnico({{ $incidencia->id }}, this.value)">
                        <option value="">Sin asignar</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}"
                                {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-600">Prioridad</label>
                    <select class="w-full border rounded px-2 py-1 text-sm"
                            onchange="actualizarPrioridad({{ $incidencia->id }}, this.value)">
                        @foreach($prioridades as $prioridad)
                            <option value="{{ $prioridad->id }}"
                                {{ $incidencia->prioridad_id == $prioridad->id ? 'selected' : '' }}>
                                {{ $prioridad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-3 text-right">
                <a href="{{ route('gestor.show', $incidencia->id) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-eye"></i> Ver detalles
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Vista desktop - tabla original -->
    <div class="hidden sm:block">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Técnico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($incidencias as $incidencia)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">#{{ $incidencia->id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $incidencia->cliente->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ Str::limit($incidencia->descripcion, 50) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs
                                {{ $incidencia->estado->nombre === 'Asignada' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $incidencia->estado->nombre === 'En trabajo' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $incidencia->estado->nombre === 'Resuelta' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ $incidencia->estado->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <select class="border rounded px-2 py-1 text-sm w-full"
                                    onchange="actualizarPrioridad({{ $incidencia->id }}, this.value)">
                                @foreach($prioridades as $prioridad)
                                    <option value="{{ $prioridad->id }}" 
                                        {{ $incidencia->prioridad_id == $prioridad->id ? 'selected' : '' }}>
                                        {{ $prioridad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <select class="border rounded px-2 py-1 text-sm w-full"
                                    onchange="asignarTecnico({{ $incidencia->id }}, this.value)">
                                <option value="">Sin asignar</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}"
                                            {{ $incidencia->tecnico_id == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('gestor.show', $incidencia->id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 