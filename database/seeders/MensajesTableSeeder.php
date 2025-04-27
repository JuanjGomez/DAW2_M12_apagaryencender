<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mensaje;
use App\Models\Chat;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class MensajesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mensajes predefinidos para clientes
        $mensajesClientes = [
            'Hola, tengo un problema con mi ordenador.',
            'Buenos días, mi impresora no funciona correctamente.',
            'No puedo acceder al sistema de gestión documental.',
            'La red WiFi está muy lenta, ¿podrían revisarla?',
            'Mi ordenador se reinicia constantemente, necesito ayuda urgente.',
            'No puedo abrir documentos PDF, me da un error.',
            '¿Cuándo podría venir un técnico para revisar mi equipo?',
            'El monitor muestra líneas extrañas, creo que está fallando.',
            'Se ha bloqueado mi cuenta de correo, necesito restablecerla.',
            'Necesito instalar un nuevo software, ¿me pueden ayudar?'
        ];

        // Mensajes predefinidos para técnicos
        $mensajesTecnicos = [
            'Buenos días, estamos revisando su incidencia.',
            'He verificado su problema y parece ser un problema de configuración.',
            '¿Ha intentado reiniciar el equipo?',
            'Necesitaré acceso remoto a su ordenador para diagnosticar mejor el problema.',
            'Hemos detectado que falta un controlador actualizado.',
            'El problema parece estar resuelto ahora. ¿Puede confirmarlo?',
            'Programaré una visita a su sede para mañana a las 10:00.',
            'Le envío un enlace con instrucciones para resolver este problema común.',
            'Me gustaría que me proporcionara más detalles sobre cuándo ocurre el error.',
            'Este problema necesitará una intervención física, pasaré por su oficina esta tarde.'
        ];

        // Obtener chats existentes
        $chats = Chat::all();
        $clientes = User::whereHas('role', function($query) {
            $query->where('nombre', 'cliente');
        })->get();
        $tecnicos = User::whereHas('role', function($query) {
            $query->where('nombre', 'tecnico');
        })->get();

        foreach ($chats as $chat) {
            // Determinar cuántos mensajes tendrá este chat (entre 3 y 10)
            $numMensajes = rand(3, 10);
            
            // Obtenemos un cliente y un técnico aleatorios para este chat
            $cliente = $clientes->random();
            $tecnico = $tecnicos->random();
            
            for ($i = 0; $i < $numMensajes; $i++) {
                // Alternamos mensajes entre cliente y técnico
                $usuario = ($i % 2 == 0) ? $cliente : $tecnico;
                $mensaje = ($i % 2 == 0) ? $mensajesClientes[array_rand($mensajesClientes)] : $mensajesTecnicos[array_rand($mensajesTecnicos)];
                
                // Calculamos la fecha de envío (se hace cada vez más reciente)
                $fechaEnvio = now()->subDays(5)->addHours($i * 2);
                
                Mensaje::create([
                    'chat_id' => $chat->id,
                    'usuario_id' => $usuario->id,
                    'mensaje' => $mensaje,
                    'enviado_en' => $fechaEnvio,
                    'created_at' => $fechaEnvio,
                    'updated_at' => $fechaEnvio,
                ]);
            }
        }
    }
} 