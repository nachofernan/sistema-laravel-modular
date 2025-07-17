<?php

namespace App\Http\Controllers\Tickets;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\Tickets\Notificacion\CambioEncargado;
use App\Models\Tickets\Mensaje;
use App\Models\Tickets\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Tickets/Tickets/Ver', only: ['index', 'show']),
            new Middleware('permission:Tickets/Tickets/Editar', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tickets = Ticket::all();
        return view('tickets.tickets.index', compact('tickets'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
        return view('tickets.tickets.show', compact('ticket'));
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
        $ticket->fill($request->all());
        if($ticket->isDirty('categoria_id')) {
            Mensaje::create(['mensaje' => 'Cambio de Categoría a: ' . $ticket->categoria->nombre, 'ticket_id' => $ticket->id, 'leido' => 1 ]);
        }
        if($ticket->isDirty('estado_id')) {
            Mensaje::create(['mensaje' => 'Cambio de Estado a: ' . $ticket->estado->nombre, 'ticket_id' => $ticket->id, 'leido' => 1 ]);
        }
        if($ticket->isDirty('user_encargado_id')) {
            //Mail::to([$ticket->encargado->email])->send(new CambioEncargado($ticket));
            EmailHelper::enviarNotificacion(
                [$ticket->encargado->email],
                new CambioEncargado($ticket),
                'Cambio de encargado del ticket #' . $ticket->codigo
            );
        }
        if($ticket->estado_id == 2) {
            $ticket->finalizado = Carbon::now();
        }
        $ticket->save();
        return redirect()->route('tickets.tickets.show', $ticket);
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
