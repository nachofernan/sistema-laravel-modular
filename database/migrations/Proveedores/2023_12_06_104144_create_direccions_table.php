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
        Schema::create('direccions', function (Blueprint $table) {
            $table->id();

            $table->string('tipo');
            $table->string('calle');
            $table->string('altura');
            $table->string('piso')->nullable();
            $table->string('departamento')->nullable();
            $table->string('ciudad');
            $table->string('codigopostal');
            $table->string('provincia');
            $table->string('pais');

            //Link a Proveedores
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->unsignedBigInteger('user_id_updated')->nullable();
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccions');
    }
};
