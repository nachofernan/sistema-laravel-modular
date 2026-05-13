<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar 'bloqueado' al enum de estado (era un bug silencioso)
        DB::statement("ALTER TABLE email_logs MODIFY COLUMN estado ENUM('exitoso', 'fallido', 'pendiente', 'bloqueado') DEFAULT 'pendiente'");

        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('emailable_type')->nullable()->after('descripcion');
            $table->unsignedBigInteger('emailable_id')->nullable()->after('emailable_type');
            $table->unsignedBigInteger('managed_job_id')->nullable()->after('emailable_id');

            $table->index(['emailable_type', 'emailable_id']);
            $table->foreign('managed_job_id')->references('id')->on('managed_jobs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropForeign(['managed_job_id']);
            $table->dropIndex(['emailable_type', 'emailable_id']);
            $table->dropColumn(['emailable_type', 'emailable_id', 'managed_job_id']);
        });

        DB::statement("ALTER TABLE email_logs MODIFY COLUMN estado ENUM('exitoso', 'fallido', 'pendiente') DEFAULT 'pendiente'");
    }
};
