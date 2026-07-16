<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Estructura de organigrama: dota a las áreas de semántica (tipo, responsable)
 * y a los usuarios de un cargo tipificado.
 *
 * Se corre sobre la conexión `usuarios` (php artisan module:migrate usuarios,
 * que pasa --database=usuarios --path=database/migrations/Usuarios), por eso
 * usa Schema:: pelado como el resto de las migraciones de esta carpeta.
 *
 * Nota: se elimina `users.puesto` (texto libre, sin datos en ninguna fila y
 * además nunca fillable, por lo que el input del form jamás persistía). Su rol
 * lo reemplaza `cargo_id` (catálogo). No hay datos que migrar.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Catálogos primero: las FK de areas/users los referencian.
        Schema::create('tipos_area', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_area_id')->nullable()->after('nombre');
            $table->foreign('tipo_area_id')->references('id')->on('tipos_area')->onDelete('set null');

            // Quién encabeza el nodo. areas <-> users se referencian mutuamente,
            // pero ambas tablas ya existen y el FK es nullable: sin problema de orden.
            $table->unsignedBigInteger('responsable_id')->nullable()->after('area_id');
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedInteger('orden')->default(0)->after('responsable_id');
            $table->boolean('activa')->default(true)->after('orden');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('puesto');

            $table->unsignedBigInteger('cargo_id')->nullable()->after('sede_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropColumn('cargo_id');

            $table->string('puesto')->nullable();
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->dropForeign(['tipo_area_id']);
            $table->dropForeign(['responsable_id']);
            $table->dropColumn(['tipo_area_id', 'responsable_id', 'orden', 'activa']);
        });

        Schema::dropIfExists('cargos');
        Schema::dropIfExists('tipos_area');
    }
};
