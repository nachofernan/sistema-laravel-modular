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
        Schema::create('concursos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->unsignedBigInteger('numero')->nullable();
            $table->text('descripcion');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_cierre');
            $table->string('numero_legajo');
            $table->string('legajo');
            $table->unsignedBigInteger('estado_id')->default(1);
            $table->unsignedBigInteger('subrubro_id')->nullable();
            
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
            
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concursos');
    }
};
