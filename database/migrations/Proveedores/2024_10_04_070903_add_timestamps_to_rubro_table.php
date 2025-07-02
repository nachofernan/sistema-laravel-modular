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
        Schema::table('rubros', function (Blueprint $table) {
            //
            $table->timestamps(); // Agrega las columnas created_at y updated_at
        });
        Schema::table('subrubros', function (Blueprint $table) {
            //
            $table->timestamps(); // Agrega las columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rubros', function (Blueprint $table) {
            //
            $table->dropTimestamps(); // Elimina las columnas created_at y updated_at
        });
        Schema::table('subrubros', function (Blueprint $table) {
            //
            $table->dropTimestamps(); // Elimina las columnas created_at y updated_at
        });
    }
};
