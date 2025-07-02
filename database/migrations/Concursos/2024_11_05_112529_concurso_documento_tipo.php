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
        Schema::create('concurso_documento_tipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concurso_id')->constrained()->onDelete('cascade');  // Relaciona con concursos
            $table->foreignId('documento_tipo_id')->constrained()->onDelete('cascade');  // Relaciona con documento_tipos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concurso_documento_tipo');
    }
};
