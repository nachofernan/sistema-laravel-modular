<?php

namespace App\Models\Inventario;

use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemento extends Model
{
    use HasFactory;

    protected $connection = 'inventario';

    protected $guarded = [];

    public static function createCodigo(int $categoria_id): string
    {
        $numero = Elemento::where('categoria_id', $categoria_id)->count() + 1;
        $categoria = Categoria::find($categoria_id);
        $codigo = $categoria->prefijo . str_pad($numero, 5, "0", STR_PAD_LEFT);
        return $codigo;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    public function entregaActual()
    {
        return Entrega::where('elemento_id', $this->id)->where('user_id', $this->user_id)->whereNull('fecha_devolucion')->orderBy('fecha_entrega', 'desc')->first();
    }

    public function entregaAbierta()
    {
        return Entrega::where('elemento_id', $this->id)->whereNull('fecha_devolucion')->orderBy('fecha_entrega', 'desc')->first();
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function valores()
    {
        return $this->hasMany(Valor::class);
    }

    public function findValor($caracteristica_id)
    {
        return Valor::where('caracteristica_id', $caracteristica_id)->where('elemento_id', $this->id)->first();
    }

    public function modificaciones()
    {
        return $this->hasMany(Modificacion::class);
    }
}
