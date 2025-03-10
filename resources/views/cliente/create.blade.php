<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Incidencia - Sistema de Incidencias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <div class="min-h-screen">
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

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto py-6 px-4">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Crear Incidencia</h2>

            <!-- Formulario de creación de incidencia -->
            <form action="{{ route('cliente.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded-lg">
                @csrf

                <!-- Categoría -->
                <div class="mb-4">
                    <label for="categoria_id" class="block text-gray-700">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Subcategoría -->
                <div class="mb-4">
                    <label for="subcategoria_id" class="block text-gray-700">Subcategoría</label>
                    <select name="subcategoria_id" id="subcategoria_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Selecciona una subcategoría</option>
                        @foreach ($subcategorias as $subcategoria)
                            <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('subcategoria_id') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                    @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Imagen -->
                <div class="mb-4">
                    <label for="imagen" class="block text-gray-700">Adjuntar Imagen (Opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    @error('imagen') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Botón de enviar -->
                <div class="mb-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Crear Incidencia</button>
                </div>
            </form>
        </main>
    </div>

</body>
</html>
