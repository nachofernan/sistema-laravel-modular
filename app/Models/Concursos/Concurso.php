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
        // Un concurso VENCIDO (precarga + fecha pasada) NO debe ser editable
        if ($this->estado_id == 1 && $this->fecha_cierre->isPast()) {
            return false;
        }

        // Un concurso CERRADO (activo + fecha pasada) NO debe ser editable
        if ($this->estado_id == 2 && $this->fecha_cierre->isPast()) {
            return false;
        }

        // Lógica original para los demás casos
        return $this->estado_id == 1 || ($this->estado_id == 2 && $this->fecha_cierre->isFuture());
        //return $this->estado_id == 1 || ($this->estado_id == 2 && $this->fecha_cierre > now());
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
        $pivotTable = DB::connection($this->getConnectionName() ?: 'concursos')->getDatabaseName().'.concurso_sede';
        return $this->belongsToMany(Sede::class, 
            $pivotTable, // Solo el nombre de la tabla
            'concurso_id', 
            'sede_id')
            ->using(ConcursoSede::class) // Especifica el modelo pivot
            ->withPivot(['id', 'concurso_id', 'sede_id'])
            ->orderBy('sede_id');
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
        return $this->hasMany(ConcursoDocumento::class);
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
        return $this->belongsToMany(DocumentoTipo::class, 'concurso_documento_tipo', 'concurso_id', 'documento_tipo_id')
            ->where('de_concurso', false)
            ->orderByPivot('documento_tipo_id');
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

        // --- LÓGICA VIRTUAL ---
    
        // Si está en precarga pero ya pasó la fecha: VENCIDO
        if ($estadoId == 1 && $this->fecha_cierre->isPast()) {
            return 'vencido';
        }
        
        // Caso especial: si es activo (id=2) pero ya pasó la fecha de cierre, es "cerrado"
        if ($estadoId == 2 && $this->fecha_cierre->isPast()) {
            return 'cerrado';
        }
        
        // Devolvemos el estado correspondiente al ID o 'desconocido' si no existe en el mapeo
        return $mapeoEstados[$estadoId] ?? 'desconocido';
    }

    // Métodos auxiliares

    /**
     * Obtiene destinatarios únicos según el tipo de grupo y si participaron o no.
     * * @param array $grupos Grupos a incluir
     * @param bool $soloParticipantes Si es true, solo trae proveedores que NO rechazaron (intencion != 2)
     */
    public function getCorreosInteresados(array $grupos = ['proveedores', 'contactos_concurso', 'contactos_proveedores'], bool $soloParticipantes = false)
    {
        $destinatarios = collect();

        // 1. Filtrar invitaciones según participación si es necesario
        $invitacionesFiltradas = $this->invitaciones;
        if ($soloParticipantes) {
            // Asumiendo que intencion != 2 son los que participan/interesados
            $invitacionesFiltradas = $invitacionesFiltradas->where('intencion', '!=', 2);
        }

        // 2. Empresa Proveedora
        if (in_array('proveedores', $grupos)) {
            foreach ($invitacionesFiltradas as $inv) {
                if ($inv->proveedor && $inv->proveedor->correo) {
                    $destinatarios->push([
                        'email' => strtolower(trim($inv->proveedor->correo)),
                        'nombre' => $inv->proveedor->razon_social,
                        'tipo' => 'Empresa'
                    ]);
                }
            }
        }

        // 3. Contactos de los proveedores (solo si el proveedor pasó el filtro anterior)
        if (in_array('contactos_proveedores', $grupos)) {
            foreach ($invitacionesFiltradas as $inv) {
                foreach ($inv->proveedor->contactos as $contacto) {
                    $destinatarios->push([
                        'email' => strtolower(trim($contacto->correo)),
                        'nombre' => $contacto->nombre,
                        'tipo' => 'Contacto Proveedor'
                    ]);
                }
            }
        }

        // 4. Contactos directos del concurso (Estos suelen ir siempre si se invita al grupo)
        if (in_array('contactos_concurso', $grupos)) {
            if($this->usuario_id) {
                $destinatarios->push([
                    'email' => strtolower(trim($this->usuario->correo)),
                    'nombre' => $this->usuario->nombre,
                    'tipo' => 'Contacto Gestor'
                ]);
            }
            foreach ($this->contactos as $contacto) {
                $destinatarios->push([
                    'email' => strtolower(trim($contacto->correo)),
                    'nombre' => $contacto->nombre,
                    'tipo' => 'Contacto Directo'
                ]);
            }
        }

        return $destinatarios->pluck('email')->unique()->values()->toArray();
    }
}
