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
        Schema::create('copres', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('numero_ticket_factura');
            $table->string('cuit');
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->decimal('litros', 10, 2)->nullable();
            $table->decimal('precio_x_litro', 10, 2)->nullable();
            $table->decimal('importe_final', 12, 2);
            $table->integer('km_vehiculo')->nullable();
            $table->bigInteger('kz')->nullable();
            $table->date('salida')->nullable();
            $table->date('reentrada')->nullable();
            $table->bigInteger('user_id_creator')->nullable();
            $table->bigInteger('user_id_chofer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copres');
    }
};
