<?php

namespace App\Models\Usuarios;

use App\Models\Concursos\Concurso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sede extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';
    
    public $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function concursos()
    {
        $pivotTable = DB::connection('concursos')->getDatabaseName().'.concurso_sede';
        return $this->belongsToMany(
            Concurso::class,            // Modelo relacionado
            $pivotTable,                // Tabla pivot en la base de datos de concursos (dinámica por entorno)
            'sede_id',                  // Clave foránea en la tabla pivot hacia sedes
            'concurso_id'               // Clave foránea en la tabla pivot hacia concursos
        );
    }
}
