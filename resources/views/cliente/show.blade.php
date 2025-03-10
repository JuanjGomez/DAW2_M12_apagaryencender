<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100" data-login-message="{{ session('loginSuccess') }}">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold">Sistema de Incidencias</h1>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">Bienvenido, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto my-10 p-5 bg-white rounded-lg shadow-lg max-w-4xl">
        <h2 class="text-2xl font-semibold mb-4">Detalles de la Incidencia #{{ $incidencia->id }}</h2>

        <!-- Detalles de la incidencia -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Descripción:</h3>
            <p>{{ $incidencia->descripcion }}</p>
        </div>

        <!-- Imagen (si existe) -->
        @if($incidencia->imagen)
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Imagen de la Incidencia:</h3>
                <img src="{{ asset($incidencia->imagen) }}" alt="Imagen de la incidencia" class="w-full h-auto mt-2 rounded-lg shadow">
            </div>
        @endif

        <!-- Estado de la incidencia -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Estado:</h3>
            <p>{{ $incidencia->estado->nombre }}</p>
        </div>

        <!-- Técnico asignado -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Técnico Asignado:</h3>
            <p>{{ $incidencia->tecnico ? $incidencia->tecnico->name : 'Sin asignar' }}</p>
        </div>

        <!-- Fecha de creación -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Fecha de Creación:</h3>
            <p>{{ $incidencia->fecha_creacion }}</p>
        </div>

        <!-- Resolución de la incidencia (si está resuelta) -->
        @if($incidencia->estado->nombre == 'Resuelta')
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Resolución:</h3>
                <p>{{ $incidencia->resolucion }}</p>
            </div>
        @endif

        <!-- Botón para regresar -->
        <div class="mt-6">
            <a href="{{ route('cliente.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Regresar al listado de incidencias
            </a>
        </div>
    </div>

</body>
</html>
