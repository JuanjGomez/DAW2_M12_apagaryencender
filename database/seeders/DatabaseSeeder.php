<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
=======
        $this->call([
            RolesTableSeeder::class,
            SedesTableSeeder::class,
            CategoriasTableSeeder::class,
            SubcategoriasTableSeeder::class,
            EstadosTableSeeder::class,
            PrioridadesTableSeeder::class,
            UsersTableSeeder::class,
            IncidenciasTableSeeder::class,
>>>>>>> 86cc8e26541607363638230a2487de04c9cd2ce8
        ]);
    }
}
