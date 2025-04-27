<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incidencia;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Estado;
use App\Models\Prioridad;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Incidencia 1: Cliente Barcelona (Software)
        DB::table('incidencias')->insert([
            'cliente_id' => 2, // Cliente Barcelona
            'tecnico_id' => 3, // Técnico Barcelona
            'categoria_id' => 1, // Software
            'subcategoria_id' => 1, // Aplicación gestión administrativa
            'estado_id' => 4, // Sin asignar
            'prioridad_id' => 4, // Baja
            'sede_id' => 1, // Barcelona
            'descripcion' => 'La aplicación de gestión administrativa no responde al intentar abrirla.',
            'resolucion' => 'Estamos a tope, nos ha ido bien',
            'imagen' => null,
            'fecha_creacion' => Carbon::now(),
            'fecha_resolucion' => Carbon::now(),
            'chat_id' => null,
        ]);

        // Incidencia 2: Cliente Berlín (Hardware)
        DB::table('incidencias')->insert([
            'cliente_id' => 4, // Cliente Berlín
            'tecnico_id' => 5, // Técnico Berlín
            'categoria_id' => 2, // Hardware
            'subcategoria_id' => 2, // El ratolí no funciona
            'estado_id' => 2, // Asignada
            'prioridad_id' => 2, // Alta
            'sede_id' => 2, // Berlín
            'descripcion' => 'El ratón de mi computadora no responde, necesito asistencia urgente.',
            'resolucion' => null,
            'imagen' => null,
            'fecha_creacion' => Carbon::now(),
            'fecha_resolucion' => null,
            'chat_id' => null,
        ]);

        // Incidencia 3: Cliente Montreal (Hardware)
        DB::table('incidencias')->insert([
            'cliente_id' => 6, // Cliente Montreal
            'tecnico_id' => 6, // Técnico Montreal
            'categoria_id' => 2, // Hardware
            'subcategoria_id' => 3, // Monitor no se enciende
            'estado_id' => 3, // En trabajo
            'prioridad_id' => 1, // Urgente
            'sede_id' => 3, // Montreal
            'descripcion' => 'El monitor no se enciende, he intentado varias veces pero no responde.',
            'resolucion' => null,
            'imagen' => null,
            'fecha_creacion' => Carbon::now(),
            'fecha_resolucion' => null,
            'chat_id' => null,
        ]);

        // Incidencia 4: Cliente Barcelona (Software)
        DB::table('incidencias')->insert([
            'cliente_id' => 2, // Cliente Barcelona
            'tecnico_id' => 3, // Técnico Barcelona
            'categoria_id' => 1, // Software
            'subcategoria_id' => 4, // Imatge de projector defectuosa
            'estado_id' => 1, // Sin asignar
            'prioridad_id' => 3, // Media
            'sede_id' => 1, // Barcelona
            'descripcion' => 'El proyector de la sala de conferencias está mostrando una imagen defectuosa.',
            'resolucion' => null,
            'imagen' => null,
            'fecha_creacion' => Carbon::now(),
            'fecha_resolucion' => null,
            'chat_id' => null,
        ]);

        // Incidencia 5: Cliente Berlín (Software)
        DB::table('incidencias')->insert([
            'cliente_id' => 4, // Cliente Berlín
            'tecnico_id' => 5, // Técnico Berlín
            'categoria_id' => 1, // Software
            'subcategoria_id' => 2, // Accés remot
            'estado_id' => 4, // Resuelta
            'prioridad_id' => 2, // Alta
            'sede_id' => 2, // Berlín
            'descripcion' => 'Tengo problemas para acceder a la red remotamente desde mi casa.',
            'resolucion' => 'Se realizó un ajuste en la configuración del acceso remoto y ahora funciona correctamente.',
            'imagen' => null,
            'fecha_creacion' => Carbon::now()->subDays(2), // Creada hace 2 días
            'fecha_resolucion' => Carbon::now()->subDays(1), // Resuelta hace 1 día
            'chat_id' => null,
        ]);
    }
}
