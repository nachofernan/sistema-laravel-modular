<?php

namespace App\Models\Proveedores;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoTipo extends Model
{
    use HasFactory;

    protected $connection = 'proveedores';

    protected $guarded = [];

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    /* public function documentosProveedor(Proveedor $proveedor)
    {
        return $this->hasMany(Documento::class)->where('documentable_id', $proveedor->id)->where('documentable_type', 'Proveedor')->get();
    } */
    public function documentosProveedor(Proveedor $proveedor)
    {
        return $this->hasMany(Documento::class)->whereMorphedTo('documentable', $proveedor);
    }
}
