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
        Schema::create('invitacions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('concurso_id')->constrained();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedInteger('intencion')->default(0); // 0 es pregunta, 1 es participa, 2 es no participa, 3 es que ofertó
            $table->date('fecha_envio')->nullable();
            
            $table->index('proveedor_id'); 

            $table->timestamps();
        });

        Schema::table('documentos', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('invitacion_id')->nullable();
            $table->foreign('invitacion_id')->references('id')->on('invitacions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitacions');
    }
};
