<?php

namespace App\Models\Fichadas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichada extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = 'fichadas'; // Setea la conexión a la base de datos

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idEmpleado', 'legajo');
    }
}
