<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\PasswordResetCodeMail;

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
     * Traiter l'envoi du code de réinitialisation (6 chiffres)
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Générer un code à 6 chiffres
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Stocker le code en session pour validation
        $request->session()->put('password_reset', [
            'email' => $request->email,
            'code' => $code,
            'user_id' => $user->id,
            'created_at' => now()->toDateTimeString(),
        ]);

        // Également stocker dans le cache (15 minutes)
        Cache::put("password_reset_{$request->email}", [
            'code' => $code,
            'user_id' => $user->id,
            'email' => $request->email,
        ], now()->addMinutes(15));

        // Envoyer l'email avec le code
        try {
            Mail::to($request->email)->send(new PasswordResetCodeMail($code, $user));
            Log::info("Code de récupération envoyé à {$request->email} : {$code}");
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi du code de récupération : " . $e->getMessage());
            return back()->with('error', 'Impossible d\'envoyer l\'email. Veuillez contacter l\'administrateur.');
        }

        return redirect()->route('password.verify-code')->with('success', 'Un code de récupération à 6 chiffres vous a été envoyé par email.');
    }

    /**
     * Afficher le formulaire de saisie du code à 6 chiffres
     */
    public function showVerifyCodeForm()
    {
        if (!session()->has('password_reset')) {
            return redirect()->route('password.request')->with('error', 'Veuillez d\'abord demander un code de récupération.');
        }

        return view('auth.verify-reset-code');
    }

    /**
     * Vérifier le code à 6 chiffres
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Le code de récupération est requis.',
            'code.digits' => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        $resetData = session('password_reset');
        
        if (!$resetData) {
            return back()->with('error', 'Session expirée. Veuillez redemander un code.');
        }

        // Normaliser les codes
        $submittedCode = preg_replace('/\s+/', '', trim($request->code));
        $expectedCode = preg_replace('/\s+/', '', trim($resetData['code']));

        if ($submittedCode !== $expectedCode) {
            Log::warning("Échec vérification code récupération pour {$resetData['email']} — Saisi: '{$submittedCode}', Attendu: '{$expectedCode}'");
            return back()->with('error', 'Code incorrect. Veuillez vérifier le code reçu par email.');
        }

        // Code valide - générer un token de réinitialisation
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $resetData['email']],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Nettoyer la session
        $request->session()->forget('password_reset');
        Cache::forget("password_reset_{$resetData['email']}");

        // Rediriger vers le formulaire de nouveau mot de passe
        return redirect()->route('password.reset', ['token' => $token, 'email' => $resetData['email']])
            ->with('success', 'Code vérifié! Choisissez maintenant votre nouveau mot de passe.');
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
