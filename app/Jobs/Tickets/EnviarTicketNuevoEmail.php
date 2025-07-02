<?php

namespace App\Jobs\Tickets;

use App\Mail\Tickets\Notificacion\TicketNuevo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarTicketNuevoEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;
    protected $destinatarios;

    /**
     * Create a new job instance.
     */
    public function __construct($ticket, array $destinatarios)
    {
        //
        $this->ticket = $ticket;
        $this->destinatarios = $destinatarios;

        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->destinatarios)->send(new TicketNuevo($this->ticket));
        sleep(3);
    }
}
