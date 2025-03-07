<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['nombre' => 'administrador'],
            ['nombre' => 'gestor equipo'],
            ['nombre' => 'tecnico'],
            ['nombre' => 'cliente'],
        ]);
    }
}
