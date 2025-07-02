<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $connection = 'inventario';
    
    protected $guarded = [];

    public function elementos()
    {
        return $this->hasMany(Elemento::class);
    }
}
