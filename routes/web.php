<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
    Route::get('/admin', [AdminController::class, 'indexUsers'])->name('admin.index');
    Route::post('/admin/createUsers', [AdminController::class, 'storeUser'])->name('admin.store');
    Route::put('/admin/updateUsers/{id}', [AdminController::class, 'updateUser'])->name('admin.update');
    Route::delete('/admin/deleteUsers/{id}', [AdminController::class, 'destroyUser'])->name('admin.destroy');

    // Rutas para cliente
    Route::get('/cliente', function () {
        return view('cliente.index');
    })->name('cliente.index');

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
