<?php

namespace App\Mail;

use App\Models\Dossier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DossierRefuseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dossier;
    public $motif;

    /**
     * Create a new message instance.
     */
    public function __construct(Dossier $dossier, $motif)
    {
        $this->dossier = $dossier;
        $this->motif = $motif;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $code = $this->dossier->code_dossier ?? ($this->dossier->ecole->sigle ?? 'STG') . '-' . ($this->dossier->created_at ? $this->dossier->created_at->format('dmYHi') : '');
        return new Envelope(
            subject: 'Décision Dossier de Stage ' . $code . ' - TFG SARL',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dossier-refuse',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
