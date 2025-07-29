<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public function caracteristica()
    {
        return $this->belongsTo(Caracteristica::class);
    }
}
