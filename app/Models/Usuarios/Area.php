<?php

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $connection = 'usuarios';
    
    protected $guarded = [];

    public function padre()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function hijos()
    {
        return $this->hasMany(Area::class)->orderBy('orden');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoArea::class, 'tipo_area_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Ids de todos los descendientes (hijos, nietos, etc.).
     * Se usa para impedir que un área tome como padre a uno de sus descendientes
     * (crearía un ciclo y colgaría el render del árbol).
     *
     * @return array<int, int>
     */
    public function descendantIds(): array
    {
        $ids = [];

        foreach ($this->hijos as $hijo) {
            $ids[] = $hijo->id;
            $ids = array_merge($ids, $hijo->descendantIds());
        }

        return $ids;
    }
}
