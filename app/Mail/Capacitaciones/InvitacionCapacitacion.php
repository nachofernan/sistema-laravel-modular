<?php

namespace App\Mail\Capacitaciones;

use App\Models\Capacitaciones\Invitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitacionCapacitacion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitacion $invitacion) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a capacitación: ' . $this->invitacion->capacitacion->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.capacitaciones.invitacion',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
