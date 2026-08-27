<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEcoleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ecole;
    public $credentials;
    public $urlConnexion;

    /**
     * Create a new message instance.
     */
    public function __construct($ecole, array $credentials)
    {
        $this->ecole = $ecole;
        $this->credentials = $credentials;
        $this->urlConnexion = url('/auth/ecole/login');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue sur STAGILOG — Vos identifiants de connexion — TFG SARL')
                    ->view('emails.welcome-ecole');
    }
}
