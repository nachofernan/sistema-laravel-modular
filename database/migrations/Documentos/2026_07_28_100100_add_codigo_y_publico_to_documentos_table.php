<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tres cosas:
 *
 * 1. `codigo` guarda la codificación de Control de Gestión (ej. `L-07.2-003_v3`), que
 *    hasta ahora viajaba sólo dentro del nombre del archivo. Es un string libre a
 *    propósito: el criterio de codificación es de esa área, no de la Plataforma.
 * 2. `publico` separa "descargable sin login" de `visible`, que hasta ahora hacía las
 *    dos cosas a la vez.
 * 3. Se van dos columnas muertas: `sede_id` (NULL en los 76 documentos, nunca se filtró
 *    por sede) y `file_storage` (guardaba el nombre del archivo al crear y el path
 *    absoluto del servidor al actualizar, truncado a 255 y repetido entre documentos
 *    distintos). La fuente real del archivo es MediaLibrary.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('codigo')->nullable()->after('nombre');
            $table->text('observaciones')->nullable()->after('descripcion');
            $table->boolean('publico')->default(false)->after('visible');
            $table->softDeletes();
        });

        // Los documentos visibles hoy ya se descargan sin login: marcarlos públicos
        // evita romper links que estén circulando. Los ocultos quedan fuera del portal.
        Schema::getConnection()->statement('UPDATE documentos SET publico = visible');

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn(['sede_id', 'file_storage']);
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn(['codigo', 'observaciones', 'publico']);
            $table->dropSoftDeletes();
            $table->string('sede_id')->nullable();
            $table->string('file_storage')->default('');
        });
    }
};
