<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Categoria as DocumentoCategoria;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use App\Models\Tickets\Categoria as TicketCategoria;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function dashboard()
    {
        $ticket_categorias = TicketCategoria::all();

        return view('home.dashboard', compact('ticket_categorias'));
    }

    public function documentoCategoria(DocumentoCategoria $categoria)
    {
        abort_unless($categoria->esPublica(), 404);

        $categoria->load(['hijos' => fn ($query) => $query->where('publica', true), 'hijos.documentosPublicos']);

        return view('home.documentos.categoria.show', compact('categoria'));
    }

    /**
     * Sirve el archivo y registra la descarga. Un documento que no es público no se
     * entrega aunque se conozca su ID: el link no alcanza como autorización.
     */
    public function documentoDownload(Documento $documento)
    {
        abort_unless($documento->esPublico(), 404);

        $media = $documento->getFirstMedia('archivos');

        abort_if($media === null, 404, 'Archivo no encontrado');

        Descarga::create([
            'documento_id' => $documento->id,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
        ]);

        return $media->toResponse(request());
    }
}
