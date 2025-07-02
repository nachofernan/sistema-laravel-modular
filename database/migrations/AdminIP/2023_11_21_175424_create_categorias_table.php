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

            $table->timestamps();
        });

        Schema::table('ips', function($table) {

            $table->unsignedBigInteger('categoria_id')->nullable()->after('user_id');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');

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
