<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuarios\Permission;
use App\Models\Usuarios\Role;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Roles/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Roles/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Roles/Crear', only: ['create', 'store']),
        ];
    }

    //
    public function index()
    {
        //
        $roles = Role::orderBy('name', 'asc')->get();
        return view('usuarios.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('usuarios.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $role = Role::create($request->all());
        $permisos = $request->all()['permissions'] ?? array();
        $role->syncPermissions(array_keys($permisos));
        return redirect()->route('usuarios.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $role = Role::find($id);
        return view('usuarios.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $role = Role::find($id);
        $role->update($request->all());
        return redirect()->route('usuarios.roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function permissions(Role $role)
    {
        //
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('usuarios.roles.permissions', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function sync_permissions(Request $request, Role $role)
    {
        //
        $permisos = $request->all()['permissions'] ?? array();
        $role->syncPermissions(array_keys($permisos));
        return redirect()->route('usuarios.roles.permissions', $role);
    }
}
