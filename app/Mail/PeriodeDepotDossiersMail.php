<?php

namespace App\Mail;

use App\Models\Ecole;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PeriodeDepotDossiersMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ecole;
    public $dateDebut;
    public $dateFin;
    public $messagePersonnalise;

    /**
     * Create a new message instance.
     */
    public function __construct(Ecole $ecole, string $dateDebut, string $dateFin, ?string $messagePersonnalise = null)
    {
        $this->ecole = $ecole;
        $this->dateDebut = Carbon::parse($dateDebut)->locale('fr')->isoFormat('D MMMM YYYY');
        $this->dateFin = Carbon::parse($dateFin)->locale('fr')->isoFormat('D MMMM YYYY');
        $this->messagePersonnalise = $messagePersonnalise;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Ouverture de la Session de Dépôt des Dossiers de Stage - TFG SARL')
                    ->view('emails.periode-depot-dossiers');
    }
}
