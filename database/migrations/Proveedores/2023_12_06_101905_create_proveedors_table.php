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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('cuit');
            $table->string('razonsocial');
            $table->string('correo');
            $table->string('fantasia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('webpage')->nullable();
            $table->string('horario')->nullable();

            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->unsignedBigInteger('user_id_updated')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
