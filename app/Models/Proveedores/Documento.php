<?php

namespace App\Models\Proveedores;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $connection = 'proveedores';

    protected $guarded = [];

    protected $casts = [
        'vencimiento' => 'datetime',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function proveedor()
    {
        if($this->documentable_type == 'App\Models\Proveedores\Proveedor') {
            return $this->documentable;
        } else {
            return $this->documentable->proveedor;
        }
    }

    public function documentoTipo()
    {
        return $this->belongsTo(DocumentoTipo::class, 'documento_tipo_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function actualizador()
    {
        return $this->belongsTo(User::class, 'user_id_updated');
    }

    public function validacion()
    {
        return $this->hasOne(Validacion::class);
    }

    public function requiereVencimiento()
    {
        // Si tiene documentoTipo y este requiere vencimiento
        if ($this->documentoTipo && $this->documentoTipo->vencimiento) {
            return true;
        }
        
        // Si no tiene documentoTipo pero ya tiene un vencimiento establecido
        if (!$this->documentoTipo && $this->vencimiento) {
            return true;
        }
        
        return false;
    }

    public function tieneVencimiento()
    {
        return !is_null($this->vencimiento);
    }
}
