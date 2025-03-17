<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadosTableSeeder extends Seeder
{
    public function run()
    {
        $estados = [
            ['nombre' => 'Sense assignar'],
            ['nombre' => 'Assignada'],
            ['nombre' => 'En treball'],
            ['nombre' => 'Resolta'],
            ['nombre' => 'Tancada'],
        ];

        foreach ($estados as $estado) {
            Estado::create($estado);
        }
    }
}
