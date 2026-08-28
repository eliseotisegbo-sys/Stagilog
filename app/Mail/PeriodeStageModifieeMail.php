<?php

namespace App\Mail;

use App\Models\Dossier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PeriodeStageModifieeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dossier;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Dossier $dossier, string $adminName = 'L\'Administration')
    {
        $this->dossier = $dossier;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $code = $this->dossier->code_dossier ?? ($this->dossier->ecole->sigle ?? 'STG') . '-' . ($this->dossier->created_at ? $this->dossier->created_at->format('dmYHi') : '');
        return new Envelope(
            subject: 'Modification de la Période de Stage (Sous Réserve) - Dossier ' . $code . ' - TFG SARL',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.periode-stage-modifiee',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
