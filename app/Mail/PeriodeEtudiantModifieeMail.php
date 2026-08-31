<?php

namespace App\Mail;

use App\Models\Dossier;
use App\Models\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PeriodeEtudiantModifieeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dossier;
    public $etudiant;
    public $adminName;
    public $recipientType; // 'etudiant' ou 'ecole'

    /**
     * Create a new message instance.
     */
    public function __construct(Dossier $dossier, Etudiant $etudiant, string $adminName = 'L\'Administration TFG SARL', string $recipientType = 'etudiant')
    {
        $this->dossier = $dossier;
        $this->etudiant = $etudiant;
        $this->adminName = $adminName;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $code = $this->dossier->code_dossier ?? ($this->dossier->ecole->sigle ?? 'STG') . '-' . ($this->dossier->created_at ? $this->dossier->created_at->format('dmYHi') : '');
        $etuName = $this->etudiant->nom_etudiant . ' ' . $this->etudiant->prenom_etudiant;
        
        $subject = ($this->recipientType === 'etudiant')
            ? "Mise à jour de votre période de stage ({$code}) - TFG SARL"
            : "Ajustement de période de stage pour {$etuName} ({$code}) - TFG SARL";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.periode-etudiant-modifiee',
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
