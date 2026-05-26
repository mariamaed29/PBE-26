<?php

namespace App\Mail;

use App\Models\Notificacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OcorrenciaProfessorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notificacao $notificacao)
    {
        $this->notificacao->loadMissing('ocorrencia.aluno');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notificacao->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ocorrencias.professor',
        );
    }
}
