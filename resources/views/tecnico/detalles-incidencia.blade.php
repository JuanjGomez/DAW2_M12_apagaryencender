<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Detalles de la Incidencia #{{ $incidencia->id }}</h2>
    
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="font-semibold">Cliente:</p>
            <p>{{ $incidencia->cliente->name }}</p>
        </div>
        
        <div>
            <p class="font-semibold">Categoría:</p>
            <p>{{ $incidencia->categoria->nombre }}</p>
        </div>
        
        <div>
            <p class="font-semibold">Subcategoría:</p>
            <p>{{ $incidencia->subcategoria->nombre }}</p>
        </div>
        
        <div>
            <p class="font-semibold">Estado:</p>
            <p>{{ $incidencia->estado->nombre }}</p>
        </div>
        
        <div>
            <p class="font-semibold">Prioridad:</p>
            <p>{{ $incidencia->prioridad->nombre }}</p>
        </div>
        
        <div>
            <p class="font-semibold">Fecha de creación:</p>
            <p>{{ $incidencia->fecha_creacion }}</p>
        </div>
    </div>
    
    <div class="mt-4">
        <p class="font-semibold">Descripción:</p>
        <p class="mt-2">{{ $incidencia->descripcion }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('tecnico.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Volver al listado
        </a>
    </div>
</div> 