<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TecnicoController;
use App\Models\User;
use App\Models\Incidencia;
use App\Models\Mensaje;
use App\Models\Subcategoria;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\TecnicoController;


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
    // Rutas para administrador | Gestion de usuarios
    Route::get('/admin', [AdminController::class, 'indexUsers'])->name('admin.index');
    Route::post('/admin/createUsers', [AdminController::class, 'storeUser'])->name('admin.store');
    Route::put('/admin/updateUsers/{id}', [AdminController::class, 'updateUser'])->name('admin.update');
    Route::delete('/admin/deleteUsers/{id}', [AdminController::class, 'destroyUser'])->name('admin.destroy');
    // | Gestion de categorias de incidencias
    Route::get('/admin/categorias', [AdminController::class, 'indexCategorias'])->name('admin.categorias');
    Route::post('/admin/createCategorias', [AdminController::class, 'storeCategorias'])->name('admin.storeCategorias');
    Route::put('/admin/categorias/{id}', [AdminController::class, 'updateCategorias'])->name('admin.updateCategorias');
    Route::delete('/admin/deleteCategorias/{id}', [AdminController::class, 'destroyCategorias'])->name('admin.destroyCategorias');

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

        // Ruta para cerrar una incidencia desde el cliente
        Route::patch('/incidencia/{incidencia}/cerrar', [ClienteController::class, 'cerrar'])->name('incidencia.cerrar');

        // Ruta para mostrar el chat de una incidencia
        Route::get('/chat/{incidencia}', [ChatController::class, 'show'])->name('chat.show');

        // Ruta para almacenar un mensaje en el chat
        Route::post('/chat/{incidencia}/store', [ChatController::class, 'store'])->name('chat.store');



        Route::post('/incidencias', [ClienteController::class, 'store'])->name('cliente.store');

        Route::patch('/incidencias/{id}/devolver', [ClienteController::class, 'devolver'])->name('incidencia.devolver');

        Route::post('/cliente/filtrar', [ClienteController::class, 'filtrar'])->name('cliente.filtrar');

        Route::get('/subcategorias/{categoriaId}', function ($categoriaId) {
            $subcategorias = Subcategoria::where('categoria_id', $categoriaId)->get();
            return response()->json(['subcategorias' => $subcategorias]);
        });
        
        
    // Rutas para gestor
    Route::middleware(['auth'])->prefix('gestor')->name('gestor.')->group(function () {
        Route::get('/', [GestorController::class, 'index'])->name('index');
        Route::get('/incidencia/{id}', [GestorController::class, 'show'])->name('show');
        Route::post('/incidencia/{id}/asignar-tecnico', [GestorController::class, 'asignarTecnico'])->name('asignar.tecnico');
        Route::post('/incidencia/{id}/actualizar-prioridad', [GestorController::class, 'actualizarPrioridad'])->name('actualizar.prioridad');
        Route::get('/incidencias-por-tecnico', [GestorController::class, 'incidenciasPorTecnico'])->name('incidencias.tecnico');
        Route::post('/filtrar', [GestorController::class, 'filtrarIncidencias'])->name('filtrar');
        Route::post('/incidencias-tecnico/filtrar', [GestorController::class, 'filtrarIncidenciasPorTecnico'])
             ->name('incidencias-tecnico.filtrar');
    });

    // Rutas para técnico
    Route::get('/tecnico', [TecnicoController::class, 'index'])->name('tecnico.index');
    Route::get('/tecnico/incidencia/{incidencia}', [TecnicoController::class, 'mostrarIncidencia'])->name('tecnico.mostrar');
    Route::post('/tecnico/incidencia/{incidencia}/estado', [TecnicoController::class, 'cambiarEstado'])->name('tecnico.cambiarEstado');
    Route::post('/tecnico/incidencia/{incidencia}/resolucion', [TecnicoController::class, 'actualizarResolucion'])->name('tecnico.actualizarResolucion');

});
// ------------------------------------------------------------------------------------------------------------------------


Route::get('/tecnico/incidencias/{incidencia}/detalles', [TecnicoController::class, 'obtenerDetalles'])->name('tecnico.incidencias.detalles');
Route::post('/tecnico/incidencias/{incidencia}/actualizar-estado', [TecnicoController::class, 'actualizarEstado'])->name('tecnico.incidencias.actualizar-estado');

Route::post('/tecnico/filtrar-incidencias', [TecnicoController::class, 'filtrarIncidencias'])
    ->name('tecnico.filtrar-incidencias');
