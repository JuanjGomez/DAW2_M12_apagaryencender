<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Añadir la columna 'gestor_id' y la clave foránea a la tabla 'sedes'
        Schema::table('sedes', function (Blueprint $table) {
            $table->foreignId('gestor_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Eliminar la clave foránea y la columna 'gestor_id' si es necesario revertir la migración
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropForeign(['gestor_id']);
            $table->dropColumn('gestor_id');
        });
    }
};
