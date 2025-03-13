<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prioridad;

class PrioridadesTableSeeder extends Seeder
{
    public function run()
    {
        $prioridades = [
            ['nombre' => 'Alta'],
            ['nombre' => 'Mitjana'],
            ['nombre' => 'Baixa'],
        ];

        foreach ($prioridades as $prioridad) {
            Prioridad::create($prioridad);
        }
    }
}
