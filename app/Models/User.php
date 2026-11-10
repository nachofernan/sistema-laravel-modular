<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Capacitaciones\Invitacion;
use App\Models\Documentos\Documento;
use App\Models\Inventario\Elemento;
use App\Models\Inventario\Entrega;
use App\Models\Tickets\Ticket;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Cargo;
use App\Models\Usuarios\Log;
use App\Models\Usuarios\PasswordSecurity;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasPermissions, HasRoles;
    use HasProfilePhoto;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    protected $connection = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'realname',
        'nombre',
        'apellido',
        'email',
        'legajo',
        'interno',
        'visible',
        'area_id',
        'sede_id',
        'cargo_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Qué cambios del usuario quedan en el historial (activity_log, base usuarios).
     * Nunca se registran contraseña, remember_token ni secretos de 2FA.
     *
     * Cubierto por: editar_un_usuario_registra_valor_anterior_y_nuevo,
     * editar_la_contrasena_no_guarda_la_contrasena_en_el_historial.
     */
    public function getActivitylogOptions(): LogOptions
    {
        $descripciones = [
            'created' => 'creado',
            'updated' => 'editado',
            'deleted' => 'eliminado',
            'restored' => 'restaurado',
        ];

        return LogOptions::defaults()
            ->useLogName('usuarios')
            ->logOnly([
                'name',
                'realname',
                'nombre',
                'apellido',
                'email',
                'legajo',
                'interno',
                'visible',
                'area_id',
                'sede_id',
                'cargo_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $evento) => $descripciones[$evento] ?? $evento);
    }

    /**
     * Sincroniza los roles del usuario y deja en el historial cuáles se agregaron y
     * cuáles se quitaron. Hace falta a mano porque syncRoles() no dispara eventos de
     * Eloquent. Si no cambió nada, no registra.
     *
     * Cubierto por: sincronizar_roles_registra_agregados_y_quitados,
     * sincronizar_los_mismos_roles_no_registra_nada.
     *
     * @param  array<int, string>  $roles  nombres de roles
     */
    public function sincronizarRoles(array $roles): void
    {
        $antes = $this->getRoleNames();

        $this->syncRoles($roles);

        $despues = $this->load('roles')->getRoleNames();
        $agregados = $despues->diff($antes)->values()->all();
        $quitados = $antes->diff($despues)->values()->all();

        if ($agregados === [] && $quitados === []) {
            return;
        }

        activity('usuarios')
            ->performedOn($this)
            ->event('roles')
            ->withProperties(['agregados' => $agregados, 'quitados' => $quitados])
            ->log('roles modificados');
    }

    public function getSistemasAcceso()
    {
        $retorno = [];
        foreach ($this->getRoleNames()->toArray() as $role) {
            if ($role == 'Plataforma/Admin') {
                continue;
            }
            $retorno[] = explode('/', $role)[0];
        }

        return array_unique($retorno);
    }

    /**
     * Verificar si el usuario es super-admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains('name', 'Plataforma/Admin');
    }

    /**
     * Un usuario tiene una seguridad en su contraseña
     */
    public function passwordSecurity()
    {
        return $this->hasOne(PasswordSecurity::class);
    }

    // Usuario
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function getNombreCompletoAttribute()
    {
        // realname es el nombre real; name se usa como username interno, así que
        // queda como último recurso cuando no hay nada mejor cargado.
        if (filled($this->realname)) {
            return $this->realname;
        }

        $completo = trim(($this->apellido ? $this->apellido.', ' : '').$this->nombre);

        return $completo !== '' ? $completo : $this->name;
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function getLastAccessAttribute()
    {
        return Log::where('user_id', $this->id)->where('event', 'login')->orderBy('created_at', 'desc')->first();
    }

    // Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketsAbiertos()
    {
        return $this->hasMany(Ticket::class)->whereNull('finalizado')->get();
    }

    public function ticketsCerrados($limit = null)
    {
        return $this->hasMany(Ticket::class)->whereNotNull('finalizado')->limit($limit)->get();
    }

    // Inventario
    public function elementos()
    {
        return Elemento::where('user_id', $this->id)->get();
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    // Documentos
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    // Capacitaciones
    public function invitaciones()
    {
        return $this->hasMany(Invitacion::class);
    }

    public function tieneEncuestasPendientes(): bool
    {
        return $this->invitaciones()
            ->with('capacitacion.encuestas.preguntas')
            ->get()
            ->contains(fn ($invitacion) => $invitacion->tieneEncuestasPendientes());
    }
}
