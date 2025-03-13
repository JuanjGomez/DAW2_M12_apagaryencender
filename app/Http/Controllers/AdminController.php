<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;
use App\Models\Sede;

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
                'message' => 'Error al crear el usuario',
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

    public function destroyUser($id) {
        try {
            $user = User::findOrFail($id);

            // Verificar si no es el ultimo administrador
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

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
