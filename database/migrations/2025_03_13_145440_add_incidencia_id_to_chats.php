<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('incidencia_id')->nullable()->constrained('incidencias')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['incidencia_id']);
            $table->dropColumn('incidencia_id');
        });
    }
};
