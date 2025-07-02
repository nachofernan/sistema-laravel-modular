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

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('prefijo');

            $table->timestamps();
        });
        
        Schema::table('elementos', function (Blueprint $table) {

            $table->unsignedBigInteger('categoria_id')->after('vencimiento_garantia');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
