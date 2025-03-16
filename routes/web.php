<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ChatController;
use App\Models\User;
use App\Models\Incidencia;
use App\Models\Mensaje;
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

        // Ruta para ver los detalles de una incidencia
        Route::get('/cliente/{id}', [ClienteController::class, 'show'])->name('cliente.show');

        // Ruta para cerrar una incidencia desde el cliente
        Route::patch('/incidencia/{incidencia}/cerrar', [ClienteController::class, 'cerrar'])->name('incidencia.cerrar');

        // Ruta para mostrar el chat de una incidencia
        Route::get('/chat/{incidencia}', [ChatController::class, 'show'])->name('chat.show');

        // Ruta para almacenar un mensaje en el chat
        Route::post('/chat/{incidencia}/store', [ChatController::class, 'store'])->name('chat.store');

        Route::post('/incidencias', [ClienteController::class, 'store'])->name('cliente.store');

        Route::patch('/incidencias/{id}/devolver', [ClienteController::class, 'devolver'])->name('incidencia.devolver');


        
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
