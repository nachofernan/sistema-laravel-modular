<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';

    protected $guarded = [];

    protected static $eventDictionary = [
        'password_change' => 'Cambio de contraseña',
        'login' => 'Inicio de sesión',
        // Añade más eventos según sea necesario
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Método para obtener el nombre amigable del evento
    public static function getFriendlyEventName($eventName)
    {
        return self::$eventDictionary[$eventName] ?? 'Evento desconocido';
    }

    // Método de instancia para obtener el nombre amigable del evento del registro actual
    public function getEventoAttribute()
    {
        return self::getFriendlyEventName($this->event);
    }
}
