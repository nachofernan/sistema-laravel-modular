<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\Cargo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class CargoController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Usuarios/Ver', only: ['index']),
            new Middleware('permission:Usuarios/Usuarios/Crear', only: ['store']),
            new Middleware('permission:Usuarios/Usuarios/Editar', only: ['edit', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        $cargos = Cargo::withCount('users')->orderBy('orden')->orderBy('nombre')->get();
        return view('usuarios.cargos.index', compact('cargos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        Cargo::create($data);
        return redirect()->route('usuarios.cargos.index')->with('success', 'Cargo creado.');
    }

    public function edit(Cargo $cargo)
    {
        $cargos = Cargo::withCount('users')->orderBy('orden')->orderBy('nombre')->get();
        return view('usuarios.cargos.index', compact('cargos'))->with('editing', $cargo);
    }

    public function update(Request $request, Cargo $cargo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);

        $cargo->update($data);
        return redirect()->route('usuarios.cargos.index')->with('success', 'Cargo actualizado.');
    }

    public function destroy(Cargo $cargo)
    {
        // El FK en users.cargo_id es onDelete set null: los usuarios quedan sin cargo, no se borran.
        $cargo->delete();
        return redirect()->route('usuarios.cargos.index')->with('success', 'Cargo eliminado.');
    }
}
