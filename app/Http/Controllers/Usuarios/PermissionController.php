<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuarios\Permission;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller
{
    
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Permisos/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Permisos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Permisos/Crear', only: ['create', 'store']),
        ];
    }

    //
    public function index()
    {
        //
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('usuarios.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('usuarios.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Permission::create($request->all());
        return redirect()->route('usuarios.permissions.index');
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
        $permission = Permission::find($id);
        return view('usuarios.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->route('usuarios.permissions.edit', $permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
