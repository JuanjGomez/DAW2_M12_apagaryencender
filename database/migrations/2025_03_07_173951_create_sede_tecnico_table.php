<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sede_tecnico', function (Blueprint $table) {
            // Eliminamos la columna 'id', ya que no la necesitamos
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Establecemos la clave primaria compuesta por 'sede_id' y 'tecnico_id'
            $table->primary(['sede_id', 'tecnico_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sede_tecnico');
    }
};
