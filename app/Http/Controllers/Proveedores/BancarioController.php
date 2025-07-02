<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Bancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BancarioController extends Controller
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
            'tipocuenta' => 'required',
            'numerocuenta' => 'required',
            'cbu' => 'required',
            'banco' => 'required',
            'sucursal' => 'required',
        ]);
        $dbancario = Bancario::create(array_merge($request->all(), ['user_id_created' => Auth::id()]));

        return redirect()->route('proveedores.proveedors.show', $dbancario->proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bancario $bancario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bancario $bancario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bancario $bancario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bancario $bancario)
    {
        //
    }
}
