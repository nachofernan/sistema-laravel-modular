<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function valores()
    {
        return $this->hasMany(Valor::class);
    }
    
    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }
}
