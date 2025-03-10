<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 1,  // Administrador
            'sede_id' => 1,  // Barcelona
            'estado' => 'activo',
        ]);

        // Cliente Barcelona
        User::create([
            'name' => 'Cliente Barcelona',
            'email' => 'cliente@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 2,  // Cliente
            'sede_id' => 1,  // Barcelona
            'estado' => 'activo',
        ]);

        // Técnico Barcelona
        User::create([
            'name' => 'Tecnico Barcelona',
            'email' => 'tecnico@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Técnico
            'sede_id' => 1,  // Barcelona
            'jefe_id' => 1,  // Jefe de Barcelona (Administrador)
            'estado' => 'activo',
        ]);

        // Gestor Berlín
        User::create([
            'name' => 'Gestor Berlín',
            'email' => 'gestor@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 3,  // Gestor equipo
            'sede_id' => 2,  // Berlín
            'estado' => 'activo',
        ]);

        // Técnico Berlín
        User::create([
            'name' => 'Tecnico Berlín',
            'email' => 'tecnico.berlin@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Técnico
            'sede_id' => 2,  // Berlín
            'jefe_id' => 4,  // Gestor de Berlín
            'estado' => 'activo',
        ]);

        // Técnico Montreal
        User::create([
            'name' => 'Tecnico Montreal',
            'email' => 'tecnico.montreal@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Técnico
            'sede_id' => 3,  // Montreal
            'estado' => 'activo',
        ]);
    }
}
