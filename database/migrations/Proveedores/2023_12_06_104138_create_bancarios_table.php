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
        Schema::create('bancarios', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('cbu')->nullable();
            $table->string('alias')->nullable();
            $table->string('tipocuenta')->nullable();
            $table->string('numerocuenta')->nullable();
            $table->string('banco')->nullable();
            $table->string('sucursal')->nullable();

            //Link a Proveedores
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('user_id_created')->nullable();
            $table->unsignedBigInteger('user_id_updated')->nullable();
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancarios');
    }
};
