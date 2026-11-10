<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla de historial de cambios de spatie/laravel-activitylog (v4).
 *
 * Une en una sola migración los tres stubs del paquete (create + event + batch_uuid).
 * Vive en la base general `plataforma` (conexión por defecto `mysql`), junto con la otra
 * infraestructura transversal polimórfica (media, email_logs): el sujeto puede ser un
 * modelo de cualquier módulo.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->string('event')->nullable();
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
