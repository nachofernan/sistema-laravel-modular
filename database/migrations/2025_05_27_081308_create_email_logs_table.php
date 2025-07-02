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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('destinatario');
            $table->string('tipo')->default('general');
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['exitoso', 'fallido', 'pendiente'])->default('pendiente');
            $table->text('error')->nullable();
            $table->timestamps();
            
            // Índices para mejorar performance
            $table->index(['estado', 'created_at']);
            $table->index(['tipo', 'created_at']);
            $table->index('destinatario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
