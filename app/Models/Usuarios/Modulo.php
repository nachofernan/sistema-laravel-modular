<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Modulo extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';

    public $guarded = [];


    public function roles()
    {
        return Role::where('name', 'like', "{$this->nombre}/%")->orderBy('name')->get();
    }

    public function permisos()
    {
        return Permission::where('name', 'like', "{$this->nombre}/%")->orderBy('name')->get();
    }

    public static function getEstados()
    {
        $enumValues = DB::connection('usuarios')->select("SHOW COLUMNS FROM usuarios.modulos WHERE Field = 'estado'");
        $enum = $enumValues[0]->Type; // Esto devuelve algo como "enum('alto','medio','bajo')"
        $enum = substr($enum, 6, -2);
        return explode("','", $enum);
    }
}
