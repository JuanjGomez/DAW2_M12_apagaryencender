<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sede;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
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

        if (Auth::attempt($request->only('email', 'password'))) {

            $request->session()->regenerate();

            $user = Auth::user();
            $name = $user->name;
            $role_id = $user->role_id;

            session()->flash('loginSuccess', "¡Bienvenido $name!");

            // Redirigir según el rol
            switch ($role_id) {
                case 1: // Administrador
                    return redirect()->route('admin.index');
                case 2: // Tecnico
                    return redirect()->route('tecnico.index');
                case 3: // Gestor
                    return redirect()->route('gestor.index');
                case 4: // Cliente
                    return redirect()->route('cliente.index');
                default:
                    return redirect()->route('login');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
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
                // Si el error es por email duplicado
                if (isset($e->errors()['email'])) {
                    session()->flash('emailDuplicado', 'Este correo electrónico ya está registrado en el sistema');
                    return back()->withInput();
                }
                // Para otros errores de validación
                return back()->withErrors($e->errors())->withInput();
            }

            // Crear el usuario cliente por defecto
            $usuario = User::create([
                'name' => $request->name,
                'email'=> $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 4,
                'sede_id' => $request->sede_id,
            ]);

            $username = $usuario->name;

            Auth::login($usuario);
            session()->flash('loginSuccess', "¡Bienvenido $username!");
            DB::commit();
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
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