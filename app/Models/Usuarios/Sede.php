<?php

namespace App\Models\Usuarios;

use App\Models\Concursos\Concurso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(
            Concurso::class,            // Modelo relacionado
            'concursos.concurso_sede',  // Tabla pivot con prefijo de base de datos
            'sede_id',                  // Clave foránea en la tabla pivot hacia sedes
            'concurso_id'               // Clave foránea en la tabla pivot hacia concursos
        );
    }
}
