<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nombre' => 'administrador'],
            ['nombre' => 'cliente'],
            ['nombre' => 'gestor'],
            ['nombre' => 'tecnico'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
