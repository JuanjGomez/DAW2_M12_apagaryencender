<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Incidencia #{{ $incidencia->id }}</title>
</head>
<body>
    <h1>Chat de la Incidencia #{{ $incidencia->id }}</h1>

    <div>
        <h3>Descripción: {{ $incidencia->descripcion }}</h3>
        <p><strong>Estado:</strong> {{ $incidencia->estado->nombre }}</p>
        <p><strong>Técnico:</strong> {{ $incidencia->tecnico ? $incidencia->tecnico->name : 'No asignado' }}</p>
        <p><strong>Fecha de creación:</strong> {{ $incidencia->fecha_creacion }}</p>
        <p><strong>Fecha de resolución:</strong> {{ $incidencia->fecha_resolucion ?? 'Aún no resuelta' }}</p>
    </div>

    <!-- Aquí puedes agregar la funcionalidad de chat, por ejemplo -->
    <div>
        <h2>Conversaciones</h2>
        <!-- Aquí podrías agregar el listado de mensajes del chat -->
        <!-- Este es solo un ejemplo -->
        <ul>
            <!-- Suponiendo que tienes mensajes para esta incidencia -->
            @foreach($incidencia->mensajes as $mensaje)
                <li>{{ $mensaje->usuario->name }}: {{ $mensaje->contenido }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Aquí podrías agregar un formulario para enviar mensajes, si lo deseas -->
</body>
</html>
