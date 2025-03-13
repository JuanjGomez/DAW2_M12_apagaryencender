<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Incidencia;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Estado;
use App\Models\Prioridad;
use App\Models\Sede;

class IncidenciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios, categorías, subcategorías, estados, prioridades y sedes de ejemplo
        $cliente = User::where('role_id', 4)->first(); // Cliente
        $tecnico = User::where('role_id', 2)->first(); // Técnico
        $categoria = Categoria::first();
        $subcategoria = Subcategoria::first();
        $estadoAsignada = Estado::where('nombre', 'Asignada')->first();
        $prioridadAlta = Prioridad::where('nombre', 'Alta')->first();
        $sede = Sede::first();

        // Crear incidencias de ejemplo
        Incidencia::create([
            'cliente_id' => $cliente->id,
            'tecnico_id' => $tecnico->id,
            'categoria_id' => $categoria->id,
            'subcategoria_id' => $subcategoria->id,
            'estado_id' => $estadoAsignada->id,
            'prioridad_id' => $prioridadAlta->id,
            'descripcion' => 'Problema con el sistema operativo.',
            'fecha_creacion' => now(),
            'sede_id' => $sede->id,
        ]);

        Incidencia::create([
            'cliente_id' => $cliente->id,
            'tecnico_id' => $tecnico->id,
            'categoria_id' => $categoria->id,
            'subcategoria_id' => $subcategoria->id,
            'estado_id' => $estadoAsignada->id,
            'prioridad_id' => $prioridadAlta->id,
            'descripcion' => 'Error en la aplicación de gestión.',
            'fecha_creacion' => now(),
            'sede_id' => $sede->id,
        ]);
    }
}
