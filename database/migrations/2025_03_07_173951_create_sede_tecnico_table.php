<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sede_tecnico', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['sede_id', 'tecnico_id']); // Evita duplicados sin ser clave primaria compuesta
        });
    }

    public function down(): void {
        Schema::dropIfExists('sede_tecnico');
    }
};
