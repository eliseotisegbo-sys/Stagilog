<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use App\Models\User;
use App\Models\ConnexionHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ParametreController extends Controller
{
    /**
     * Paramètres Espace Admin
     */
    public function adminIndex()
    {
        $user = auth()->user();
        $connexions = ConnexionHistorique::where('id_user', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.parametres.index', compact('user', 'connexions'));
    }

    /**
     * Mise à jour Paramètres Admin
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

        return back()->with('success', 'Vos paramètres administrateur et photo de profil ont été enregistrés avec succès.');
    }

    /**
     * Paramètres Espace École
     */
    public function ecoleIndex()
    {
        $user = auth()->user();
        $ecole = $user->ecole;

        $connexions = ConnexionHistorique::where('id_user', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->take(10)
            ->get();

        return view('ecole.parametres.index', compact('user', 'ecole', 'connexions'));
    }

    /**
     * Mise à jour Paramètres École
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
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Upload Logo École
        if ($request->hasFile('logo')) {
            $logoDir = public_path('uploads/logos');
            if (!File::isDirectory($logoDir)) {
                File::makeDirectory($logoDir, 0755, true, true);
            }

            $logoName = 'logo_' . $ecole->id_ecole . '_' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($logoDir, $logoName);
            $ecole->logo = $logoName;
        }

        $ecole->nom_ecole = $request->nom_ecole;
        $ecole->sigle = strtoupper($request->sigle);
        $ecole->email = $request->email;
        $ecole->mail = $request->email;
        $ecole->telephone = $request->telephone;
        $ecole->adresse_ecole = $request->adresse_ecole;
        $ecole->save();

        $user->name = $request->nom_responsable;
        session(['user_session_name' => $request->nom_responsable]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Les paramètres de votre établissement et de votre profil ont été mis à jour.');
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

        // Mettre à jour le nom de l'utilisateur
        $user->name = $nom;
        $user->save();

        // Si logo fourni lors de la première connexion
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
}
