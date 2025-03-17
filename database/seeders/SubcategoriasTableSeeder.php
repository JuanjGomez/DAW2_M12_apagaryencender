<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('subcategorias')->insert([
            // Software
            ['nombre' => 'Aplicación gestió administrativa', 'categoria_id' => 1],
            ['nombre' => 'Accés remot', 'categoria_id' => 1],
            ['nombre' => 'Aplicació de videoconferència', 'categoria_id' => 1],
            ['nombre' => 'Imatge de projector defectuosa', 'categoria_id' => 1],

            // Hardware
            ['nombre' => 'Problema amb el teclat', 'categoria_id' => 2],
            ['nombre' => 'El ratolí no funciona', 'categoria_id' => 2],
            ['nombre' => 'Monitor no sencén', 'categoria_id' => 2],
            ['nombre' => 'Imatge de projector defectuosa', 'categoria_id' => 2],
        ]);
    }
}