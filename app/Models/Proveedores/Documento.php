<?php

namespace App\Models\Proveedores;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('proveedores');
    }
    
    /**
     * Retorna true si el documento está vencido a una fecha dada (o ahora si no se pasa fecha).
     */
    public function estaVencido($fecha = null)
    {
        $fecha = $fecha ? \Carbon\Carbon::parse($fecha) : now();
        return $this->vencimiento && $this->vencimiento->lt($fecha);
    }

    /**
     * Retorna true si el documento es válido (validado, no vencido a una fecha dada).
     */
    public function estaValidado()
    {
        return $this->validacion && $this->validacion->validado;
    }

    /**
     * Retorna true si el documento es válido (validado, no vencido a una fecha dada).
     */
    public function esValido($fecha = null)
    {
        $fecha = $fecha ? \Carbon\Carbon::parse($fecha) : now();
        $validado = $this->validacion && $this->validacion->validado;
        $noVencido = !$this->vencimiento || $this->vencimiento->gte($fecha);
        return $validado && $noVencido;
    }
}
