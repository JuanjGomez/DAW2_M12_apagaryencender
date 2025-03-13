<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller {
    public function show($incidenciaId) {
        $incidencia = Incidencia::with('chat.mensajes.usuario')->findOrFail($incidenciaId);

        if (!$incidencia->chat) {
            // Si no hay chat, lo creamos
            $chat = Chat::create(['incidencia_id' => $incidencia->id]);
            $incidencia->update(['chat_id' => $chat->id]);
        } else {
            $chat = $incidencia->chat;
        }

        return view('chat.show', compact('chat', 'incidencia'));
    }

    public function enviarMensaje(Request $request, $chatId) {
        $request->validate(['mensaje' => 'required|string']);

        Mensaje::create([
            'chat_id' => $chatId,
            'usuario_id' => Auth::id(),
            'mensaje' => $request->mensaje,
        ]);

        return back();
    }
}
