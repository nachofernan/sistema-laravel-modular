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

            $table->string('nombre');
            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension');
            $table->string('mimeType');

            $table->unsignedBigInteger('capacitacion_id');
            $table->foreign('capacitacion_id')->references('id')->on('capacitacions')->onDelete('cascade');

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
