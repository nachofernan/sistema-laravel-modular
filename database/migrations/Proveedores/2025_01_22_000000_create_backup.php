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
        DB::statement('CREATE TABLE documentos_backup LIKE documentos');
        DB::statement('INSERT INTO documentos_backup SELECT * FROM documentos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
