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
        Schema::create('managed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_uuid'); // UUID del job de Laravel
            $table->string('job_class')->default('App\\Jobs\\Emails\\EnviarCorreoAutomatizado');
            $table->string('entity_type'); // 'concurso', 'proveedor', 'ticket', 'general'
            $table->unsignedBigInteger('entity_id')->nullable(); // NULL para jobs generales
            $table->string('job_type'); // 'recordatorio_48hs', 'cierre', 'notificacion', etc.
            $table->json('tags');
            $table->timestamp('scheduled_for');
            $table->string('status')->default('pending'); // pending, executed, cancelled, failed
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['entity_type', 'entity_id']);
            $table->index(['status']);
            $table->index(['job_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managed_jobs');
    }
};
