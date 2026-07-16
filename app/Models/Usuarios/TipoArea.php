<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArea extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';

    protected $table = 'tipos_area';

    protected $guarded = [];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
