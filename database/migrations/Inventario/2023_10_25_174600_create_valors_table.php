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
        Schema::create('valors', function (Blueprint $table) {
            $table->id();

            $table->string('valor');

            $table->unsignedBigInteger('caracteristica_id');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('cascade');

            $table->unsignedBigInteger('elemento_id');
            $table->foreign('elemento_id')->references('id')->on('elementos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valors');
    }
};
