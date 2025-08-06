<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Capacitaciones\Invitacion;
use App\Models\Documentos\Documento;
use App\Models\Fichadas\Fichada;
use App\Models\Inventario\Elemento;
use App\Models\Inventario\Entrega;
use App\Models\Tickets\Ticket;
use App\Models\Usuarios\Area;
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
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

use App\Notifications\Auth\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    use HasRoles, HasPermissions;
    use SoftDeletes;

    

    protected $connection = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'realname',
        'email',
        'legajo',
        'interno',
        'visible',
        'area_id',
        'sede_id',
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

    public function getSistemasAcceso()
    {
        $retorno = array();
        foreach($this->getRoleNames()->toArray() as $role) {
            if($role == 'Plataforma/Admin') { continue; }
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

    //Fichadas
    public function fichadas()
    {
        return $this->hasMany(Fichada::class, 'idEmpleado', 'legajo');
    }

}
