<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar tabla capacitacions
        Schema::table('capacitacions', function (Blueprint $table) {
            // Renombrar la columna fecha a fecha_inicio
            $table->renameColumn('fecha', 'fecha_inicio');
            
            // Agregar nueva columna fecha_final después de fecha_inicio
            $table->date('fecha_final')->nullable()->after('fecha_inicio');
        });

        // Modificar tabla invitacions
        Schema::table('invitacions', function (Blueprint $table) {
            // Agregar columna tipo para indicar si es presencial o virtual
            $table->enum('tipo', ['presencial', 'virtual'])->default('presencial')->after('capacitacion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en tabla invitacions
        Schema::table('invitacions', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });

        // Revertir cambios en tabla capacitacions
        Schema::table('capacitacions', function (Blueprint $table) {
            $table->dropColumn('fecha_final');
            $table->renameColumn('fecha_inicio', 'fecha');
        });
    }
};
