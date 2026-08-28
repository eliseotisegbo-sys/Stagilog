<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ConnexionHistorique;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion école
     */
    public function showEcoleLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.ecole');
        }
        return view('auth.login-ecole');
    }
    
    /**
     * Afficher le formulaire de connexion admin
     */
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.admin');
        }
        return view('auth.login-admin');
    }
    
    /**
     * Traiter la connexion école (Étape 1 : Identifiants + Génération OTP 6 chiffres)
     */
    public function loginEcole(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role', 'ecole')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            ConnexionHistorique::logConnexion(null, $request, 'echec');
            return back()->withErrors([
                'email' => 'Identifiants incorrects ou compte école introuvable.',
            ])->onlyInput('email');
        }

        return $this->initiate2FA($request, $user);
    }
    
    /**
     * Traiter la connexion admin (Étape 1 : Identifiants + Génération OTP 6 chiffres)
     */
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            ConnexionHistorique::logConnexion(null, $request, 'echec');
            return back()->withErrors([
                'email' => 'Identifiants administrateur incorrects.',
            ])->onlyInput('email');
        }

        return $this->initiate2FA($request, $user);
    }

    /**
     * Initialiser le flux de vérification 2FA à 6 chiffres
     */
    protected function initiate2FA(Request $request, User $user)
    {
        // Génération code aléatoire 6 chiffres
        $code = strval(random_int(100000, 999999));

        $data = [
            'user_id' => $user->id,
            'code' => $code,
            'role' => $user->role,
            'email' => $user->email,
            'name' => $user->name,
            'expires_at' => now()->addMinutes(15)->timestamp,
        ];

        // Sauvegarder en session et en cache pour éviter toute perte
        $request->session()->put('2fa_auth', $data);
        \Illuminate\Support\Facades\Cache::put('2fa_user_' . $user->id, $code, now()->addMinutes(15));
        \Illuminate\Support\Facades\Cache::put('2fa_email_' . $user->email, $code, now()->addMinutes(15));

        // Envoi réel du code par email via stagilogtfg@gmail.com
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user));
            Log::info("Code 2FA envoyé avec succès à {$user->email} (Code: {$code})");
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi du code 2FA à {$user->email} : " . $e->getMessage());
        }

        // Journaliser la tentative
        ConnexionHistorique::logConnexion($user, $request, 'otp_en_attente');

        return redirect()->route('login.verify-code')
            ->with('info', "Un code de sécurité à 6 chiffres a été envoyé à l'adresse : {$user->email}");
    }

    /**
     * Afficher le formulaire visuel de saisie du code à 6 chiffres
     */
    public function showVerifyCodeForm(Request $request)
    {
        $twoFa = $request->session()->get('2fa_auth');
        if (!$twoFa) {
            return redirect()->route('welcome')->with('error', 'Session de connexion expirée. Veuillez vous reconnecter.');
        }

        return view('auth.verify-code', [
            'email' => $twoFa['email'],
            'name' => $twoFa['name'],
            'role' => $twoFa['role'],
        ]);
    }

    /**
     * Valider le code à 6 chiffres (Étape 2)
     */
    public function verifyCode(Request $request)
    {
        $twoFa = $request->session()->get('2fa_auth');

        if (!$twoFa) {
            return redirect()->route('welcome')->with('error', 'Session expirée. Veuillez vous réidentifier.');
        }

        // Extraire tous les chiffres du code soumis (soit full_code, soit code, soit la concaténation de digit_1 à digit_6)
        $submittedRaw = $request->input('full_code') 
            ?? $request->input('code')
            ?? ($request->input('digit_1', '') . $request->input('digit_2', '') . $request->input('digit_3', '') . $request->input('digit_4', '') . $request->input('digit_5', '') . $request->input('digit_6', ''));

        $submittedCode = preg_replace('/\D/', '', trim((string)$submittedRaw));

        if (strlen($submittedCode) !== 6) {
            return back()->with('error', 'Veuillez renseigner l\'intégralité des 6 chiffres du code reçu par email.');
        }

        if (now()->timestamp > $twoFa['expires_at']) {
            $request->session()->forget('2fa_auth');
            \Illuminate\Support\Facades\Cache::forget('2fa_user_' . $twoFa['user_id']);
            \Illuminate\Support\Facades\Cache::forget('2fa_email_' . $twoFa['email']);
            return redirect()->route($twoFa['role'] === 'admin' ? 'login.admin' : 'login.ecole')
                ->with('error', 'Le code de vérification a expiré. Veuillez vous reconnecter pour en recevoir un nouveau.');
        }

        // Normaliser et comparer les codes en string pur
        $expectedCode = trim(str_replace([' ', '-', '_'], '', (string)$twoFa['code']));
        $submittedCodeNormalized = trim(str_replace([' ', '-', '_'], '', $submittedCode));

        // Log pour debug (à retirer en production)
        Log::info("Tentative 2FA - Email: {$twoFa['email']}, Code soumis: '{$submittedCodeNormalized}', Code attendu: '{$expectedCode}'");

        // Vérification stricte du code de la session
        if ($submittedCodeNormalized !== $expectedCode) {
            $user = User::find($twoFa['user_id']);
            ConnexionHistorique::logConnexion($user, $request, 'echec_otp');
            Log::warning("Échec 2FA pour {$twoFa['email']} — Code saisi : '{$submittedCodeNormalized}', Code attendu : '{$expectedCode}'");
            return back()->with('error', 'Code de vérification incorrect. Veuillez vérifier le code à 6 chiffres reçu par email.');
        }

        // Authentification réussie
        $user = User::findOrFail($twoFa['user_id']);
        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('2fa_auth');
        \Illuminate\Support\Facades\Cache::forget('2fa_user_' . $user->id);
        \Illuminate\Support\Facades\Cache::forget('2fa_email_' . $user->email);

        // Initialiser le nom de session pour les écoles
        session(['user_session_name' => $user->name]);

        // Enregistrer la connexion réussie avec le nom
        ConnexionHistorique::logConnexion($user, $request, 'succes', $user->name);

        if ($user->isAdmin()) {
            if ($user->first_login) {
                return redirect()->route('first-time-setup');
            }
            return redirect()->intended(route('dashboard.admin'));
        }

        return redirect()->intended(route('dashboard.ecole'));
    }

    /**
     * Renvoyer un nouveau code de sécurité
     */
    public function resendCode(Request $request)
    {
        $twoFa = $request->session()->get('2fa_auth');
        if (!$twoFa) {
            return redirect()->route('welcome')->with('error', 'Session expirée.');
        }

        $user = User::find($twoFa['user_id']);
        if (!$user) {
            return redirect()->route('welcome');
        }

        $code = strval(random_int(100000, 999999));
        $twoFa['code'] = $code;
        $twoFa['expires_at'] = now()->addMinutes(15)->timestamp;
        $request->session()->put('2fa_auth', $twoFa);
        \Illuminate\Support\Facades\Cache::put('2fa_user_' . $user->id, $code, now()->addMinutes(15));
        \Illuminate\Support\Facades\Cache::put('2fa_email_' . $user->email, $code, now()->addMinutes(15));

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user));
            Log::info("Nouveau code 2FA renvoyé à {$user->email} (Code: {$code})");
        } catch (\Exception $e) {
            Log::error("Erreur renvoi code 2FA : " . $e->getMessage());
        }

        return back()->with('success', 'Un nouveau code à 6 chiffres a été envoyé sur votre adresse email.');
    }

    /**
     * Déconnexion sécurisée
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ConnexionHistorique::logDeconnexion($user, $request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome')->with('info', 'Vous avez été déconnecté avec succès.');
    }
}
