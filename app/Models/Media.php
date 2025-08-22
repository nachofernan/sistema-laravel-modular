<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $connection = 'mysql'; // O el nombre de tu conexión principal

    public function getExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function getConnectionName()
    {
        // Fuerza siempre 127.0.0.1 sin importar el contexto
        config(['database.connections.mysql.host' => env('DB_HOST')]);
        DB::purge('mysql');
        return 'mysql';
    }
}
