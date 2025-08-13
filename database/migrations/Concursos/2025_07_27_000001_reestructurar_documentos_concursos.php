<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar columna encriptado de documento_tipos
        Schema::table('documento_tipos', function (Blueprint $table) {
            $table->dropColumn('encriptado');
        });

        // Crear tabla concurso_documentos para documentos adjuntos del concurso
        Schema::create('concurso_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension')->nullable();
            $table->string('mimeType')->nullable();
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->unsignedBigInteger('concurso_id');
            $table->unsignedBigInteger('documento_tipo_id')->nullable();
            $table->timestamps();
            
            $table->foreign('concurso_id')->references('id')->on('concursos')->onDelete('cascade');
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('set null');
        });

        // Crear tabla oferta_documentos para documentos de la oferta del proveedor
        Schema::create('oferta_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invitacion_id');
            $table->unsignedBigInteger('documento_tipo_id')->nullable();
            $table->unsignedBigInteger('documento_proveedor_id')->nullable();
            $table->string('archivo')->nullable();
            $table->string('mimeType')->nullable();
            $table->string('extension')->nullable();
            $table->string('file_storage')->nullable();
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->boolean('encriptado')->default(false);
            $table->text('comentarios')->nullable();
            $table->timestamps();
            
            $table->foreign('invitacion_id')->references('id')->on('invitacions')->onDelete('cascade');
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('set null');
            
            // Índices para mejorar performance
            $table->index(['invitacion_id', 'documento_tipo_id']);
            $table->index(['documento_proveedor_id']);
        });

        // Migrar datos existentes
        if (Schema::hasTable('documentos')) {
            $this->migrarDatosExistentes();
        }

        // Eliminar tabla documentos antigua
        Schema::dropIfExists('documentos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear tabla documentos antigua
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension')->nullable();
            $table->string('mimeType')->nullable();
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->boolean('encriptado')->default(false);
            $table->unsignedBigInteger('concurso_id')->nullable();
            $table->unsignedBigInteger('invitacion_id')->nullable();
            $table->unsignedBigInteger('documento_tipo_id')->nullable();
            $table->timestamps();
            
            $table->foreign('concurso_id')->references('id')->on('concursos')->onDelete('cascade');
            $table->foreign('invitacion_id')->references('id')->on('invitacions')->onDelete('cascade');
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('set null');
        });

        // Migrar datos de vuelta
        $this->migrarDatosDeVuelta();

        // Eliminar nuevas tablas
        Schema::dropIfExists('oferta_documentos');
        Schema::dropIfExists('concurso_documentos');

        // Restaurar columna encriptado en documento_tipos
        Schema::table('documento_tipos', function (Blueprint $table) {
            $table->boolean('encriptado')->default(false);
        });
    }

    /**
     * Migrar datos existentes a la nueva estructura
     */
    private function migrarDatosExistentes()
    {
        // Obtener todos los documentos existentes
        $documentos = DB::table('documentos')->get();

        foreach ($documentos as $documento) {
            if ($documento->concurso_id && !$documento->invitacion_id) {
                // Es un documento del concurso
                DB::table('concurso_documentos')->insert([
                    'id' => $documento->id,
                    'archivo' => $documento->archivo,
                    'file_storage' => $documento->file_storage,
                    'extension' => $documento->extension,
                    'mimeType' => $documento->mimeType,
                    'user_id_created' => $documento->user_id_created,
                    'concurso_id' => $documento->concurso_id,
                    'documento_tipo_id' => $documento->documento_tipo_id,
                    'created_at' => $documento->created_at,
                    'updated_at' => $documento->updated_at,
                ]);
            } else if ($documento->invitacion_id) {
                // Es un documento de oferta
                DB::table('oferta_documentos')->insert([
                    'id' => $documento->id,
                    'invitacion_id' => $documento->invitacion_id,
                    'documento_tipo_id' => $documento->documento_tipo_id,
                    'documento_proveedor_id' => null, // Se puede vincular después si es necesario
                    'archivo' => $documento->archivo,
                    'file_storage' => $documento->file_storage,
                    'extension' => $documento->extension,
                    'mimeType' => $documento->mimeType,
                    'user_id_created' => $documento->user_id_created,
                    'encriptado' => $documento->encriptado ?? false, // Mover el campo encriptado aquí
                    'comentarios' => null,
                    'created_at' => $documento->created_at,
                    'updated_at' => $documento->updated_at,
                ]);
            }
        }
    }

    /**
     * Migrar datos de vuelta a la estructura antigua
     */
    private function migrarDatosDeVuelta()
    {
        // Migrar concurso_documentos de vuelta a documentos
        $concursoDocumentos = DB::table('concurso_documentos')->get();
        foreach ($concursoDocumentos as $doc) {
            DB::table('documentos')->insert([
                'id' => $doc->id,
                'archivo' => $doc->archivo,
                'file_storage' => $doc->file_storage,
                'extension' => $doc->extension,
                'mimeType' => $doc->mimeType,
                'user_id_created' => $doc->user_id_created,
                'encriptado' => false, // concurso_documentos no tiene encriptado
                'concurso_id' => $doc->concurso_id,
                'invitacion_id' => null,
                'documento_tipo_id' => $doc->documento_tipo_id,
                'created_at' => $doc->created_at,
                'updated_at' => $doc->updated_at,
            ]);
        }

        // Migrar oferta_documentos de vuelta a documentos
        $ofertaDocumentos = DB::table('oferta_documentos')->get();
        foreach ($ofertaDocumentos as $doc) {
            DB::table('documentos')->insert([
                'id' => $doc->id,
                'archivo' => $doc->archivo,
                'file_storage' => $doc->file_storage,
                'extension' => $doc->extension,
                'mimeType' => $doc->mimeType,
                'user_id_created' => $doc->user_id_created,
                'encriptado' => $doc->encriptado, // Mover el campo encriptado de vuelta
                'concurso_id' => null,
                'invitacion_id' => $doc->invitacion_id,
                'documento_tipo_id' => $doc->documento_tipo_id,
                'created_at' => $doc->created_at,
                'updated_at' => $doc->updated_at,
            ]);
        }
    }
}; 