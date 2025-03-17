<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedesTableSeeder extends Seeder
{
    public function run()
    {
        $sedes = [
            [
                'nombre' => 'Barcelona',
                'direccion' => 'Carrer de Balmes, 123',
                'ciudad' => 'Barcelona',
                'pais' => 'España'
            ],
            [
                'nombre' => 'Berlín',
                'direccion' => 'Friedrichstraße 123',
                'ciudad' => 'Berlin',
                'pais' => 'Alemania'
            ],
            [
                'nombre' => 'Montreal',
                'direccion' => '1234 Rue Sainte-Catherine O',
                'ciudad' => 'Montreal',
                'pais' => 'Canadá'
            ],
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
