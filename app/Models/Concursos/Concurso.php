<?php

namespace App\Models\Concursos;

use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Subrubro;
use App\Models\Shared\Pivots\ConcursoProveedor;
use App\Models\User;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class Concurso extends Model
{
    use HasFactory;

    protected $connection = 'concursos';

    protected $guarded = false;

    public function editable() : bool {
        return $this->estado_id == 1 || ($this->estado_id == 2 && $this->fecha_cierre > now());
    }

    /* public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'concursos.concurso_proveedor', 'concurso_id', 'proveedor_id')
            ->using(ConcursoProveedor::class)
            ->withPivot(['id'])
            ->withTimestamps();
    } */

    public function invitaciones()
    {
        return $this->hasMany(Invitacion::class);
    }

    public function estado() {
        return $this->belongsTo(Estado::class);
    }

    public function subrubro() {
        return $this->belongsTo(Subrubro::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* public function sedes()
    {
        return $this->belongsToMany(ConcursoSede::class,
        'concursos.concurso_sede', // Incluye el prefijo de la base de datos
        'concurso_id',
        'sede_id');
    } */
    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 
            'concursos.concurso_sede', 
            'concurso_id', 
            'sede_id')
            ->using(ConcursoSede::class)
            ->withPivot(['id', 'concurso_id', 'sede_id'])->orderBy('sede_id'); // Especifica los campos pivot que quieres
    }
    /* public function sedes()
    {
        return $this->belongsToMany(
            Sede::class, // Modelo relacionado
            ConcursoSede::class, // Modelo de la tabla pivot para especificar la conexión
            'concurso_id', // Clave foránea del modelo actual (Concurso)
            'sede_id' // Clave foránea del modelo relacionado (Sede)
        );
    } */
   

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function prorrogas()
    {
        return $this->hasMany(Prorroga::class);
    }

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    public function documentos_requeridos()
    {
        return $this->belongsToMany(DocumentoTipo::class, 'concurso_documento_tipo', 'concurso_id', 'documento_tipo_id')->orderByPivot('documento_tipo_id');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class)->orderBy('tipo');
    }

    public function historial()
    {
        return $this->hasMany(Historial::class);
    }

    /**
     * Obtiene el estado actual real del concurso.
     * 
     * @return string El estado actual: 'precarga', 'activo', 'cerrado', 'analisis', 'terminado', 'anulado'
     */
    public function getEstadoActualAttribute()
    {
        // Mapeo de IDs de estado a nombres de estado
        $mapeoEstados = [
            1 => 'precarga',
            2 => 'activo',
            3 => 'analisis',
            4 => 'terminado',
            5 => 'anulado'
        ];
        
        // Obtenemos el ID del estado del concurso
        $estadoId = $this->estado->id;
        
        // Caso especial: si es activo (id=2) pero ya pasó la fecha de cierre, es "cerrado"
        if ($estadoId == 2 && $this->fecha_cierre->isPast()) {
            return 'cerrado';
        }
        
        // Devolvemos el estado correspondiente al ID o 'desconocido' si no existe en el mapeo
        return $mapeoEstados[$estadoId] ?? 'desconocido';
    }

    // Métodos auxiliares
    public function obtenerProveedoresInvitados()
    {
        return $this->invitaciones()
            ->with('proveedor')
            ->get()
            ->pluck('proveedor.correo')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function obtenerProveedoresParticipantes()
    {
        return $this->invitaciones()
            ->with('proveedor')
            ->where('intencion', '!=', 2)
            ->get()
            ->pluck('proveedor.correo')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}
