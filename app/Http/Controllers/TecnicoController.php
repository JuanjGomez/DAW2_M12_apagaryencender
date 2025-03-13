<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    public function index()
    {
        // Obtener las incidencias asignadas al técnico
        $incidencias = Auth::user()->incidenciasTecnico;
        
        return view('tecnico.index', compact('incidencias'));
    }
}
