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
        Schema::table('copres', function (Blueprint $table) {
            $table->boolean('es_original')->default(true)->after('importe_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copres', function (Blueprint $table) {
            $table->dropColumn('es_original');
        });
    }
};
