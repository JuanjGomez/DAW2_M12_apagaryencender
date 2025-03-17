<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados')->insert([
            ['nombre' => 'Sin asignar'],
            ['nombre' => 'Asignada'],
            ['nombre' => 'En trabajo'],
            ['nombre' => 'Resuelta'],
            ['nombre' => 'Cerrada'],
            ['nombre' => 'Devuelta'],
        ]);
    }
}
