<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $connection = 'tickets';

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
