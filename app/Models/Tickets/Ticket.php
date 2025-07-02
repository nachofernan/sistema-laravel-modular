<?php

namespace App\Models\Tickets;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $connection = 'tickets';

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class);
    }

    public function documento()
    {
        return $this->hasOne(Documento::class);
    }

    public function mensajesNuevos()
    {
        return Mensaje::where('ticket_id', $this->id)->where('user_id', $this->user_id)->where('leido', '0')->count();
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'user_encargado_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
