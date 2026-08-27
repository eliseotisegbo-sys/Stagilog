<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use App\Models\User;
use App\Models\ConnexionHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\CredentialsUpdatedMail;

class ParametreController extends Controller
{
    /**
     * Profil & Gestion Multi-Admins + Historique des Connexions
     */
    public function adminIndex()
    {
        $user = auth()->user();
        
        // Liste de tous les comptes Administrateurs TFG SARL
        $admins = User::where('role', 'admin')->latest()->get();

        // Historique complet des connexions / déconnexions
        $connexions = ConnexionHistorique::latest()->paginate(25);

        return view('admin.parametres.index', compact('user', 'admins', 'connexions'));
    }

    /**
     * Mise à jour du Profil Admin connecté
     */
    public function adminUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Upload Photo de Profil Admin
        if ($request->hasFile('photo_profil')) {
            $avatarDir = public_path('uploads/avatars');
            if (!File::isDirectory($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true, true);
            }

            $photoName = 'avatar_' . $user->id . '_' . time() . '.' . $request->file('photo_profil')->getClientOriginalExtension();
            $request->file('photo_profil')->move($avatarDir, $photoName);
            $user->photo_profil = $photoName;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        session(['user_session_name' => $user->name]);

        return back()->with('success', 'Votre profil administrateur a été mis à jour avec succès.');
    }

