<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Incidencia {{ $incidencia->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <img src="{{ asset('img/adje.png') }}" alt="Logo" class="h-10 mr-4">
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
        <!-- Título del chat -->
        <h2 class="text-2xl font-semibold mb-4">Chat - Incidencia #{{ $incidencia->id }}</h2>

        <!-- Contenedor de mensajes -->
        <div class="chat-container mb-4">
            @foreach($mensajes as $mensaje)
                <div class="message @if($mensaje->usuario_id == Auth::id()) sent @else received @endif">
                    <strong>{{ $mensaje->usuario->name }}:</strong>
                    <p>{{ $mensaje->mensaje }}</p>
                    <span class="text-xs text-gray-500">{{ $mensaje->created_at->format('H:i') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Formulario para enviar un mensaje -->
        <form action="{{ route('chat.store', $incidencia->id) }}" method="POST">
            @csrf
            <textarea name="mensaje" rows="3" class="w-full p-3 border border-gray-300 rounded" placeholder="Escribe tu mensaje..."></textarea>
            <button type="submit" class="mt-2 w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Enviar</button>
        </form>
    </div>

    <script>
        // Desplazarse hacia abajo automáticamente para mostrar el último mensaje
        const chatContainer = document.querySelector('.chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    </script>
</body>
</html>
