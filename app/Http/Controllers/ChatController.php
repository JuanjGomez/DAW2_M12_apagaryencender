<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Mostrar los mensajes del chat para una incidencia
    public function show($incidenciaId)
    {
        $incidencia = Incidencia::findOrFail($incidenciaId); // Obtener la incidencia
        $mensajes = Mensaje::where('incidencia_id', $incidenciaId)->get(); // Obtener los mensajes relacionados

        return view('chat.show', compact('incidencia', 'mensajes'));
    }

    // Almacenar un nuevo mensaje en el chat
    public function store(Request $request, $incidenciaId)
    {
        $request->validate([
            'mensaje' => 'required|string', // Validar el mensaje
        ]);

        // Crear un nuevo mensaje
        Mensaje::create([
            'usuario_id' => Auth::id(),
            'incidencia_id' => $incidenciaId,
            'mensaje' => $request->mensaje,
        ]);

        return redirect()->route('chat.show', $incidenciaId); // Redirigir al chat con los nuevos mensajes
    }
}
