<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\User;
use App\Mail\WelcomeEcoleMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\CredentialsUpdatedMail;

class EcoleController extends Controller
{
    /**
     * Liste des écoles
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $ecoles = Ecole::with(['users'])
            ->withCount(['dossiers', 'users'])
            ->when($search, function($query, $search) {
                $query->where('nom_ecole', 'like', "%{$search}%")
                      ->orWhere('mail', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telephone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.ecoles.index', compact('ecoles', 'search'));
    }

    /**
     * Formulaire de création (renseignements école seuls)
     */
    public function create()
    {
        return view('admin.ecoles.create');
    }

    /**
     * Enregistrer une nouvelle école (sans forcer la création de compte)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_ecole' => 'required|string|max:255|unique:ecoles,nom_ecole',
            'sigle' => 'required|string|max:50',
            'adresse_ecole' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:ecoles,email',
        ], [
            'nom_ecole.unique' => 'Un établissement avec ce nom existe déjà.',
            'sigle.required' => 'Le sigle de létablissement est obligatoire (ex: UCAD, ESP, ISM).',
            'email.unique' => 'Cette adresse email est déjà enregistrée pour une autre école.',
        ]);

        $ecole = Ecole::create([
            'nom_ecole' => $request->nom_ecole,
            'sigle' => strtoupper($request->sigle),
            'adresse_ecole' => $request->adresse_ecole,
            'num_ecole' => $request->telephone,
            'telephone' => $request->telephone,
            'mail' => $request->email,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.ecoles.index')->with('success', "L'établissement {$ecole->nom_ecole} ({$ecole->sigle}) a été enregistré avec succès. Vous pouvez maintenant créer son compte d'accès depuis le tableau.");
    }

    /**
     * Créer le compte utilisateur d'accès pour une école avec validation préalable avant envoi d'email
     */
    public function creerCompte(Request $request, $id)
    {
        $ecole = Ecole::findOrFail($id);

        $existingUser = User::where('id_ecole', $id)->orWhere('email', $ecole->email)->first();
        if ($existingUser) {
            return back()->with('error', "Un compte utilisateur existe déjà pour cette école ({$existingUser->email}).");
        }

        // Mot de passe personnalisé ou génération automatique
        $password = $request->input('password');
        if (empty($password)) {
            $password = 'Tfg@' . strtoupper(Str::random(4)) . rand(100, 999);
        }

        $user = User::create([
            'name' => $ecole->nom_ecole,
            'email' => $ecole->email,
            'password' => Hash::make($password),
            'role' => 'ecole',
            'id_ecole' => $ecole->id_ecole,
            'first_login' => false,
        ]);

        // Envoi d'email conditionnel (validé par l'admin)
        $emailEnvoye = false;
        if ($request->boolean('envoyer_email', true)) {
            try {
                Mail::to($ecole->email)->send(new WelcomeEcoleMail($ecole, [
                    'email' => $user->email,
                    'password' => $password,
                ]));
                $emailEnvoye = true;
            } catch (\Exception $e) {
                \Log::warning("Erreur envoi email bienvenue école : " . $e->getMessage());
            }
        }

        // Notification système
        \App\Models\AppNotification::notifier(
            'ecole',
            'Compte d\'Accès Activé',
            "Votre compte d'accès pour {$ecole->nom_ecole} a été configuré et activé avec succès.",
            route('dashboard.ecole'),
            'compte_cree',
            $ecole->id_ecole
        );

        $emailMsg = $emailEnvoye ? " et les identifiants ont été envoyés à {$ecole->email}" : " (sans envoi d'email automatique).";

        return redirect()->route('admin.ecoles.index')
            ->with('compte_cree', [
                'ecole' => $ecole->nom_ecole,
                'email' => $user->email,
                'password' => $password,
                'email_envoye' => $emailEnvoye,
            ])
            ->with('success', "Compte créé et validé avec succès pour {$ecole->nom_ecole}{$emailMsg} !");
    }

