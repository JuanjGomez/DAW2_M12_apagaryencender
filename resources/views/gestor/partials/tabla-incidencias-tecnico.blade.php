@foreach($tecnicos as $tecnico)
<div class="bg-white rounded-lg shadow-lg mb-6">
    <div class="p-4 bg-gray-50 border-b">
        <h2 class="text-lg font-semibold">{{ $tecnico->name }}</h2>
        <p class="text-sm text-gray-600">{{ count($tecnico->incidenciasTecnico) }} incidencias asignadas</p>
    </div>
    
    <div class="p-4">
        @if($tecnico->incidenciasTecnico->count() > 0)
            @foreach($tecnico->incidenciasTecnico as $incidencia)
                <div class="border-b last:border-0 py-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-medium">#{{ $incidencia->id }}</span>
                            <p class="text-sm text-gray-600">{{ $incidencia->cliente->name }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $incidencia->estado->color }}">
                            {{ $incidencia->estado->nombre }}
                        </span>
                    </div>
                    <p class="text-sm mb-2">{{ Str::limit($incidencia->descripcion, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs px-2 py-1 rounded-full 
                            {{ $incidencia->prioridad->color }}">
                            {{ $incidencia->prioridad->nombre }}
                        </span>
                        <a href="{{ route('gestor.show', $incidencia->id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye"></i> Ver detalles
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-gray-500 text-center py-4">No hay incidencias asignadas</p>
        @endif
    </div>
</div>
@endforeach 