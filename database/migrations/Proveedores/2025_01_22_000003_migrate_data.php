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
        DB::statement("
            INSERT INTO documentos (
                archivo,
                file_storage,
                extension,
                mimeType,
                vencimiento,
                documento_tipo_id,
                user_id_created,
                user_id_updated,
                documentable_type,
                documentable_id,
                created_at,
                updated_at
            )
            SELECT 
                archivo,
                file_storage,
                extension,
                mimeType,
                vencimiento,
                documento_tipo_id,
                user_id_created,
                user_id_updated,
                '".addslashes('App\Models\Proveedores\Proveedor')."',
                proveedor_id,
                created_at,
                updated_at
            FROM documentos_backup
        ");

        // Crear validaciones para todos los documentos existentes
        DB::statement("
            INSERT INTO validacions (
                documento_id,
                user_id,
                validado,
                created_at,
                updated_at
            )
            SELECT 
                id,
                user_id_created,
                true,
                created_at,
                created_at
            FROM documentos
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
