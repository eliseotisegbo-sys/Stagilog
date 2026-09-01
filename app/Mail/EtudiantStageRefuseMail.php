<?php

namespace App\Mail;

use App\Models\Dossier;
use App\Models\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EtudiantStageRefuseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dossier;
    public $etudiant;
    public $motifRefus;

    /**
     * Create a new message instance.
     */
    public function __construct(Dossier $dossier, Etudiant $etudiant, ?string $motifRefus = null)
    {
        $this->dossier = $dossier;
        $this->etudiant = $etudiant;
        $this->motifRefus = $motifRefus ?? $etudiant->motif_refus ?? 'Capacité d\'accueil atteinte ou profil non retenu pour cette session.';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $code = $this->dossier->code_dossier ?? ($this->dossier->ecole->sigle ?? 'STG') . '-' . $this->dossier->id_dossier;

        return $this->subject("Information relative à votre candidature de stage - Dossier {$code}")
                    ->view('emails.etudiant-stage-refuse');
    }
}
