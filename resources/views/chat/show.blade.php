<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Incidencia {{ $incidencia->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .chat-container {
            height: 70vh;
            overflow-y: scroll;
        }
        .message {
            padding: 10px;
            margin: 5px;
            border-radius: 10px;
            max-width: 60%;
        }
        .message.sent {
            background-color: #DCF8C6;
            margin-left: auto;
        }
        .message.received {
            background-color: #F1F0F0;
        }
    </style>
</head>
<body class="bg-gray-100">
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
