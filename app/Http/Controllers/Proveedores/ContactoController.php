<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
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
            'nombre' => 'required_without_all:telefono,correo',
            'telefono' => 'required_without_all:nombre,correo',
            'correo' => 'required_without_all:nombre,telefono',
        ]);
        $contacto = Contacto::create(array_merge($request->all(), ['user_id_created' => Auth::id()]));

        return redirect()->route('proveedores.proveedors.show', $contacto->proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacto $contacto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacto $contacto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacto $contacto)
    {
        //
        $contacto->update($request->all());

        return redirect()->route('proveedores.proveedors.show', $contacto->proveedor)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacto $contacto)
    {
        //
        $proveedor = $contacto->proveedor;
        $contacto->delete();
        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó con éxito');
    }
}
