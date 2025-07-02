<?php

namespace App\Models\Proveedores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorExterno extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = 'proveedores_externos'; // Setea la conexión a la base de datos

    protected $table = 'users';

    public function proveedorInterno()
    {
        return $this->belongsTo(Proveedor::class, 'username', 'cuit');
    }
}
