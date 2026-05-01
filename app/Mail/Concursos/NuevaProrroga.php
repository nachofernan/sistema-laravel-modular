<?php

namespace App\Mail\Concursos;

use App\Mail\Concursos\Traits\ConcursoMailableTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaProrroga extends Mailable
{
    use Queueable, SerializesModels, ConcursoMailableTrait;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'BAESA - Notificación de Prórroga',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.concursos.nueva-prorroga',
            with: array_merge($this->viewData(), [
                'prorroga' => $this->entidad,
                'concurso' => $this->entidad->concurso
            ])
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
