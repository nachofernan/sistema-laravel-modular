<?php

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';
    
    protected $guarded = [];

    public function padre()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function hijos()
    {
        return $this->hasMany(Area::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
