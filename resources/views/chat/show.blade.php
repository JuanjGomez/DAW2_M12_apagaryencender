<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Incidencia</title>
    <link rel="stylesheet" href="{{ asset('css/chatCliente.css') }}"> <!-- Enlazamos el archivo CSS -->
</head>
<body>
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
