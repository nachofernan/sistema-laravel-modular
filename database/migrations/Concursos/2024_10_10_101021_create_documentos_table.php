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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension')->nullable();
            $table->string('mimeType')->nullable();
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->boolean('encriptado')->default(false);

            $table->unsignedBigInteger('concurso_id')->nullable();
            $table->foreign('concurso_id')->references('id')->on('concursos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
