<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Incidencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/chatCliente.css') }}"> <!-- Enlazamos el archivo CSS -->
</head>
<body>

    
    @if(Auth::user()->role_id == 4)
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between h-auto md:h-16">
                <!-- Logo y Título -->
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <img src="{{ asset('img/adje.png') }}" alt="Logo" class="h-10">
                    <h1 class="text-xl font-bold">Sistema de Incidencias</h1>
                </div>

                <!-- Enlace de "Volver al listado de incidencias" -->
                <div class="w-full md:w-auto text-center mb-4 md:mb-0">
                    <a href="{{ route('cliente.index') }}" class="text-blue-600 hover:text-blue-800 py-2">
                        Volver al listado de incidencias
                    </a>
                </div>

                <!-- Información del usuario y Cerrar sesión -->
                <div class="flex flex-col md:flex-row items-center space-x-0 md:space-x-4 mt-4 md:mt-0">
                    <span class="text-gray-700 text-sm mb-2 md:mb-0">Bienvenido, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

        @endif

    <div class="container">
        <div class="chat-box">
            <div class="chat-header">
                <h3>Chat con el Técnico</h3>
            </div>

            <div class="chat-messages" style="max-height: 400px; overflow-y: scroll;">
                @foreach ($mensajes as $mensaje)
                    <div class="message {{ $mensaje->usuario_id == auth()->id() ? 'message-client' : 'message-tecnico' }}">
                        <div class="message-content">
                            <p>{{ $mensaje->mensaje }}</p>
                        </div>
                        <div class="message-info">
                            <span>{{ $mensaje->usuario->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('chat.store', ['incidencia' => $incidencia->id]) }}" method="POST">
                @csrf
                <div class="input-group">
                    <textarea name="mensaje" class="form-control" placeholder="Escribe tu mensaje..." rows="2"></textarea>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
