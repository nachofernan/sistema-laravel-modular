<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $connection = 'tickets';

    protected $guarded = false;

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
