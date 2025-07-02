<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Helpers\EmailHelper;
use App\Mail\Tickets\Notificacion\TicketNuevo;
use App\Models\Tickets\Documento;
use App\Models\Tickets\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
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
        //$ticket = Ticket::create($request->all());
        $ticket = Ticket::create([
            'categoria_id' => $request->categoria_id,
            'notas' => $request->notas,
        ]);

        $ticket->user_id = Auth::user()->id;
        $ticket->estado_id = '1';
        $ticket->codigo = str_pad($ticket->id, 6, "0", STR_PAD_LEFT);
        $ticket->save();

		if($request->file('documento')) {
            $file = $request->file('documento');
            $file_storage = $file->hashName();
            if(Storage::disk('tickets')->put($file_storage, file_get_contents($file))) {
                $documento = new Documento([
                    'archivo' => $file->getClientOriginalName(),
                    'mimeType' => $file->getClientMimeType(),
                    'extension' => $file->extension(),
                    'file_storage' => $file_storage,
                    'user_id_created' => Auth::user()->id,
                    'ticket_id' => $ticket->id,
                ]);
                $documento->save();
            }
        }


        //Mail::to(['givaldi@ccasa.com.ar', 'emartinez@ccasa.com.ar', 'azugazua@ccasa.com.ar'])->send(new TicketNuevo($ticket));
        //Mail::to(['ifernandez@ccasa.com.ar'])->send(new TicketNuevo($ticket));
        /* foreach (['givaldi@ccasa.com.ar', 'emartinez@ccasa.com.ar', 'azugazua@ccasa.com.ar'] as $destinatario) {
            // Despachar el Job con el tiempo calculado
            EnviarTicketNuevoEmail::dispatch(
                $ticket, 
                [$destinatario] // Un solo destinatario por correo
            )->delay(0); // Cambia el tiempo de retraso según sea necesario
        } */
        EmailHelper::enviarNotificacion(
            [/* 'givaldi@ccasa.com.ar',  */'emartinez@ccasa.com.ar', 'azugazua@ccasa.com.ar'],
            new TicketNuevo($ticket),
            'Ingreso de nuevo Ticket'
        );


        return redirect()->route('home.tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
        return view('home.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function documentos(Ticket $ticket)
    {
        if($ticket->documento != null) {
            // Verifica si el archivo existe en el disco "tickets"
            if (!Storage::disk('tickets')->exists($ticket->documento->file_storage)) {
                abort(404, 'Archivo no encontrado.');
            }

            // Devuelve la descarga con el nombre personalizado
            $filePath = Storage::disk('tickets')->path($ticket->documento->file_storage);
            return response()->download($filePath, $ticket->documento->archivo);
        }
    }
}
