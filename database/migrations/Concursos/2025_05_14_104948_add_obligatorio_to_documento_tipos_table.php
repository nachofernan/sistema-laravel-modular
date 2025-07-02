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
        Schema::table('documento_tipos', function (Blueprint $table) {
            //
            $table->boolean('obligatorio')->default(false)->after('encriptado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documento_tipos', function (Blueprint $table) {
            //
            $table->dropColumn('obligatorio');
        });
    }
};
