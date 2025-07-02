<?php

namespace App\Http\Controllers\Concursos;

use App\Http\Controllers\Controller;
use App\Models\Concursos\DocumentoTipo;
use App\Models\Proveedores\DocumentoTipo as ProveedoresDocumentoTipo;
use Illuminate\Http\Request;

class DocumentoTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $documentoTipos_concurso = DocumentoTipo::where('de_concurso', true)->get();
        $documentoTipos_ofertas = DocumentoTipo::where('de_concurso', false)->get();
        return view('concursos.documento_tipos.index', compact('documentoTipos_concurso', 'documentoTipos_ofertas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $tipo_documento_proveedor = ProveedoresDocumentoTipo::all();
        return view('concursos.documento_tipos.create', compact('tipo_documento_proveedor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'nombre' =>'required',
                'descripcion' => '',
                'encriptado' => 'required',
                'obligatorio' => 'required',
                'de_concurso' => 'required',
            ]
        );
        if($request->input('de_concurso') == 1 || $request->input('tipo_documento_proveedor_id') == 0) {
            $request->merge(['tipo_documento_proveedor_id' => null]);
        }

        DocumentoTipo::create($request->all());
        return redirect()->route('concursos.documento_tipos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentoTipo $documento_tipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentoTipo $documento_tipo)
    {
        //
        $tipo_documento_proveedor = ProveedoresDocumentoTipo::all();
        return view('concursos.documento_tipos.edit', compact('documento_tipo', 'tipo_documento_proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentoTipo $documento_tipo)
    {
        //
        $request->validate(
            [
                'nombre' =>'required',
                'descripcion' => '',
                'encriptado' => 'required',
                'obligatorio' => 'required',
                'de_concurso' => 'required',
            ]
        );
        if($request->input('de_concurso') == 1 || $request->input('tipo_documento_proveedor_id') == 0) {
            $request->merge(['tipo_documento_proveedor_id' => null]);
        }

        $documento_tipo->update($request->all());
        return redirect()->route('concursos.documento_tipos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentoTipo $documento_tipo)
    {
        //
    }
}
