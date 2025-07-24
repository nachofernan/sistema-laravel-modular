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
            $documento = new Documento([
                'user_id_created' => Auth::user()->id,
                'ticket_id' => $ticket->id,
            ]);
            $documento->save();
            
            $media = $documento->addMediaFromRequest('documento')
                ->usingFileName($request->file('documento')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->save();
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
            $media = $ticket->documento->getFirstMedia('archivos');
            if ($media) {
                return $media->toResponse(request());
            }
            abort(404, 'Archivo no encontrado.');
        }
        abort(404, 'No hay documento asociado a este ticket.');
    }
}
