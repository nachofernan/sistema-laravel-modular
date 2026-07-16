<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\TipoArea;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class TipoAreaController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Areas/Ver', only: ['index']),
            new Middleware('permission:Usuarios/Areas/Crear', only: ['store']),
            new Middleware('permission:Usuarios/Areas/Editar', only: ['edit', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        $tipos = TipoArea::withCount('areas')->orderBy('orden')->orderBy('nombre')->get();
        return view('usuarios.tipos_area.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        TipoArea::create($data);
        return redirect()->route('usuarios.tipos-area.index')->with('success', 'Tipo de área creado.');
    }

    public function edit(TipoArea $tipoArea)
    {
        $tipos = TipoArea::withCount('areas')->orderBy('orden')->orderBy('nombre')->get();
        return view('usuarios.tipos_area.index', compact('tipos'))->with('editing', $tipoArea);
    }

    public function update(Request $request, TipoArea $tipoArea)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        $tipoArea->update($data);
        return redirect()->route('usuarios.tipos-area.index')->with('success', 'Tipo de área actualizado.');
    }

    public function destroy(TipoArea $tipoArea)
    {
        // El FK en areas.tipo_area_id es onDelete set null: las áreas quedan sin tipo, no se borran.
        $tipoArea->delete();
        return redirect()->route('usuarios.tipos-area.index')->with('success', 'Tipo de área eliminado.');
    }
}
