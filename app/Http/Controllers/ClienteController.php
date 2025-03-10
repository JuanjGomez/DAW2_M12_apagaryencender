<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\User;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incidencia;

class ClienteController extends Controller
{
    // Mostrar el formulario para crear una nueva incidencia
    public function create()
    {
        // Obtener todas las categorías y subcategorías
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();

        // Pasar las categorías y subcategorías a la vista
        return view('cliente.create', compact('categorias', 'subcategorias'));

    }

    // Guardar una nueva incidencia
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'categoria_id' => 'required',
            'subcategoria_id' => 'required',
            'descripcion' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        // Subir imagen si se ha proporcionado
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('incidencias', 'public');
        } else {
            $imagenPath = null;
        }
    
        // Obtener el ID de la sede asociada al usuario autenticado
        $sede_id = Auth::user()->sede_id; // Obtener la sede del usuario autenticado
    
        // Verificar si el usuario tiene asignada una sede
        if (!$sede_id) {
            return back()->withErrors(['error' => 'El usuario no tiene una sede asignada.']);
        }
    
        // Crear la incidencia
        Incidencia::create([   
            'cliente_id' => Auth::id(),
            'categoria_id' => $request->categoria_id,
            'subcategoria_id' => $request->subcategoria_id,
            'descripcion' => $request->descripcion,
            'imagen' => $imagenPath,
            'estado_id' => 1, // Asumimos que el estado inicial es "activo" o lo que sea adecuado
            'sede_id' => $sede_id, // Asumimos que el cliente tiene sede asociada
        ]);
    
        // Redirigir al cliente con un mensaje de éxito
        return redirect()->route('cliente.index')->with('success', 'Incidencia creada con éxito');
    }
    
    // Muestra las incidencias del cliente
    public function index(Request $request)
    {
        $query = Incidencia::where('cliente_id', Auth::id());

        // Filtro por estado
        if ($request->has('estado') && in_array($request->estado, ['activo', 'inactivo', 'pendiente'])) {
            $query->where('estado', $request->estado);
        }

        // Filtro por incidencias resueltas (excluirlas)
        if ($request->has('resueltas') && $request->resueltas == 'no') {
            $query->where('estado', '!=', 'resuelta');
        }

        // Filtro de ordenación por fecha
        if ($request->has('orden')) {
            $query->orderBy('fecha_creacion', $request->orden);
        }

        // Obtener las incidencias filtradas
        $incidencias = $query->get();

        // Pasar las incidencias a la vista
        return view('cliente.index', compact('incidencias'));
    }

    // Mostrar detalles de una incidencia
    public function show($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        return view('cliente.show', compact('incidencia'));
    }
}
