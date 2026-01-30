<?php

namespace App\Mail\Concursos;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\ConcursoDocumento;
use App\Models\Concursos\OfertaDocumento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoDocumento extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $documento, public string $destinatario = '')
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'BAESA - Nuevo Documento',
        );
    }

    /**
     * Obtener el link correcto según el destinatario y entorno
     */
    public function getLinkConcurso(): string
    {
        // Verificar si es usuario interno
        $esUsuarioInterno = str_ends_with($this->destinatario, '@buenosairesenergia.com.ar');
        
        if ($esUsuarioInterno) {
            // Link interno según entorno
            $baseUrl = config('app.env') === 'production' 
                ? 'http://172.17.8.80/plataforma'
                : 'http://172.17.9.231/plataforma';
                
            return "{$baseUrl}/concursos/concursos/{$this->documento->concurso_id}";
        }
        
        // Link externo (proveedores)
        return "https://buenosairesenergia.com.ar/registroproveedores/concursos/{$this->documento->concurso_id}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.concursos.nuevo-documento',
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
