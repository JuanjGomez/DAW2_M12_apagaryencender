<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    public function index()
    {
        $tecnico = Auth::user();
        $incidencias = Incidencia::where('tecnico_id', $tecnico->id)
            ->with(['cliente', 'estado', 'categoria', 'subcategoria', 'prioridad'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tecnico.index', compact('incidencias'));
    }

    public function cambiarEstado(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'estado_id' => 'required|exists:estados,id'
        ]);

        // Verificar que el técnico es el asignado a la incidencia
        if ($incidencia->tecnico_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para modificar esta incidencia');
        }

        $incidencia->estado_id = $request->estado_id;

        // Si el estado es "Resolta", actualizar fecha_resolucion
        if ($request->estado_id == Estado::where('nombre', 'Resolta')->first()->id) {
            $incidencia->fecha_resolucion = now();
        }

        $incidencia->save();

        return back()->with('success', 'Estado de la incidencia actualizado correctamente');
    }

    public function mostrarIncidencia(Incidencia $incidencia)
    {
        // Verificar que el técnico es el asignado a la incidencia
        if ($incidencia->tecnico_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para ver esta incidencia');
        }

        $estados = Estado::all();
        return view('tecnico.detalle', compact('incidencia', 'estados'));
    }

    public function actualizarResolucion(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'resolucion' => 'required|string'
        ]);

        // Verificar que el técnico es el asignado a la incidencia
        if ($incidencia->tecnico_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para modificar esta incidencia');
        }

        $incidencia->resolucion = $request->resolucion;
        $incidencia->save();

        return back()->with('success', 'Resolución actualizada correctamente');
    }
}
