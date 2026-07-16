<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\Area;
use App\Models\Usuarios\TipoArea;
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
        $areas = Area::whereNull('area_id')->orderBy('orden')->get();
        return view('usuarios.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoArea::orderBy('orden')->orderBy('nombre')->get();
        return view('usuarios.areas.create', compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_area_id' => 'nullable|exists:usuarios.tipos_area,id',
            'area_id' => 'nullable|exists:usuarios.areas,id',
        ]);

        $area = Area::create($data);
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
        $areas = Area::whereNull('area_id')->orderBy('orden')->get();
        $tipos = TipoArea::orderBy('orden')->orderBy('nombre')->get();
        // El personal del área y el responsable se gestionan en el componente Livewire Miembros.
        return view('usuarios.areas.edit', compact('area', 'areas', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_area_id' => 'nullable|exists:usuarios.tipos_area,id',
            'area_id' => 'nullable|exists:usuarios.areas,id',
            'orden' => 'nullable|integer|min:0',
            'activa' => 'nullable|boolean',
        ]);

        // Un área no puede tener como padre a sí misma ni a uno de sus descendientes (evita ciclos).
        $invalidos = array_merge([$area->id], $area->descendantIds());
        if (in_array($data['area_id'] ?? null, $invalidos)) {
            $data['area_id'] = null;
        }

        $data['activa'] = $request->boolean('activa');

        $area->update($data);
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
