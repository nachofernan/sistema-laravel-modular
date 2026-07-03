<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Documenta en migraciones una columna que ya existe en la base de producción
 * (categoria_id, FK a categorias, onDelete cascade) pero nunca quedó versionada.
 * Sin esta migración, un entorno nuevo corriendo `migrate` desde cero no crea
 * la columna y el módulo Documentos rompe al crear cualquier documento.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
};
