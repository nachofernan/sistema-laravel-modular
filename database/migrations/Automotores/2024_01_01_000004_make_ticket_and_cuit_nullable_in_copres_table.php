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
            $table->string('numero_ticket_factura')->nullable()->change();
            $table->string('cuit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copres', function (Blueprint $table) {
            $table->string('numero_ticket_factura')->nullable(false)->change();
            $table->string('cuit')->nullable(false)->change();
        });
    }
};
