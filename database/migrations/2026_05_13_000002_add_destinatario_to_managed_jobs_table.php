<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('managed_jobs', function (Blueprint $table) {
            $table->string('destinatario')->nullable()->after('job_type');
        });

        // Poblar destinatario desde el metadata existente
        DB::statement("
            UPDATE managed_jobs
            SET destinatario = JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.destinatario'))
            WHERE JSON_EXTRACT(metadata, '$.destinatario') IS NOT NULL
        ");

        Schema::table('managed_jobs', function (Blueprint $table) {
            $table->index('destinatario');
        });
    }

    public function down(): void
    {
        Schema::table('managed_jobs', function (Blueprint $table) {
            $table->dropIndex(['destinatario']);
            $table->dropColumn('destinatario');
        });
    }
};
