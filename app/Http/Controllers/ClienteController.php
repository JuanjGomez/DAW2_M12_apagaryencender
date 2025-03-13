<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\User;
use App\Models\Sede;
use App\Models\Chat;
use App\Models\Mensaje;
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
    
        if ($request->hasFile('imagen')) {
            // Obtener el archivo de la imagen
            $imagen = $request->file('imagen');
            
            // Generar un nombre único para la imagen
            $nombreImagen = time() . '-' . $imagen->getClientOriginalName();
        
            // Mover la imagen a public/img/incidencias
            $imagen->move(public_path('img/incidencias'), $nombreImagen);
            
            // Guardar la ruta relativa en la base de datos (por ejemplo: img/incidencias/imagen123.jpg)
            $imagenPath = 'img/incidencias/' . $nombreImagen;
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
        // Empezamos la consulta filtrando por el cliente autenticado
        $query = Incidencia::where('cliente_id', Auth::id());
    
        // Filtro por estado (usando estado_id)
        if ($request->has('estado') && in_array($request->estado, [1, 2, 3])) { // Suponiendo que los estados tienen ID: 1 = activo, 2 = inactivo, 3 = pendiente
            $query->where('estado_id', $request->estado);
        }
    
        // Filtro por incidencias no resueltas (excluir las resueltas)
        if ($request->has('resueltas') && $request->resueltas == 'no') {
            $query->whereNull('fecha_resolucion');
        }
    
        // Filtro de ordenación por fecha de creación
        if ($request->has('orden')) {
            $query->orderBy('fecha_creacion', $request->orden);
        }
    
        // Obtener las incidencias filtradas por los criterios anteriores
        $incidencias = $query->whereIn('estado_id', [1, 2, 3])
                            ->get();

        // Obtener solo las incidencias resueltas (estado_id == 4)
        $incidenciasResueltas = Incidencia::where('cliente_id', Auth::id())
                                        ->where('estado_id', 4) // Estado resuelto
                                        ->get();
    
        // Pasar las incidencias a la vista
        return view('cliente.index', compact('incidencias','incidenciasResueltas'));
    }

        // Método para cerrar una incidencia
        public function cerrar(Incidencia $incidencia)
        {
            // Verificar que la incidencia esté resuelta antes de cerrarla
            if ($incidencia->estado_id == 4) { // Suponiendo que 4 es el estado de "resuelta"
                // Cambiar el estado a "cerrada" (por ejemplo, estado_id = 5)
                $incidencia->estado_id = 5; // Cambiar a "cerrada"
                $incidencia->save();
    
                // Redirigir con un mensaje de éxito
                return redirect()->route('cliente.index')->with('success', 'Incidencia cerrada correctamente.');
            }
    
            // Si no está resuelta, no permitir cerrarla y mostrar un mensaje de error
            return redirect()->route('cliente.index')->with('error', 'Solo se pueden cerrar incidencias resueltas.');
        }
    

    // Mostrar detalles de una incidencia
    public function show($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        return view('cliente.show', compact('incidencia'));
    }

    public function showChat($incidenciaId)
{
    // Obtener la incidencia
    $incidencia = Incidencia::findOrFail($incidenciaId);

    // Obtener el chat relacionado con la incidencia
    $chat = $incidencia->chat;

    // Si no existe un chat, crear uno (si lo deseas)
    if (!$chat) {
        $chat = Chat::create(['incidencia_id' => $incidencia->id]);
    }

    // Obtener los mensajes del chat
    $mensajes = $chat->mensajes()->with('usuario')->get();

    // Pasar los datos a la vista
    return view('cliente.chat', compact('incidencia', 'chat', 'mensajes'));
}

}
