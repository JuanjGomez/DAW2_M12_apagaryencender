<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($incidenciaId)
    {
        // Recuperar la incidencia por ID
        $incidencia = Incidencia::findOrFail($incidenciaId);

        // Retornar una vista con la incidencia (puedes personalizarla según tus necesidades)
        return view('chat.show', compact('incidencia'));
    }
}
