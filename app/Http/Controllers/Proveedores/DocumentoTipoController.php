<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\User;
use App\Rules\UniqueValue;
use Illuminate\Http\Request;

class DocumentoTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $documentoTipos = DocumentoTipo::orderBy('codigo')->get();
        return view('proveedores.documento_tipos.index', compact('documentoTipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('proveedores.documento_tipos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'nombre' => 'required',
                'vencimiento' => 'required',
                'codigo' => [
                    'required',
                    new UniqueValue('documento_tipos', 'codigo', 'proveedores')
                ],
            ]);
        $documentoTipo = DocumentoTipo::create($request->all());

        return redirect()->route('proveedores.documento_tipos.show', $documentoTipo)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentoTipo $documentoTipo)
    {
        //
        return view('proveedores.documento_tipos.show', compact('documentoTipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentoTipo $documentoTipo)
    {
        //
        $users = User::pluck('realname', 'id')->prepend('Sin Validador', null);
        return view('proveedores.documento_tipos.edit', compact('documentoTipo', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentoTipo $documentoTipo)
    {
        //
        $request->validate([
            'nombre' => 'required',
            'vencimiento' => 'required'
        ]);
        $documentoTipo->update($request->all());

        return redirect()->route('proveedores.documento_tipos.edit', $documentoTipo)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentoTipo $documentoTipo)
    {
        //
    }
}
