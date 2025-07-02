<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            // Campo para almacenar el destinatario original cuando hay redirección
            $table->string('destinatario_original')->nullable()->after('destinatario');
        });
    }

    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn('destinatario_original');
        });
    }
};
