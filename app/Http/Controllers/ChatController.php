<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Mensaje;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Mostrar el chat de una incidencia
    public function show(Incidencia $incidencia)
    {
        // Obtener el chat asociado a la incidencia
        $chat = $incidencia->chat;
    
        // Si no existe el chat, lo creamos
        if (!$chat) {
            $chat = Chat::create(['incidencia_id' => $incidencia->id]);
            $incidencia->chat_id = $chat->id;
            $incidencia->save();
        }
    
        // Obtener todos los mensajes del chat
        $mensajes = $chat->mensajes()->with('usuario')->get();
    
        // Retornar la vista del chat
        return view('chat.show', compact('incidencia', 'mensajes'));
    }
    
    
    // Almacenar un nuevo mensaje en el chat
    public function store(Request $request, Incidencia $incidencia)
    {
        // Validar el mensaje
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Verificar si la incidencia tiene un chat
        $chat = $incidencia->chat;
        if (!$chat) {
            // Si no tiene chat, lo creamos
            $chat = Chat::create(['incidencia_id' => $incidencia->id]);
            $incidencia->chat_id = $chat->id;
            $incidencia->save();
        }

        // Crear el nuevo mensaje en el chat
        $mensaje = new Mensaje();
        $mensaje->chat_id = $chat->id;
        $mensaje->usuario_id = Auth::id(); // El usuario que está enviando el mensaje
        $mensaje->mensaje = $request->mensaje;
        $mensaje->save();

        // Redirigir al chat con el nuevo mensaje
        return redirect()->route('chat.show', ['incidencia' => $incidencia->id]);
    }
}
