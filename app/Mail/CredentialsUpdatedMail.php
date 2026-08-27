<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CredentialsUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $urlConnexion;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->urlConnexion = url($user->role === 'admin' ? '/auth/admin/login' : '/auth/ecole/login');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Vos identifiants STAGILOG ont été mis à jour — TFG SARL')
                    ->view('emails.credentials-updated');
    }
}
