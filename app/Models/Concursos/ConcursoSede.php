<?php

namespace App\Models\Concursos;

use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ConcursoSede extends Pivot
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = 'concursos'; // Setea la conexión a la base de datos
    protected $table = 'concurso_sede';

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }
}
