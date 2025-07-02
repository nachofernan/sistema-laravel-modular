<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificacion extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public function elemento()
    {
        return $this->belongsTo(Elemento::class);
    }
}
