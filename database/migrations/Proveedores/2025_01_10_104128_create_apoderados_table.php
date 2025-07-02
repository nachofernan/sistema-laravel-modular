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
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable();
            $table->enum('tipo', ['representante','apoderado']);
            $table->boolean('activo')->default(true);
            
            //Link a Proveedores
            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');
            
            $table->timestamps();
        });

        /* Schema::create('documento_apoderado', function (Blueprint $table) {
            $table->id();

            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension')->nullable();
            $table->string('mimeType')->nullable();

            $table->date('vencimiento')->nullable();

            $table->unsignedBigInteger('user_id_created');

            //Link a Usuario Validador
            $table->unsignedBigInteger('apoderado_id');
            $table->foreign('apoderado_id')->references('id')->on('apoderados')->onDelete('cascade');

            $table->timestamps();
        }); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /* Schema::dropIfExists('documento_apoderado'); */
        Schema::dropIfExists('apoderados');
    }
};
