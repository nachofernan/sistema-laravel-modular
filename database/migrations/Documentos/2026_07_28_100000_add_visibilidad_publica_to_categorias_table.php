<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La visibilidad pública de una categoría pasa a ser un atributo (`publica`) en vez
 * de derivarse de que la categoría exista: hasta ahora el menú público listaba toda
 * categoría raíz con una query suelta dentro del Blade, sin forma de tener una
 * categoría interna.
 *
 * De paso `categoria_padre_id` deja de ser un string sin FK: si las reglas de
 * visibilidad se apoyan en la jerarquía, la jerarquía tiene que ser confiable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_padre_id')->nullable()->change();
            $table->boolean('publica')->default(false)->after('categoria_padre_id');
            $table->unsignedInteger('orden')->default(0)->after('publica');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->foreign('categoria_padre_id')->references('id')->on('categorias')->cascadeOnDelete();
        });

        // Hoy todas las categorías se muestran sin login: se preserva ese estado y el
        // orden actual, que de hecho es el orden de creación.
        Schema::getConnection()->statement('UPDATE categorias SET publica = 1, orden = id');
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['categoria_padre_id']);
            $table->dropColumn(['publica', 'orden']);
            $table->string('categoria_padre_id')->nullable()->change();
        });
    }
};
