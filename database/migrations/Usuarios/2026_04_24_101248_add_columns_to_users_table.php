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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('puesto')->nullable();
            $table->date('cumpleanos')->nullable();
            $table->date('ingreso')->nullable();
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('nombre');
            $table->dropColumn('apellido');
            $table->dropColumn('puesto');
            $table->dropColumn('cumpleanos');
            $table->dropColumn('ingreso');
            $table->dropColumn('activo');
        });
    }
};
