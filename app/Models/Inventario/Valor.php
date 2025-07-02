<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valor extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public function elemento()
    {
        return $this->belongsTo(Elemento::class);
    }

    public function caracteristica()
    {
        return $this->belongsTo(Caracteristica::class);
    }

    public function opcion()
    {
        return $this->belongsTo(Opcion::class, 'valor');
    }
}
