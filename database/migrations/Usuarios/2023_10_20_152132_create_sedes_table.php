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
        Schema::create('sedes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');

            $table->timestamps();
        });
        
        Schema::table('users', function($table) {

            $table->unsignedBigInteger('sede_id')->nullable()->after('area_id');
            $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Drop foreign key constraints
            $table->dropForeign(['sede_id']);
            // 2. Drop the column
            $table->dropColumn('sede_id');
        });

        Schema::dropIfExists('sedes');
    }
};
