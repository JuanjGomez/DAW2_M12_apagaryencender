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
        $this->call(RolesTableSeeder::class);
        $this->call(SedesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriasTableSeeder::class);
        $this->call(SubcategoriasTableSeeder::class);
        $this->call(EstadosTableSeeder::class);
        $this->call(PrioridadesTableSeeder::class);
        $this->call(IncidenciasTableSeeder::class);

    }
}
