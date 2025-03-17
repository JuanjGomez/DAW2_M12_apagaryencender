@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Detalles de la Incidencia #{{ $incidencia->id }}</h2>
                    <a href="{{ route('tecnico.index') }}" class="btn btn-secondary">Volver</a>
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

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Información General</h4>
                            <table class="table">
                                <tr>
                                    <th>Cliente:</th>
                                    <td>{{ $incidencia->cliente->name }}</td>
                                </tr>
                                <tr>
                                    <th>Categoría:</th>
                                    <td>{{ $incidencia->categoria->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Subcategoría:</th>
                                    <td>{{ $incidencia->subcategoria->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Prioridad:</th>
                                    <td>
                                        <span class="badge bg-{{ $incidencia->prioridad->nombre === 'Alta' ? 'danger' : 
                                            ($incidencia->prioridad->nombre === 'Mitjana' ? 'warning' : 'info') }}">
                                            {{ $incidencia->prioridad->nombre }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha de Creación:</th>
                                    <td>{{ $incidencia->fecha_creacion }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Resolución:</th>
                                    <td>{{ $incidencia->fecha_resolucion ?? 'Pendiente' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Descripción del Problema</h4>
                            <div class="card">
                                <div class="card-body">
                                    {{ $incidencia->descripcion }}
                                </div>
                            </div>

                            @if($incidencia->imagen)
                                <h4 class="mt-4">Imagen Adjunta</h4>
                                <img src="{{ asset('storage/' . $incidencia->imagen) }}" 
                                     alt="Imagen de la incidencia" 
                                     class="img-fluid rounded">
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Cambiar Estado</h4>
                            <form action="{{ route('tecnico.cambiarEstado', $incidencia) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="input-group">
                                    <select name="estado_id" class="form-select">
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->id }}" 
                                                {{ $incidencia->estado_id == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <h4>Resolución</h4>
                            <form action="{{ route('tecnico.actualizarResolucion', $incidencia) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <textarea name="resolucion" 
                                              class="form-control @error('resolucion') is-invalid @enderror" 
                                              rows="4" 
                                              placeholder="Describe cómo se resolvió la incidencia...">{{ $incidencia->resolucion }}</textarea>
                                    @error('resolucion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success mt-2">
                                    Guardar Resolución
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 