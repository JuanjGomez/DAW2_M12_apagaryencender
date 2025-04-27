<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;
use App\Models\Incidencia;
use Illuminate\Support\Facades\DB;

class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero, creamos 10 chats base (sin asociar a incidencias todavía)
        for ($i = 1; $i <= 10; $i++) {
            $chat = Chat::create([
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ]);
        }
        
        // Ahora, asociamos chats a algunas incidencias existentes
        // Obtenemos todas las incidencias que no tienen chats
        $incidenciasSinChat = Incidencia::whereNull('chat_id')->take(5)->get();
        
        foreach ($incidenciasSinChat as $incidencia) {
            // Creamos un nuevo chat para esta incidencia
            $chat = Chat::create([
                'created_at' => $incidencia->fecha_creacion,
                'updated_at' => now(),
            ]);
            
            // Actualizamos la incidencia para asociarla con este chat
            $incidencia->chat_id = $chat->id;
            $incidencia->save();
        }
    }
} 