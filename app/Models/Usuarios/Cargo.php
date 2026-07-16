<?php

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
