<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adjunto;
use App\Models\Incidencia;
use App\Models\Mensaje;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdjuntosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Arrays de tipos de archivos comunes
        $tiposArchivos = [
            'imagen' => [
                'extensions' => ['jpg', 'png', 'gif'],
                'mimes' => ['image/jpeg', 'image/png', 'image/gif'],
                'prefijos' => ['captura_', 'imagen_', 'foto_', 'screenshot_']
            ],
            'documento' => [
                'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
                'mimes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                'prefijos' => ['informe_', 'documento_', 'reporte_', 'guia_', 'manual_']
            ],
            'codigo' => [
                'extensions' => ['txt', 'log', 'xml', 'json'],
                'mimes' => ['text/plain', 'text/plain', 'application/xml', 'application/json'],
                'prefijos' => ['log_', 'error_', 'config_', 'data_']
            ]
        ];

        // Obtener todas las incidencias y mensajes
        $incidencias = Incidencia::all();
        $mensajes = Mensaje::all();
        $usuarios = User::all();

        // Crear adjuntos para incidencias (1-3 adjuntos por incidencia para algunas incidencias)
        foreach ($incidencias as $incidencia) {
            // Solo el 50% de las incidencias tendrá adjuntos
            if (rand(0, 1) == 0) {
                continue;
            }

            $numAdjuntos = rand(1, 3);
            for ($i = 0; $i < $numAdjuntos; $i++) {
                $this->crearAdjunto($incidencia, $tiposArchivos, $incidencia->cliente_id);
            }
        }

        // Crear adjuntos para mensajes (1 adjunto para algunos mensajes)
        foreach ($mensajes as $mensaje) {
            // Solo el 20% de los mensajes tendrá adjuntos
            if (rand(0, 4) > 0) {
                continue;
            }

            $this->crearAdjunto($mensaje, $tiposArchivos, $mensaje->usuario_id);
        }
    }

    /**
     * Crear un adjunto para un modelo (incidencia o mensaje)
     */
    private function crearAdjunto($modelo, $tiposArchivos, $usuarioId)
    {
        // Elegir aleatoriamente un tipo de archivo
        $tipoKey = array_rand($tiposArchivos);
        $tipo = $tiposArchivos[$tipoKey];
        
        // Elegir extensión y MIME aleatorios
        $extIndex = array_rand($tipo['extensions']);
        $extension = $tipo['extensions'][$extIndex];
        $mime = $tipo['mimes'][$extIndex];
        
        // Crear un nombre de archivo aleatorio
        $prefijoIndex = array_rand($tipo['prefijos']);
        $prefijo = $tipo['prefijos'][$prefijoIndex];
        $nombreArchivo = $prefijo . uniqid() . '.' . $extension;
        
        // Ruta donde se guardaría el archivo (no se crea realmente)
        $ruta = 'archivos/' . $tipoKey . '/' . $nombreArchivo;
        
        // Crear el registro de adjunto
        Adjunto::create([
            'nombre' => $nombreArchivo,
            'ruta' => $ruta,
            'tipo_mime' => $mime,
            'tamano' => rand(50000, 5000000), // Tamaño entre 50KB y 5MB
            'adjuntable_id' => $modelo->id,
            'adjuntable_type' => get_class($modelo),
            'usuario_id' => $usuarioId,
            'created_at' => $modelo->created_at ?? now(),
            'updated_at' => $modelo->updated_at ?? now(),
        ]);
    }
} 