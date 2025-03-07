<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

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
            $username = $user->username;
            $rol_id = $user->rol_id;
            session()->flash('success', "Bienvenido $username!");

            if ($rol_id == 1) {
                return redirect()->route('home');
            } else if ($rol_id == 2) {
                return redirect()->route('');
            } else if ($rol_id == 3) {
                return redirect()->route('');
            } else if ($rol_id == 4) {
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:120|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'sede_id' => 'required|integer|exists:sedes,id',
        ]);

        // Crear el usuario cliente por defecto
        $usuario = User::create([
            'name' => $request->username,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
            'sede_id' => $request->sede_id,
            'rol_id' => 4,
        ]);

        $username = $usuario->username;

        Auth::login($usuario);
        session()->flash('success', "Bienvenido $username!");
        DB::commit();
        return redirect()->route('');
    }

    public function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
