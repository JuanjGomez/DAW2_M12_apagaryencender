<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


// Rutas de acceso abierto -----------------------------------------------------------------------------------------------

//Ruta formulario de sesion
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);

// Ruta formulario de registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Ruta para salir de sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------------------------------------------------------------------------------------------

// Rutas securizadas ------------------------------------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Rutas para administrador
    Route::get('/admin', function () {
        $users = User::with(['role', 'sede'])->get();
        return view('admin.index', compact('users'));
    })->name('admin.index');

    // Rutas para cliente
    Route::get('/cliente', function () {
        return view('cliente.index');
    })->name('cliente.index');

        // Ruta para mostrar las incidencias del cliente
        Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente.index');

        // Ruta para mostrar el formulario de creación de incidencia
        Route::get('/cliente/create', [ClienteController::class, 'create'])->name('cliente.create');

        // Ruta para almacenar la incidencia creada
        Route::post('/cliente', [ClienteController::class, 'store'])->name('cliente.store');

        // Ruta para ver los detalles de una incidencia
        Route::get('/cliente/{id}', [ClienteController::class, 'show'])->name('cliente.show');

    
    // Rutas para gestor
    Route::get('/gestor', function () {
        $incidencias = Auth::user()->sede->incidencias;
        return view('gestor.index', compact('incidencias'));
    })->name('gestor.index');

    // Rutas para técnico
    Route::get('/tecnico', function () {
        return view('tecnico.index');
    })->name('tecnico.index');
});
// ------------------------------------------------------------------------------------------------------------------------
