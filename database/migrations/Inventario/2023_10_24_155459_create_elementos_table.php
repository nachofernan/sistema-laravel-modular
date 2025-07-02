<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('elementos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo');
            $table->text('notas')->nullable();

            $table->string('sede_id')->nullable();
            $table->string('area_id')->nullable();
            $table->string('user_id')->nullable();
            
            $table->string('proveedor')->nullable();
            $table->string('soporte')->nullable();
            $table->date('vencimiento_garantia')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementos');
    }
};
