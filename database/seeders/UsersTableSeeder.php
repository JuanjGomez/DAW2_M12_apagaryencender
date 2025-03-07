<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios con roles y sede
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('password'),
            'role_id' => 1,  // Administrador
            'sede_id' => 1,  // Barcelona
        ]);

        User::create([
            'name' => 'Cliente Barcelona',
            'email' => 'cliente@empresa.com',
            'password' => Hash::make('password'),
            'role_id' => 2,  // Cliente
            'sede_id' => 1,  // Barcelona
        ]);

        User::create([
            'name' => 'Tecnico Barcelona',
            'email' => 'tecnico@empresa.com',
            'password' => Hash::make('password'),
            'role_id' => 4,  // Tecnico
            'sede_id' => 1,  // Barcelona
        ]);

        User::create([
            'name' => 'Gestor Berlín',
            'email' => 'gestor@empresa.com',
            'password' => Hash::make('password'),
            'role_id' => 3,  // Gestor equipo
            'sede_id' => 2,  // Berlín
        ]);
    }
}
