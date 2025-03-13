<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;
use App\Models\Categoria;

class SubcategoriasTableSeeder extends Seeder
{
    public function run()
    {
        $softwareId = Categoria::where('nombre', 'Software')->first()->id;
        $hardwareId = Categoria::where('nombre', 'Hardware')->first()->id;

        $subcategorias = [
            // Software
            [
                'nombre' => 'Aplicació gestió administrativa',
                'categoria_id' => $softwareId
            ],
            [
                'nombre' => 'Accés remot',
                'categoria_id' => $softwareId
            ],
            [
                'nombre' => 'Aplicació de videoconferència',
                'categoria_id' => $softwareId
            ],
            // Hardware
            [
                'nombre' => 'Problema amb el teclat',
                'categoria_id' => $hardwareId
            ],
            [
                'nombre' => 'El ratolí no funciona',
                'categoria_id' => $hardwareId
            ],
            [
                'nombre' => 'Monitor no s\'encén',
                'categoria_id' => $hardwareId
            ],
            [
                'nombre' => 'Imatge de projector defectuosa',
                'categoria_id' => $hardwareId
            ],
        ];

        foreach ($subcategorias as $subcategoria) {
            Subcategoria::create($subcategoria);
        }
    }
}
