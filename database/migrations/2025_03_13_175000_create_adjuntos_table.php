<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ruta');
            $table->string('tipo_mime');
            $table->integer('tamano')->comment('Tamaño en bytes');
            $table->morphs('adjuntable'); // Para relación polimórfica (puede estar asociado a incidencia o mensaje)
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('adjuntos');
    }
}; 