<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Una descarga sin login deja de atribuirse al usuario 1: `user_id` pasa a ser
 * nullable. Se agrega `ip`, que la documentación del módulo daba por existente
 * pero nunca se había creado.
 *
 * Las descargas históricas no se tocan: no hay forma de distinguir cuáles fueron
 * realmente del usuario 1 y cuáles de un invitado.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('descargas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('ip', 45)->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('descargas', function (Blueprint $table) {
            $table->dropColumn('ip');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
