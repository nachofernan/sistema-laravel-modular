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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->string('archivo');
            $table->string('file_storage');
            $table->string('extension')->nullable();
            $table->string('mimeType')->nullable();

            $table->date('vencimiento')->nullable();

            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('user_id_created');
            $table->unsignedBigInteger('user_id_updated')->nullable();
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
