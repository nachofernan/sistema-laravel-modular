<?php

namespace App\Http\Controllers\Proveedores;

use App\Exports\Proveedores\ProveedorExport;
use App\Http\Controllers\Controller;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Estado;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Rubro;
use App\Models\User;
use App\Rules\UniqueValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\Middleware;

class ProveedorController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Proveedores/Proveedores/Ver', only: ['index', 'show', 'export']),
            new Middleware('permission:Proveedores/Proveedores/Editar|Proveedores/Proveedores/EditarEstado', only: ['edit', 'update']),
            new Middleware('permission:Proveedores/Proveedores/Editar', only: ['store', 'create']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('proveedores.proveedors.index');
    }

    public function eliminados()
    {
        //
        return view('proveedores.proveedors.eliminados');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('proveedores.proveedors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'cuit' => [
                'required',
                'numeric',
                'min:1000000',
                'max:999999999999999',
                new UniqueValue('proveedors', 'cuit', 'proveedores')
            ],
            'razonsocial' => 'required',
            'correo' => [
                'required',
                'email:rfc',
                new UniqueValue('proveedors', 'correo', 'proveedores')
            ],
        ], [
            'unique' => 'El :attribute ya está en uso',
            'email' => 'El correo ingresado no es un correo válido',
            'numeric' => 'Sólo se aceptan números entre 10 y 15 cifras',
            'min' => 'Sólo se aceptan números entre 10 y 15 cifras',
            'max' => 'Sólo se aceptan números entre 10 y 15 cifras'
        ]);
        $webpage = $request->all();
        if($webpage['webpage']) {
            if(!preg_match('|^(http(s)?:\/\/){1,1}[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $webpage['webpage'])) {
                $request->merge(['webpage' => 'http://' . $request->webpage]);
            }
        }
        $proveedor = Proveedor::create(array_merge($request->all(), ['estado_id' => '1', 'user_id_created' => Auth::id()]));

        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        //
        $documentoTipos = array();
        foreach(DocumentoTipo::orderBy('codigo', 'asc')->get() as $td) {
            $documentoTipos[$td->id] = $td->codigo . ' - ' . $td->nombre;
        }
        //$documentoTipos = Tipodocumento::pluck('nombre', 'id');
        return view('proveedores.proveedors.show', compact('proveedor', 'documentoTipos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        //
        $estados = Estado::pluck('estado', 'id');
        return view('proveedores.proveedors.edit', compact('proveedor', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        //
        $user = User::find(Auth::user()->id);
        if($user->can('Proveedores/Proveedores/EditarEstado')) {
            $request->validate([
                'estado_id' => 'required',
            ]);
            $litigio = $request->has('litigio') ? 1 : 0;
            $request->merge(['litigio' => $litigio]);
        } 
        if($user->can('Proveedores/Proveedores/Editar')) {
            $request->validate([
                'cuit' => [
                    'required',
                    'numeric',
                    'min:1000000',
                    'max:999999999999999',
                    new UniqueValue('proveedors', 'cuit', 'proveedores', $proveedor->id)
                ],
                'razonsocial' => 'required',
                'correo' => [
                    'required',
                    'email:rfc',
                    new UniqueValue('proveedors', 'correo', 'proveedores', $proveedor->id)
                ],
            ], [
                'unique' => 'El :attribute ya está en uso',
                'email' => 'El correo ingresado no es un correo válido',
                'numeric' => 'Sólo se aceptan números entre 10 y 15 cifras',
                'min' => 'Sólo se aceptan números entre 10 y 15 cifras',
                'max' => 'Sólo se aceptan números entre 10 y 15 cifras'
            ]);
            if($request->input('webpage', null)) {
                if(!preg_match('|^(http(s)?:\/\/){1,1}[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $request->input('webpage'))) {
                    $request->merge(['webpage' => 'http://' . $request->webpage]);
                }
            }
        }
        $proveedor->update($request->all());
        /* if($proveedor->proveedor_externo) {
            $proveedor->proveedor_externo->update([
                'username' => $proveedor->cuit,
                'email' => $proveedor->correo,
                'name' => $proveedor->razonsocial,
            ]);
        } */

        return redirect()->route('proveedores.proveedors.edit', $proveedor)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        //
    }

    public function export() 
    {
        $nombre = 'Proveedores-' . Carbon::now()->format('dmYHi') . '.xlsx';
        return (new ProveedorExport)->download($nombre);
    }

    public function anexosolped()
    {
        return view('proveedores.anexosolped.create');
    }

}
