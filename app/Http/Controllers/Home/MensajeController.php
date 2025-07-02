<?php

namespace App\Http\Controllers\Home;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\Tickets\Notificacion\MensajeUsuario;
use App\Models\Tickets\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MensajeController extends Controller
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
        $mensaje = Mensaje::create($request->all());
        $mensaje->update([
            'user_id' => Auth::user()->id,
        ]);
        if($mensaje->ticket->encargado) {
            //Mail::to([$mensaje->ticket->encargado->email])->send(new MensajeUsuario($mensaje));
            EmailHelper::enviarNotificacion(
                [$mensaje->ticket->encargado->email],
                new MensajeUsuario($mensaje),
                'Nuevo mensaje en el ticket #' . $mensaje->ticket->codigo
            );
        }

        return redirect()->route('home.tickets.show', $mensaje->ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mensaje $mensaje)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mensaje $mensaje)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mensaje $mensaje)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mensaje $mensaje)
    {
        //
    }
}
