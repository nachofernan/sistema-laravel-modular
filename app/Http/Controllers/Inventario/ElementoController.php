<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\Area;
use App\Models\Inventario\Elemento;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Estado;
use App\Models\Inventario\Modificacion;
use App\Models\Inventario\Valor;
use App\Models\Usuarios\Sede;
use App\Models\User;
use App\Exports\Inventario\ElementosExport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;

class ElementoController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Inventario/Elementos/Ver', only: ['index', 'show', 'exportar']),
            new Middleware('permission:Inventario/Elementos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Inventario/Elementos/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventario.elementos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $areas = Area::whereNull('area_id')->get();
        $sedes = Sede::all();
        $users = User::all();
        $categorias = Categoria::all();
        $estados = Estado::all();
        return view('inventario.elementos.create', compact('areas', 'sedes', 'users', 'categorias', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|integer',
            'estado_id'    => 'required|integer',
            'nombre'       => 'nullable|string|max:255',
            'descripcion'  => 'nullable|string',
            'numero_serie' => 'nullable|string|max:255',
            'user_id'      => 'nullable|integer',
            'sede_id'      => 'nullable|integer',
            'area_id'      => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['codigo'] = Elemento::createCodigo($data['categoria_id']);
        $elemento = Elemento::create($data);
        return redirect()->route('inventario.elementos.show', $elemento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Elemento $elemento)
    {
        //
        $areas = Area::whereNull('area_id')->get();
        $sedes = Sede::all();
        $users = User::all();
        $categorias = Categoria::all();
        $estados = Estado::all();
        return view('inventario.elementos.show', compact('elemento', 'areas', 'sedes', 'users', 'categorias', 'estados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elemento $elemento)
    {
        //
        $areas = Area::whereNull('area_id')->get();
        $sedes = Sede::all();
        $users = User::all();
        $categorias = Categoria::all();
        $estados = Estado::all();
        return view('inventario.elementos.edit', compact('elemento', 'areas', 'sedes', 'users', 'categorias', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Elemento $elemento)
    {
        //
        $elemento->update($request->except(['valor']));
        if($request->valor) {
            foreach($request->valor as $key => $valor) {
                if(is_numeric($key)) {
                    $elemento_valor = Valor::find($key);
                    if($elemento_valor->valor != $valor) {
                        $elemento_valor->update([
                            'valor' => $valor ?? '',
                        ]);
                    }
                } else {
                    Valor::create([
                        'valor' => $valor ?? '',
                        'elemento_id' => $elemento->id,
                        'caracteristica_id' => substr($key,1),
                    ]);
                }
            }
        }
        return redirect()->route('inventario.elementos.show', $elemento);
    }

    public function exportar()
    {
        return Excel::download(new ElementosExport(), 'inventario-elementos-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elemento $elemento)
    {
        //
    }
}
