<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\Area;
use App\Models\Usuarios\PasswordSecurity;
use App\Models\Usuarios\Sede;
use App\Models\Usuarios\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Usuarios\Modulo;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Usuarios/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Usuarios/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Usuarios/Crear', only: ['create', 'store']),
            new Middleware('permission:Usuarios/Usuarios/Eliminar', only: ['trashed']),
        ];
    }

   /*  public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:Usuarios/Usuarios/Ver'])->only(['index', 'show']);
        $this->middleware(['permission:Usuarios/Usuarios/Editar'])->only('edit', 'update');
        $this->middleware(['permission:Usuarios/Usuarios/Crear'])->only('create', 'store');
        $this->middleware(['permission:Usuarios/Usuarios/Eliminar'])->only('trashed');
    } */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
        return view('usuarios.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $areas = Area::whereNull('area_id')->get();
        $sedes = Sede::all();
        $modulos = Modulo::where('estado', '!=', 'inactivo')->orderBy('nombre')->get();
        return view('usuarios.users.create', compact('areas', 'sedes', 'modulos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $data['password'] = bcrypt($data['legajo']);
        $data['visible'] = isset($data['visible']) ? 1 : 0;
        $user = User::create($data);

        /**
         * Se agregó la parte de seguridad de contraseña y se toqueteó lo de carbon para que de que hace 31 días que se modificó.
         */
        if($user->id) {
            PasswordSecurity::create([
                'user_id' => $user->id,
                'password_expiry_days' => 180,
                'password_updated_at' => \Carbon\Carbon::now()->subDays(181),
            ]);
            $auth = User::find(Auth::user()->id);
            if ($auth->hasPermissionTo('Usuarios/Usuarios/Roles')) {
                $user->syncRoles(array_keys($request->all()['roles']));
            }

            return redirect()->route('usuarios.users.show', $user);
        } else {
            return redirect()->route('usuarios.users.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);
        return view('usuarios.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::find($id);
        $areas = Area::whereNull('area_id')->get();
        $sedes = Sede::all();
        $modulos = Modulo::where('estado', '!=', 'inactivo')->orderBy('nombre')->get();
        return view('usuarios.users.edit', compact('user', 'areas', 'sedes', 'modulos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::find($id);
        $data = $request->all();
        $data['visible'] = isset($data['visible']) ? 1 : 0;
        $user->update($data);
        $auth = User::find(Auth::user()->id);
        if ($auth->hasPermissionTo('Usuarios/Usuarios/Roles')) {
            $user->syncRoles(array_keys($request->all()['roles']));
        }

        return redirect()->route('usuarios.users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function roles(User $user)
    {
        //
        $roles = Role::orderBy('name', 'asc')->get();
        return view('usuarios.users.roles', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function sync_roles(Request $request, User $user)
    {
        //
        $permisos = $request->all()['roles'] ?? array();
        $user->syncRoles(array_keys($permisos));
        return redirect()->route('usuarios.users.roles', $user);
    }

    public function trashed()
    {
        //
        $users = User::onlyTrashed()->get();
        return view('usuarios.users.trashed', compact('users'));
    }
}
