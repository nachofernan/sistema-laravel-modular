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
            $table->string('descripcion')->nullable();
            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension');
            $table->string('mimeType');
            $table->string('version')->nullable();
            $table->integer('orden')->default(1000);
            $table->boolean('visible')->default(1);

            $table->string('sede_id')->nullable();
            $table->string('user_id');

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