    /**
     * Ajouter un nouvel utilisateur d'accès pour une école (Multi-utilisateurs par école)
     */
    public function ajouterUtilisateur(Request $request, $id)
    {
        $ecole = Ecole::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'envoyer_email' => 'nullable|boolean',
        ], [
            'name.required' => 'Le nom du responsable ou de l\'utilisateur est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre compte.',
        ]);

        $password = $request->input('password');
        if (empty($password)) {
            $password = 'Tfg@' . strtoupper(Str::random(4)) . rand(100, 999);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'ecole',
            'id_ecole' => $ecole->id_ecole,
            'first_login' => false,
        ]);

        $emailEnvoye = false;
        if ($request->boolean('envoyer_email', true)) {
            try {
                Mail::to($user->email)->send(new WelcomeEcoleMail($ecole, [
                    'email' => $user->email,
                    'password' => $password,
                ]));
                $emailEnvoye = true;
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email ajout utilisateur école : " . $e->getMessage());
            }
        }

        return redirect()->route('admin.ecoles.index')
            ->with('compte_cree', [
                'ecole' => $ecole->nom_ecole . ' (' . $user->name . ')',
                'email' => $user->email,
                'password' => $password,
                'email_envoye' => $emailEnvoye,
            ])
            ->with('success', "Utilisateur {$user->name} ajouté avec succès pour {$ecole->nom_ecole} !");
    }

    /**
     * Supprimer un utilisateur d'une école
     */
    public function supprimerUtilisateur($id)
    {
        $user = User::where('role', 'ecole')->findOrFail($id);
        $ecoleName = $user->ecole->nom_ecole ?? 'l\'établissement';
        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.ecoles.index')->with('success', "Le compte utilisateur de {$userName} ({$ecoleName}) a été supprimé.");
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur école par l'administrateur
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.min' => 'Le mot de passe doit comporter au moins 6 caractères.',
        ]);

        // Vérifier si l'id correspond à un User directement ou à une École
        $user = User::find($id);
        if (!$user) {
            $user = User::where('id_ecole', $id)->first();
        }

        if (!$user) {
            $ecole = Ecole::findOrFail($id);
            $user = User::create([
                'name' => $ecole->nom_ecole,
                'email' => $ecole->email,
                'password' => Hash::make($request->password),
                'role' => 'ecole',
                'id_ecole' => $ecole->id_ecole,
                'first_login' => false,
            ]);
        } else {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $ecoleName = $user->ecole->nom_ecole ?? 'l\'établissement';

        // Envoi d'email pour informer de la mise à jour des coordonnées
        try {
            Mail::to($user->email)->send(new CredentialsUpdatedMail($user, $request->password));
        } catch (\Exception $e) {
            \Log::warning("Erreur envoi email mise à jour mot de passe : " . $e->getMessage());
        }

        return redirect()->route('admin.ecoles.index')->with('success', "Le mot de passe pour {$user->name} ({$ecoleName}) a été mis à jour avec succès et un email contenant les nouveaux identifiants a été envoyé.");
    }

    /**
     * Formulaire d'édition des infos école
     */
    public function edit($id)
    {
        $ecole = Ecole::with('users')->findOrFail($id);
        $user = $ecole->users->first();

        return view('admin.ecoles.edit', compact('ecole', 'user'));
    }

    /**
     * Mettre à jour une école
     */
    public function update(Request $request, $id)
    {
        $ecole = Ecole::findOrFail($id);

        $request->validate([
            'nom_ecole' => 'required|string|max:255|unique:ecoles,nom_ecole,' . $id . ',id_ecole',
            'adresse_ecole' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:ecoles,email,' . $id . ',id_ecole',
        ]);

        $ecole->update([
            'nom_ecole' => $request->nom_ecole,
            'adresse_ecole' => $request->adresse_ecole,
            'num_ecole' => $request->telephone,
            'telephone' => $request->telephone,
            'mail' => $request->email,
            'email' => $request->email,
        ]);

        // Mise à jour de l'email du compte utilisateur associé si existant
        $user = User::where('id_ecole', $id)->first();
        if ($user && $user->email !== $request->email) {
            $user->email = $request->email;
            $user->name = $request->nom_ecole;
            $user->save();
        }

        return redirect()->route('admin.ecoles.index')->with('success', "Les informations de l'école ont été mises à jour.");
    }

    /**
     * Supprimer une école
     */
    public function destroy($id)
    {
        $ecole = Ecole::withCount('dossiers')->findOrFail($id);

        if ($ecole->dossiers_count > 0) {
            return back()->with('error', "Impossible de supprimer cette école car {$ecole->dossiers_count} dossier(s) de stage y sont rattachés.");
        }

        // Supprimer les users associés
        User::where('id_ecole', $id)->delete();
        $ecole->delete();

        return redirect()->route('admin.ecoles.index')->with('success', "L'école a été supprimée avec succès.");
    }
}
