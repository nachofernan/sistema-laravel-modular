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
        Schema::create('modificacions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('elemento_id');
            $table->foreign('elemento_id')->references('id')->on('elementos')->onDelete('cascade');

            $table->string('modificacion');
            $table->string('valor_nuevo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modificacions');
    }
};
