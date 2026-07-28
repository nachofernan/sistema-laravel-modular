<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Historial de archivos de un documento. Hasta ahora reemplazar el archivo borraba
 * el anterior del disco sin vuelta atrás.
 *
 * `version` en `documentos` deja de ser un string libre y pasa a ser el número de
 * la versión vigente. No se sincroniza con el `_v3` que trae el código de Control
 * de Gestión: ese vive dentro de `codigo` y lo numera esa área con su criterio.
 * Todos los documentos existentes arrancan en 1, que es desde donde el sistema
 * empieza a guardar historial.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_versiones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('documento_id')->constrained('documentos')->cascadeOnDelete();
            $table->unsignedInteger('version');
            // ID del media en la colección `historial`. Sin FK: la tabla `media` vive
            // en otra base y el aislamiento entre bases no admite FK cruzadas.
            $table->unsignedBigInteger('media_id')->nullable();
            $table->string('archivo');
            $table->text('notas')->nullable();
            $table->unsignedBigInteger('subido_por')->nullable();

            $table->timestamps();

            $table->unique(['documento_id', 'version']);
        });

        // El UPDATE va antes del change(): la columna venía nullable y con 75 de 76
        // filas en NULL, que no entran en una columna NOT NULL.
        Schema::getConnection()->statement("UPDATE documentos SET version = '1'");

        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_versiones');

        Schema::table('documentos', function (Blueprint $table) {
            $table->string('version')->nullable()->change();
        });
    }
};
