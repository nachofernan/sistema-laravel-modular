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
        Schema::create('historials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concurso_id')->constrained()->onDelete('cascade');  // Relaciona con concursos
            $table->foreignId('estado_id')->constrained()->onDelete('cascade');  // Relaciona con estados
            $table->unsignedBigInteger('user_id')->nullable();  // Relaciona con usuarios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial');
    }
};
