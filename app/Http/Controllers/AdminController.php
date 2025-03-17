<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Sede;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Mensaje;
use App\Models\Chat;
use App\Models\Incidencia;

class AdminController extends Controller
{
    protected function applyFiltersUsers(Request $request)
    {
        $query = User::query()->with(['role', 'sede']);

        // Filtro de busqueda por nombre o email
        if($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filtro por sede
        if ($request->filled('sede')) {
            $query->where('sede_id', $request->sede);
        }

        return $query->paginate(10)->withQueryString();
    }

    public function indexUsers(Request $request)
    {
        $users = $this->applyFiltersUsers($request);

        if ($request->ajax()) {
            return view('admin.users_list', compact('users'));
        }

        $roles = Role::all();
        $sedes = Sede::all();
        return view('admin.index', compact('users', 'roles', 'sedes'));
    }

    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
                'role_id' => 'required|exists:roles,id',
                'sede_id' => 'required|exists:sedes,id'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'sede_id' => $validated['sede_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users,email,' . $id,
                'role_id' => 'required|exists:roles,id',
                'sede_id' => 'required|exists:sedes,id'
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'sede_id' => $validated['sede_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'user' => $user
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyUser($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Verificar si no es el último administrador
            if ($user->role->nombre === 'administrador') {
                $adminCount = User::whereHas('role', function($query) {
                    $query->where('nombre', 'administrador');
                })->count();

                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el último administrador'
                    ], 422);
                }
            }

            // Verificar si el usuario tiene incidencias asociadas
            $hasIncidencias = Incidencia::where('cliente_id', $id)
                ->orWhere('tecnico_id', $id)
                ->exists();

            if ($hasIncidencias) {
                // Actualizar las incidencias en lugar de eliminarlas
                Incidencia::where('cliente_id', $id)->update(['cliente_id' => null]);
                Incidencia::where('tecnico_id', $id)->update(['tecnico_id' => null]);
            }

            // Eliminar mensajes asociados
            Mensaje::where('usuario_id', $id)->delete();

            // Finalmente eliminar el usuario
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function applyFiltersCategorias(Request $request)
    {
        $query = Categoria::query()->with('subcategorias');

        if ($request->filled('categoria')) {
            $query->where('id', $request->categoria);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%")
                ->orWhereHas('subcategorias', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%");
                });
            });
        }

        return $query->paginate(10)->withQueryString();
    }

    public function indexCategorias(Request $request)
    {
        $categoriasFiltradas = $this->applyFiltersCategorias($request);

        if ($request->ajax()) {
            return view('admin.categorias_list', compact('categoriasFiltradas'));
        }

        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();

        return view('admin.categorias', compact('categoriasFiltradas', 'categorias', 'subcategorias'));
    }

    public function storeCategorias(Request $request)
    {
        try{
            $validated = $request->validate([
                'nombre' => 'required|string|max:50',
                'subcategorias' => 'required|array|min:1',
                'subcategorias.*' => 'required|string|max:100'
            ]);

            $categoria = Categoria::create([
                'nombre' => $validated['nombre']
            ]);

            foreach ($validated['subcategorias'] as $subcategoriasNombre) {
                $categoria->subcategorias()->create([
                    'nombre' => $subcategoriasNombre
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCategorias(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'nombre' => 'required|string|max:50',
                'subcategorias' => 'required|array|min:1',
                'subcategorias.*' => 'required|string|max:100'
            ]);

            $categoria = Categoria::findOrFail($id);
            $categoria->update([
                'nombre' => $validated['nombre']
            ]);

            // Obtener IDs de subcategorías existentes
            $existingIds = $request->subcategoria_ids ?? [];

            // Actualizar o crear subcategorías
            foreach ($validated['subcategorias'] as $index => $nombre) {
                if (isset($existingIds[$index]) && $existingIds[$index]) {
                    // Actualizar subcategoría existente
                    $categoria->subcategorias()
                        ->where('id', $existingIds[$index])
                        ->update(['nombre' => $nombre]);
                } else {
                    // Crear nueva subcategoría
                    $categoria->subcategorias()->create(['nombre' => $nombre]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
