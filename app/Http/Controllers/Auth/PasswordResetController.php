<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Afficher le formulaire de demande de réinitialisation
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Traiter l'envoi du lien de réinitialisation
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Envoyer ou logger l'email
        // Pour démo et tests, on génère l'URL
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Simuler l'envoi / journaliser
        \Log::info("Lien de réinitialisation de mot de passe pour {$request->email} : {$resetUrl}");

        return back()->with('status', 'Un lien de réinitialisation vous a été envoyé. Si vous êtes en mode test, le lien a été enregistré.');
    }

    /**
     * Afficher le formulaire de nouveau mot de passe
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Valider et mettre à jour le mot de passe
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Ce jeton de réinitialisation est invalide ou a expiré.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login.ecole')->with('success', 'Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
    }
}
