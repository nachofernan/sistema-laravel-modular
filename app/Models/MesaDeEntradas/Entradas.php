<?php

namespace App\Models\MesaDeEntradas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entradas extends Model
{
    use HasFactory;

    protected $connection = 'mesadeentradas';

    protected $guarded = [];
}
