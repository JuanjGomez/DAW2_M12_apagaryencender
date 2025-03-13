<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sede;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|max:120',
            'password' => 'required|max:255',
        ]);

        // Verificar si el usuario existe
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No existe un usuario con este correo electrónico.',
            ])->withInput();
        }

        // Intentar autenticar
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $name = $user->name;
            $role = $user->role->nombre;

            session()->flash('loginSuccess', "¡Bienvenido $name!");

            Log::info('Usuario autenticado', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $role
            ]);

            // Redirigir según el rol
            switch ($role) {
                case 'administrador':
                    return redirect()->route('admin.index');
                case 'tecnico':
                    return redirect()->route('tecnico.index');
                case 'gestor':
                    return redirect()->route('gestor.index');
                case 'cliente':
                    return redirect()->route('cliente.index');
                default:
                    return redirect()->route('login');
            }
        }

        // Si la autenticación falló
        Log::warning('Intento de login fallido', [
            'email' => $request->email,
            'user_exists' => $user ? 'yes' : 'no'
        ]);

        return back()->withErrors([
            'password' => 'La contraseña es incorrecta.',
        ])->withInput();
    }

    public function showRegisterForm(){
        $sedes = Sede::all();
        return view('register', compact('sedes'));
    }

    public function register(Request $request){
        try {
            DB::beginTransaction();

            // Validación
            try {
                $request->validate([
                    'name' => 'required|string|min:3|max:30',
                    'email' => 'required|string|email|max:120|unique:users',
                    'password' => 'required|string|min:8|max:100|confirmed',
                    'sede_id' => 'required|integer|exists:sedes,id',
                ]);
            } catch (ValidationException $e) {
                DB::rollBack();
                if (isset($e->errors()['email'])) {
                    session()->flash('emailDuplicado', 'Este correo electrónico ya está registrado en el sistema');
                    return back()->withInput();
                }
                return back()->withErrors($e->errors())->withInput();
            }

            $clienteRole = Role::where('nombre', 'cliente')->first();

            // Crear el usuario cliente
            $usuario = User::create([
                'name' => $request->name,
                'email'=> $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $clienteRole->id,
                'sede_id' => $request->sede_id,
            ]);

            $username = $usuario->name;

            Auth::login($usuario);
            session()->flash('loginSuccess', "¡Bienvenido $username!");
            DB::commit();
            return redirect()->route('cliente.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en registro de usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al registrar el usuario. Por favor, inténtelo de nuevo.');
            return back()->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
