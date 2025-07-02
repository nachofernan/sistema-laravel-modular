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
        Schema::create('documento_tipos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->text('descripcion')->nullable();
            // Si es relativo al concurso o a la oferta
            $table->boolean('de_concurso')->default(true);
            // Si el archivo debe estar encriptado por naturaleza
            $table->boolean('encriptado')->default(false);
            // Si el tipo de documento es relativo a los tipo de documentos de los proveedores
            $table->unsignedBigInteger('tipo_documento_proveedor_id')->nullable()->default(null);

            $table->timestamps();
        });

        Schema::table('documentos', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('documento_tipo_id')->nullable();
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_tipos');
    }
};
