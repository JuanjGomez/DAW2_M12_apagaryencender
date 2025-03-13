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
        // Obtener datos necesarios
        $clientes = User::whereHas('role', function($query) {
            $query->where('nombre', 'cliente');
        })->get();

        $tecnicos = User::whereHas('role', function($query) {
            $query->where('nombre', 'tecnico');
        })->get();

        $categorias = Categoria::all();
        $estados = Estado::all();
        $prioridades = Prioridad::all();
        $sedes = Sede::all();

        // Crear 20 incidencias de prueba
        for ($i = 0; $i < 20; $i++) {
            $categoria = $categorias->random();
            $subcategorias = Subcategoria::where('categoria_id', $categoria->id)->get();
            
            $cliente = $clientes->random();
            $sede = $cliente->sede;
            $tecnico = $tecnicos->where('sede_id', $sede->id)->random();

            Incidencia::create([
                'cliente_id' => $cliente->id,
                'tecnico_id' => $tecnico->id,
                'categoria_id' => $categoria->id,
                'subcategoria_id' => $subcategorias->random()->id,
                'estado_id' => $estados->random()->id,
                'prioridad_id' => $prioridades->random()->id,
                'sede_id' => $sede->id,
                'descripcion' => 'Incidencia de prueba #' . ($i + 1) . ': ' . fake()->sentence(),
                'resolucion' => fake()->boolean(30) ? fake()->paragraph() : null,
                'fecha_creacion' => fake()->dateTimeBetween('-1 month', 'now'),
                'fecha_resolucion' => fake()->boolean(20) ? fake()->dateTimeBetween('-1 week', 'now') : null,
            ]);
        }
    }
}
