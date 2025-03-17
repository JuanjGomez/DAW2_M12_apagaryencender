<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SedesTableSeeder extends Seeder
{
    public function run()
    {
        // Crear sedes
        DB::table('sedes')->insert([
            [
                'nombre' => 'Barcelona',
                'direccion' => 'Carrer de la Pau, 23',
                'ciudad' => 'Barcelona',
                'pais' => 'España'
            ],
            [
                'nombre' => 'Berlín',
                'direccion' => 'Kaiserstraße 12',
                'ciudad' => 'Berlín',
                'pais' => 'Alemania'
            ],
            [
                'nombre' => 'Montreal',
                'direccion' => 'Rue Saint-Denis, 45',
                'ciudad' => 'Montreal',
                'pais' => 'Canadá'
            ],
        ]);
    }
}