<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEcoleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ecole;
    public $username;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($ecole, $username, $password)
    {
        $this->ecole = $ecole;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue sur STAGILOG - TFG SARL')
                    ->view('emails.welcome-ecole');
    }
}
