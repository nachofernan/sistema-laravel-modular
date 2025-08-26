<?php

namespace App\Models\Capacitaciones;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';

    protected $guarded = [];

    const TIPO_PRESENCIAL = 'presencial';
    const TIPO_VIRTUAL = 'virtual';

    public static function getTipos()
    {
        return [
            self::TIPO_PRESENCIAL => 'Presencial',
            self::TIPO_VIRTUAL => 'Virtual',
        ];
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
