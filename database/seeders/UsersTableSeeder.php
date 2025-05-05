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
            'email' => 'cliente.barcelona@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Cliente
            'sede_id' => 1,  // Barcelona
            'estado' => 'activo',
        ]);

        // Técnico Barcelona
        User::create([
            'name' => 'Tecnico Barcelona',
            'email' => 'tecnico.barcelona@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 2,  // Técnico
            'sede_id' => 1,  // Barcelona
            'jefe_id' => 1,  // Jefe de Barcelona (Administrador)
            'estado' => 'activo',
        ]);
           // Gestor Barcelona
           User::create([
            'name' => 'Gestor Barcelona',
            'email' => 'gestor.barcelona@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 3,  // Gestor equipo
            'sede_id' => 1,  // Berlín
            'estado' => 'activo',
        ]);
         // Cliente Berlin
         User::create([
            'name' => 'Cliente Berlin',
            'email' => 'cliente.berlin@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Cliente
            'sede_id' => 2,  // Barcelona
            'estado' => 'activo',
        ]);

        // Gestor Berlín
        User::create([
            'name' => 'Gestor Berlín',
            'email' => 'gestor.berlin@empresa.com',
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
            'role_id' => 2,  // Técnico
            'sede_id' => 2,  // Berlín
            'jefe_id' => 4,  // Gestor de Berlín
            'estado' => 'activo',
        ]);
         // Cliente Montreal
         User::create([
            'name' => 'Cliente Montreal',
            'email' => 'cliente.montreal@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 4,  // Cliente
            'sede_id' => 3,  // Barcelona
            'estado' => 'activo',
        ]);
        
        // Gestor Montreal
        User::create([
            'name' => 'Gestor MOntreal',
            'email' => 'gestor.montreal@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 3,  // Gestor equipo
            'sede_id' => 3,  // Berlín
            'estado' => 'activo',
        ]);

        // Técnico Montreal
        User::create([
            'name' => 'Tecnico Montreal',
            'email' => 'tecnico.montreal@empresa.com',
            'password' => Hash::make('qweQWE123'),
            'role_id' => 2,  // Técnico
            'sede_id' => 3,  // Montreal
            'estado' => 'activo',
        ]);
    }
}