<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioridadesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('prioridades')->insert([
            ['nombre' => 'Urgente'],
            ['nombre' => 'Alta'],
            ['nombre' => 'Media'],
            ['nombre' => 'Baja'],
        ]);
    }
}
