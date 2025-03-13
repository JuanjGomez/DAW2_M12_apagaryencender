@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Mis Incidencias Asignadas</h2>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incidencias as $incidencia)
                                    <tr>
                                        <td>{{ $incidencia->id }}</td>
                                        <td>{{ $incidencia->cliente->name }}</td>
                                        <td>{{ $incidencia->categoria->nombre }}</td>
                                        <td>{{ $incidencia->subcategoria->nombre }}</td>
                                        <td>
                                            <span class="badge bg-{{ $incidencia->estado->nombre === 'Assignada' ? 'warning' : 
                                                ($incidencia->estado->nombre === 'En treball' ? 'info' : 
                                                ($incidencia->estado->nombre === 'Resolta' ? 'success' : 'secondary')) }}">
                                                {{ $incidencia->estado->nombre }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $incidencia->prioridad->nombre === 'Alta' ? 'danger' : 
                                                ($incidencia->prioridad->nombre === 'Mitjana' ? 'warning' : 'info') }}">
                                                {{ $incidencia->prioridad->nombre }}
                                            </span>
                                        </td>
                                        <td>{{ $incidencia->fecha_creacion }}</td>
                                        <td>
                                            <a href="{{ route('tecnico.mostrar', $incidencia) }}" 
                                               class="btn btn-sm btn-primary">
                                                Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
