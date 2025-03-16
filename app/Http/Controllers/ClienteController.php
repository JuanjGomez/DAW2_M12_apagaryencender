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
    
// Guardar una nueva incidencia
public function store(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'descripcion' => 'required|string|max:255',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif',  // Validación de imagen
    ]);

    // Obtener el usuario autenticado (cliente)
    $cliente_id = Auth::id();  // Usamos Auth::id() para obtener el ID del usuario autenticado

    // Asignar un valor predeterminado para el estado y prioridad si no se envían
    $estado_id = 1;  // Supongamos que 1 es el estado predeterminado para nuevas incidencias
    $prioridad_id = $request->prioridad_id ?? null;  // Se puede dejar vacío si no se envía
    $sede_id = Auth::user()->sede_id;
    
    // Crear la incidencia
    $incidencia = new Incidencia();
    $incidencia->cliente_id = $cliente_id;  // Asignamos el cliente autenticado
    $incidencia->tecnico_id = null;  // Si no se asigna un técnico en el momento de la creación
    $incidencia->categoria_id = $request->categoria_id;
    $incidencia->subcategoria_id = $request->subcategoria_id;
    $incidencia->estado_id = $estado_id;  // Asignamos el estado predeterminado
    $incidencia->prioridad_id = $prioridad_id;  // Asignamos la prioridad si existe
    $incidencia->sede_id = $sede_id;  // Asignamos la sede asociada al cliente
    $incidencia->descripcion = $request->descripcion;

    // Subir imagen si se ha proporcionado
    if ($request->hasFile('imagen')) {
        // Obtener el archivo de la imagen
        $imagen = $request->file('imagen');
        
        // Generar un nombre único para la imagen
        $nombreImagen = time() . '-' . $imagen->getClientOriginalName();

        // Mover la imagen a public/img/incidencias
        $imagen->move(public_path('img/incidencias'), $nombreImagen);
        
        // Guardar la ruta relativa en la base de datos (por ejemplo: img/incidencias/imagen123.jpg)
        $incidencia->imagen = 'img/incidencias/' . $nombreImagen;
    }


    // Guardar la incidencia
    $incidencia->save();

    // Retornar la respuesta en formato JSON
    return response()->json([
        'success' => true,
        'message' => 'Incidencia creada con éxito',
        'incidencia' => $incidencia
    ]);
}


    // Muestra las incidencias del cliente
    public function index(Request $request)
    {
        // Empezamos la consulta filtrando por el cliente autenticado
        $query = Incidencia::where('cliente_id', Auth::id());
    
        // Obtener las incidencias filtradas por los criterios anteriores
        $incidencias = $query->whereIn('estado_id', [1, 2, 3,6])
                            ->get();

        // Obtener solo las incidencias resueltas (estado_id == 4)
        $incidenciasResueltas = Incidencia::where('cliente_id', Auth::id())
                                        ->where('estado_id', 4) // Estado resuelto
                                        ->get();

        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
    
        // Pasar las incidencias a la vista
        return view('cliente.index', compact('incidencias','incidenciasResueltas','categorias', 'subcategorias'));
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

public function devolver($id)
{
    // Buscar la incidencia por su ID
    $incidencia = Incidencia::findOrFail($id);

    // Cambiar el estado de la incidencia a "Devuelta"
    $incidencia->estado_id = 6;
    $incidencia->save();

    // Redirigir con un mensaje de éxito
    return redirect()->route('cliente.index')->with('success', 'Incidencia devuelta con éxito');
}

}
