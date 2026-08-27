<?php

namespace App\Mail;

use App\Models\Dossier;
use App\Models\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EtudiantStageValideMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dossier;
    public $etudiant;

    /**
     * Create a new message instance.
     */
    public function __construct(Dossier $dossier, Etudiant $etudiant)
    {
        $this->dossier = $dossier;
        $this->etudiant = $etudiant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Félicitations ! Votre stage chez Technology Forever Group SARL est validé',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.etudiant-stage-valide',
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
