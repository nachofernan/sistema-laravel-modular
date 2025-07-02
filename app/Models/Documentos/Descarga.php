<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descarga extends Model
{
    use HasFactory;

    protected $connection = 'documentos';
    
    protected $guarded = [];

}
