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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');

            $table->timestamps();
        });

        Schema::table('users', function($table) {

            $table->unsignedBigInteger('area_id')->nullable()->after('interno');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Drop foreign key constraints
            $table->dropForeign(['area_id']);
            // 2. Drop the column
            $table->dropColumn('area_id');
        });

        Schema::dropIfExists('areas');
    }
};
