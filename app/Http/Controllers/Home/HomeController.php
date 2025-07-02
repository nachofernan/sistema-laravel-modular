<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use App\Models\Tickets\Categoria as TicketCategoria;
use App\Models\Documentos\Categoria as DocumentoCategoria;
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
        return view('home.documentos.categoria.show', compact('categoria'));
    }

    public function documentoDownload(Documento $documento)
    {
        Descarga::create([
            'documento_id' => $documento->id,
            'user_id' => Auth::user()->id ?? 1,
        ]);
        return response()->file(storage_path('app/public/documentos/').$documento->file_storage);
    }
}
