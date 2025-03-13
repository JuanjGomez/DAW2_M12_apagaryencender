<?php

namespace Database\Seeders;

use App\Models\Subcategoria;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            SedesTableSeeder::class,
            CategoriasTableSeeder::class,
            SubcategoriasTableSeeder::class,
            EstadosTableSeeder::class,
            PrioridadesTableSeeder::class,
            UsersTableSeeder::class,
            IncidenciasTableSeeder::class,
        ]);
    }
}
