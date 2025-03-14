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

    public function filtrarIncidencias(Request $request)
    {
        try {
            $query = Auth::user()->incidenciasTecnico()
                ->with(['cliente', 'categoria', 'subcategoria', 'estado', 'prioridad']);

            // Filtro por prioridad
            if ($request->filled('prioridad')) {
                $query->where('prioridad_id', $request->prioridad);
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado_id', $request->estado);
            }

            // Ordenar por fecha
            $orden = $request->orden === 'asc' ? 'asc' : 'desc';
            $query->orderBy('fecha_creacion', $orden);

            $incidencias = $query->get();

            return response()->json([
                'success' => true,
                'incidencias' => $incidencias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar las incidencias: ' . $e->getMessage()
            ], 500);
        }
    }
}
