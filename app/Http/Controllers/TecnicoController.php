<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    public function index()
    {
        // Obtener las incidencias asignadas al técnico
        $incidencias = Auth::user()->incidenciasTecnico;
        
        return view('tecnico.index', compact('incidencias'));
    }

    public function obtenerDetalles(Incidencia $incidencia)
    {
        // Verificar que la incidencia pertenece al técnico actual
        if ($incidencia->tecnico_id !== Auth::id()) {
            abort(403);
        }

        return view('tecnico.detalles-incidencia', [
            'incidencia' => $incidencia->load(['cliente', 'categoria', 'subcategoria', 'estado', 'prioridad'])
        ]);
    }

    public function actualizarEstado(Request $request, Incidencia $incidencia)
    {
        // Verificar que la incidencia pertenece al técnico actual
        if ($incidencia->tecnico_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'estado_id' => 'required|exists:estados,id'
        ]);

        $incidencia->update([
            'estado_id' => $validated['estado_id']
        ]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
