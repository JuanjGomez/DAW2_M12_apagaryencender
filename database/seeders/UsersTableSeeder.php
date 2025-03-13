<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Sede;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('nombre', 'administrador')->first();
        $clienteRole = Role::where('nombre', 'cliente')->first();
        $gestorRole = Role::where('nombre', 'gestor')->first();
        $tecnicoRole = Role::where('nombre', 'tecnico')->first();

        $sedes = Sede::all();

        // Crear un administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'sede_id' => $sedes->first()->id
        ]);

        // Crear gestores para cada sede
        foreach ($sedes as $sede) {
            User::create([
                'name' => 'Gestor ' . $sede->nombre,
                'email' => 'gestor.' . strtolower($sede->nombre) . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $gestorRole->id,
                'sede_id' => $sede->id
            ]);

            // Crear un técnico por sede
            User::create([
                'name' => 'Técnico ' . $sede->nombre,
                'email' => 'tecnico.' . strtolower($sede->nombre) . '@example.com',
                'password' => Hash::make('12345678'),  // Contraseña más simple para pruebas
                'role_id' => $tecnicoRole->id,
                'sede_id' => $sede->id
            ]);

            // Crear 3 clientes por sede
            for ($i = 1; $i <= 3; $i++) {
                User::create([
                    'name' => 'Cliente ' . $i . ' ' . $sede->nombre,
                    'email' => 'cliente' . $i . '.' . strtolower($sede->nombre) . '@example.com',
                    'password' => Hash::make('password'),
                    'role_id' => $clienteRole->id,
                    'sede_id' => $sede->id
                ]);
            }
        }
    }
}
