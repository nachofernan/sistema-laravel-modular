<?php

namespace App\Models\Adminip;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    
    protected $connection = 'adminip';

    protected $guarded = [];

}
