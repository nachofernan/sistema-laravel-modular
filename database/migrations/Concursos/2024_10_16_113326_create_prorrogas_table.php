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
        Schema::create('prorrogas', function (Blueprint $table) {
            $table->id();
            $table->datetime('fecha_anterior');
            $table->datetime('fecha_actual');
            $table->unsignedBigInteger('concurso_id');
            $table->foreign('concurso_id')->references('id')->on('concursos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prorrogas');
    }
};
