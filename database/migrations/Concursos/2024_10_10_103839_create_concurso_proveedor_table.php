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
        Schema::create('concurso_proveedor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concurso_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->timestamps();
    
            // Claves foráneas
            $table->foreign('concurso_id')->references('id')->on('concursos')->onDelete('cascade');
            // Aquí, como la base de datos de proveedores está en otra conexión, no puedes usar foreign key fácilmente
            $table->index('proveedor_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concurso_proveedor');
    }
};
