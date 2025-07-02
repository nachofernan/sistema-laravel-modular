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
        Schema::create('ips', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('ip');
            $table->string('bloque_a');
            $table->string('bloque_b');
            $table->string('bloque_c');
            $table->string('bloque_d');
            $table->string('mac')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ips');
    }
};