    /**
     * Créer un nouvel administrateur TFG SARL (Multi-comptes admin)
     */
    public function storeAdminUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ], [
            'name.required' => 'Le nom complet de l\'administrateur est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà attribuée à un compte.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $newAdmin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'first_login' => false,
        ]);

        if ($request->hasFile('photo_profil')) {
            $avatarDir = public_path('uploads/avatars');
            if (!File::isDirectory($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true, true);
            }

            $photoName = 'avatar_' . $newAdmin->id . '_' . time() . '.' . $request->file('photo_profil')->getClientOriginalExtension();
            $request->file('photo_profil')->move($avatarDir, $photoName);
            $newAdmin->photo_profil = $photoName;
            $newAdmin->save();
        }

        try {
            Mail::to($newAdmin->email)->send(new CredentialsUpdatedMail($newAdmin, $request->password));
        } catch (\Exception $e) {
            \Log::warning("Erreur envoi email création compte admin : " . $e->getMessage());
        }

        return back()->with('success', "Le compte administrateur pour {$newAdmin->name} a été créé avec succès et un email lui a été envoyé.");
    }

    /**
     * Supprimer un compte administrateur
     */
    public function destroyAdminUser($id)
    {
        $current = auth()->user();
        if ($current->id == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte actif.');
        }

        $adminToDelete = User::where('role', 'admin')->findOrFail($id);
        $name = $adminToDelete->name;
        $adminToDelete->delete();

        return back()->with('success', "Le compte administrateur de {$name} a été supprimé.");
    }

    /**
     * Profil Espace École
     */
    public function ecoleIndex()
    {
        $user = auth()->user();
        $ecole = $user->ecole;

        $connexions = ConnexionHistorique::where('id_user', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->take(15)
            ->get();

        return view('ecole.parametres.index', compact('user', 'ecole', 'connexions'));
    }

    /**
     * Mise à jour Profil & Informations École
     */
    public function ecoleUpdate(Request $request)
    {
        $user = auth()->user();
        $ecole = $user->ecole;

        $request->validate([
            'nom_responsable' => 'required|string|max:255',
            'nom_ecole' => 'required|string|max:255',
            'sigle' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse_ecole' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:4096',
        ]);

        // Upload Logo École
        if ($request->hasFile('logo') && $ecole) {
            $logoDir = public_path('uploads/logos');
            if (!File::isDirectory($logoDir)) {
                File::makeDirectory($logoDir, 0755, true, true);
            }

            $logoName = 'logo_' . $ecole->id_ecole . '_' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($logoDir, $logoName);
            $ecole->logo = $logoName;
        }

        if ($ecole) {
            $ecole->nom_ecole = $request->nom_ecole;
            $ecole->sigle = strtoupper($request->sigle);
            $ecole->email = $request->email;
            $ecole->mail = $request->email;
            $ecole->telephone = $request->telephone;
            $ecole->adresse_ecole = $request->adresse_ecole;
            $ecole->save();
        }

        $user->name = $request->nom_responsable;
        $user->save();
        session(['user_session_name' => $request->nom_responsable]);

        return back()->with('success', 'Les coordonnées de votre établissement et de votre profil ont été mises à jour.');
    }

    /**
     * Enregistrer le nom de la personne connectée pour cette session (+ logo si 1ere connexion)
     */
    public function setSessionUser(Request $request)
    {
        $request->validate([
            'nom_connecte' => 'required|string|max:255',
            'logo_ecole' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:4096',
        ]);

        $user = auth()->user();
        $nom = trim($request->nom_connecte);
        session(['user_session_name' => $nom]);
        session(['session_identified' => true]);

        $user->name = $nom;
        $user->save();

        if ($request->hasFile('logo_ecole') && $user->ecole) {
            $logoDir = public_path('uploads/logos');
            if (!File::isDirectory($logoDir)) {
                File::makeDirectory($logoDir, 0755, true, true);
            }

            $logoName = 'logo_' . $user->ecole->id_ecole . '_' . time() . '.' . $request->file('logo_ecole')->getClientOriginalExtension();
            $request->file('logo_ecole')->move($logoDir, $logoName);
            $user->ecole->logo = $logoName;
            $user->ecole->save();
        }

        return redirect()->route('dashboard.ecole')->with('success', "Bienvenue, {$nom} !");
    }

    /**
     * Modification du mot de passe par l'école avec ancien mot de passe
     */
    public function ecoleUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'L\'ancien mot de passe est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit comporter au moins 6 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'L\'ancien mot de passe saisi est incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Votre mot de passe a été mis à jour avec succès !');
    }

    /**
     * Envoi du code OTP pour réinitialisation de mot de passe (Mot de passe oublié)
     */
    public function ecoleSendResetOtp(Request $request)
    {
        $user = auth()->user();
        $email = $request->email ?? $user->email;

        // Génération d'un code OTP à 6 chiffres
        $otp = random_int(100000, 999999);

        session([
            'ecole_reset_otp' => (string)$otp,
            'ecole_reset_otp_expires_at' => now()->addMinutes(15),
            'ecole_reset_email' => $email,
        ]);

        try {
            Mail::to($email)->send(new \App\Mail\VerificationCodeMail($otp, $user->name));
            return response()->json([
                'success' => true,
                'message' => "Un code de sécurité à 6 chiffres a été envoyé à l'adresse {$email}."
            ]);
        } catch (\Exception $e) {
            \Log::error("Erreur envoi OTP réinitialisation mot de passe école: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Impossible d'envoyer l'email. Veuillez réessayer ou contacter le support."
            ], 500);
        }
    }

    /**
     * Vérification du code OTP et enregistrement du nouveau mot de passe
     */
    public function ecoleVerifyResetOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'otp_code.required' => 'Le code de vérification à 6 chiffres est obligatoire.',
            'otp_code.size' => 'Le code de vérification doit comporter exactement 6 chiffres.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit comporter au moins 6 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $sessionOtp = session('ecole_reset_otp');
        $expiresAt = session('ecole_reset_otp_expires_at');

        if (!$sessionOtp || !$expiresAt || now()->isAfter($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de vérification a expiré. Veuillez demander un nouveau code.'
            ], 422);
        }

        if (trim($request->otp_code) !== trim($sessionOtp)) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de vérification à 6 chiffres saisi est incorrect.'
            ], 422);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Nettoyage session
        session()->forget(['ecole_reset_otp', 'ecole_reset_otp_expires_at', 'ecole_reset_email']);

        return response()->json([
            'success' => true,
            'message' => 'Votre mot de passe a été réinitialisé avec succès !'
        ]);
    }
}
