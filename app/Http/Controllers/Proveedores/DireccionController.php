<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'proveedor_id' => 'required',
            'tipo' => 'required',
            'calle' => 'required',
            'altura' => 'required',
            'ciudad' => 'required',
            'codigopostal' => 'required',
            'provincia' => 'required',
            'pais' => 'required',
        ]);
        $direccion = Direccion::create(array_merge($request->all(), ['user_id_created' => Auth::id()]));

        return redirect()->route('proveedores.proveedors.show', $direccion->proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Direccion $direccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Direccion $direccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Direccion $direccion)
    {
        //
        $direccion->update($request->all());
        return redirect()->route('proveedores.proveedors.show', $direccion->proveedor)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Direccion $direccion)
    {
        //
        $proveedor = $direccion->proveedor;
        $direccion->delete();
        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó con éxito');
    }
}
