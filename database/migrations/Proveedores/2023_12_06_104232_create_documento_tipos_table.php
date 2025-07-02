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
        Schema::create('documento_tipos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->boolean('vencimiento');

            //Link a Usuario Validador
            $table->unsignedBigInteger('user_id_validador')->nullable();

            $table->timestamps();
        });

        Schema::table('documentos', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('documento_tipo_id');
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_tipos');
    }
};
