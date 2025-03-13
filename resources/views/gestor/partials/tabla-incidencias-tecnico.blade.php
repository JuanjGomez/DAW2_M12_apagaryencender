@foreach($tecnicos as $tecnico)
<div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
    <div class="p-4 bg-gray-50 border-b">
        <h2 class="text-lg font-semibold">{{ $tecnico->name }}</h2>
    </div>
    
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($tecnico->incidencias as $incidencia)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">#{{ $incidencia->id }}</td>
                <td class="px-6 py-4 text-sm">{{ $incidencia->cliente->name }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $incidencia->estado->nombre === 'Asignada' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $incidencia->estado->nombre === 'En trabajo' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $incidencia->estado->nombre === 'Resuelta' ? 'bg-green-100 text-green-800' : '' }}">
                        {{ $incidencia->estado->nombre }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 rounded-full text-xs
                        {{ $incidencia->prioridad->nombre === 'Alta' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $incidencia->prioridad->nombre === 'Media' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $incidencia->prioridad->nombre === 'Baja' ? 'bg-green-100 text-green-800' : '' }}">
                        {{ $incidencia->prioridad->nombre }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">{{ $incidencia->fecha_creacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach 