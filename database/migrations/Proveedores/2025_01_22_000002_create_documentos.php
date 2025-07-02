<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            $table->index(['documentable_type', 'documentable_id']);

            $table->unsignedBigInteger('documento_tipo_id')->nullable(); // Nullable para apoderados
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id_created');
            $table->unsignedBigInteger('user_id_updated')->nullable();
            
            $table->timestamps();
        });

        Schema::create('validacions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('documento_id');
            $table->foreign('documento_id')->references('id')->on('documentos')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('validado')->default(false);
            
            $table->text('comentarios')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
