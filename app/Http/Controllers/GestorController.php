<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\User;
use App\Models\Prioridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestorController extends Controller
{
    public function index(Request $request)
    {
        // Obtener la sede del gestor autenticado
        $sede_id = Auth::user()->sede_id;
        
        // Iniciar la consulta de incidencias de esa sede
        $query = Incidencia::where('sede_id', $sede_id);
        
        // Filtrar por prioridad
        if ($request->has('prioridad_id') && $request->prioridad_id) {
            $query->where('prioridad_id', $request->prioridad_id);
        }
        
        // Filtrar por técnico
        if ($request->has('tecnico_id') && $request->tecnico_id) {
            $query->where('tecnico_id', $request->tecnico_id);
        }
        
        // Ocultar resueltas si se solicita
        if ($request->has('ocultar_resueltas')) {
            $query->whereNotIn('estado_id', [4, 5]); // No mostrar resueltas ni cerradas
        }
        
        // Ordenar por fecha
        $orden = $request->has('fecha_entrada') ? $request->fecha_entrada : 'desc';
        $query->orderBy('fecha_creacion', $orden);
        
        // Obtener incidencias con relaciones
        $incidencias = $query->with(['cliente', 'tecnico', 'estado', 'prioridad'])->get();
        
        // Obtener técnicos de la sede para el selector
        $tecnicos = User::where('sede_id', $sede_id)
                        ->where('role_id', 2) // Técnicos
                        ->get();
        
        // Obtener prioridades para el selector
        $prioridades = Prioridad::all();
        
        return view('gestor.index', compact('incidencias', 'tecnicos', 'prioridades'));
    }
    
    public function asignarTecnico(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        
        // Verificar que la incidencia pertenece a la sede del gestor
        if ($incidencia->sede_id != Auth::user()->sede_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        $incidencia->tecnico_id = $request->tecnico_id;
        
        // Si se asigna un técnico, cambiar el estado a "Asignada" (ID 2)
        if ($request->tecnico_id) {
            $incidencia->estado_id = 2; // Asignada
        } else {
            $incidencia->estado_id = 1; // Sin asignar
        }
        
        $incidencia->save();
        
        return response()->json(['success' => true]);
    }
    
    public function actualizarPrioridad(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        
        // Verificar que la incidencia pertenece a la sede del gestor
        if ($incidencia->sede_id != Auth::user()->sede_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        $incidencia->prioridad_id = $request->prioridad_id;
        $incidencia->save();
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $incidencia = Incidencia::with(['cliente', 'tecnico', 'estado', 'prioridad', 'categoria', 'subcategoria'])
                                ->findOrFail($id);
        
        // Verificar que la incidencia pertenece a la sede del gestor
        if ($incidencia->sede_id != Auth::user()->sede_id) {
            return abort(403);
        }
        
        // Obtener técnicos de la sede para el selector
        $tecnicos = User::where('sede_id', Auth::user()->sede_id)
                        ->where('role_id', 2) // Técnicos
                        ->get();
        
        // Obtener prioridades para el selector
        $prioridades = Prioridad::all();
        
        return view('gestor.show', compact('incidencia', 'tecnicos', 'prioridades'));
    }

    public function incidenciasPorTecnico()
    {
        $sede_id = Auth::user()->sede_id;
        
        // Obtener técnicos de la sede con sus incidencias
        $tecnicos = User::where('sede_id', $sede_id)
                        ->where('role_id', 2)
                        ->with(['incidencias' => function($query) {
                            $query->with(['estado', 'prioridad'])
                                 ->orderBy('fecha_creacion', 'desc');
                        }])
                        ->get();
        
        return view('gestor.incidencias_tecnico', compact('tecnicos'));
    }

    public function cerrarIncidencia($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        if ($incidencia->cliente_id == Auth::id()) {
            $incidencia->estado_id = 5; // Suponiendo que 5 es el ID para "Cerrada"
            $incidencia->fecha_resolucion = now();
            $incidencia->save();
        }
        return redirect()->route('cliente.index')->with('success', 'Incidencia cerrada con éxito');
    }
}
