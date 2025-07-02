<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;

class AreaController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Areas/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Areas/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Areas/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $areas = Area::whereNull('area_id')->get();
        return view('usuarios.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('usuarios.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $area = Area::create($request->all());
        return redirect()->route('usuarios.areas.edit', $area);
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        //
        $areas = Area::whereNull('area_id')->get();
        return view('usuarios.areas.edit', compact('area', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        //
        $area->update($request->all());
        return redirect()->route('usuarios.areas.edit', $area);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        //
    }
}
