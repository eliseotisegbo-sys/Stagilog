<?php

namespace App\Http\Controllers\Ecole;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Etudiant;
use App\Models\Cycle;
use App\Models\Filiere;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DossierController extends Controller
{
    /**
     * Liste des dossiers de l'école connectée
     */
    public function index(Request $request)
    {
        $idEcole = auth()->user()->id_ecole;
        $search = $request->query('search');
        $status = $request->query('statut');

        $query = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants']);

        if ($status === 'brouillon') {
            $query->where('statut_brouillon', 'brouillon');
        } elseif ($status && in_array($status, ['en_attente', 'valide', 'refuse', 'sous_reserve'])) {
            $query->where('statut_brouillon', 'soumis')->where('statut', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('filiere', 'like', "%{$search}%")
                  ->orWhere('annee_academique', 'like', "%{$search}%")
                  ->orWhere('type_stage', 'like', "%{$search}%")
                  ->orWhere('code_dossier', 'like', "%{$search}%");
            });
        }

        $dossiers = $query->latest()->paginate(15);

        $countTotal = Dossier::where('id_ecole', $idEcole)->count();
        $countBrouillon = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'brouillon')->count();
        $countAttente = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'soumis')->where('statut', 'en_attente')->count();
        $countSousReserve = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'soumis')->where('statut', 'sous_reserve')->count();
        $countValide = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'soumis')->where('statut', 'valide')->count();
        $countRefuse = Dossier::where('id_ecole', $idEcole)->where('statut_brouillon', 'soumis')->where('statut', 'refuse')->count();

        return view('ecole.dossiers.index', compact(
            'dossiers', 
            'search', 
            'status',
            'countTotal',
            'countBrouillon',
            'countAttente',
            'countSousReserve',
            'countValide',
            'countRefuse'
        ));
    }

    /**
     * Formulaire de création de dossier
     */
    public function create()
    {
        $cycles = Cycle::all();
        $filieres = Filiere::where('actif', true)->get();

        return view('ecole.dossiers.create', compact('cycles', 'filieres'));
    }

    /**
     * Enregistrer un dossier (Brouillon ou Soumission directe)
     */
    public function store(Request $request)
    {
        $idEcole = auth()->user()->id_ecole;
        $ecoleName = auth()->user()->ecole->nom_ecole ?? 'École Partenaire';

        $isSubmit = ($request->input('action') === 'soumettre');
        $statutBrouillon = $isSubmit ? 'soumis' : 'brouillon';
        $statutGeneral = $isSubmit ? 'en_attente' : 'brouillon';

        if ($isSubmit) {
            $request->validate([
                'annee_academique' => 'required|string|max:50',
                'id_filiere' => 'required|exists:filieres,id_filiere',
                'id_cycle' => 'required|exists:cycles,id_cycle',
                'datedebut' => 'required|date',
                'datefin' => 'required|date|after:datedebut',
                'note_demande_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                
                'etudiants' => 'required|array|min:1',
                'etudiants.*.nom' => 'required|string|max:255',
                'etudiants.*.prenom' => 'required|string|max:255',
                'etudiants.*.email' => 'required|email|max:255',
                'etudiants.*.niveau_etude' => 'required|string|max:100',
                'etudiants.*.date_naissance' => 'nullable|date',
                'etudiants.*.datedebut_stage' => 'nullable|date',
                'etudiants.*.datefin_stage' => 'nullable|date',
                'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ], [
                'note_demande_file.required' => 'La note de demande officielle de l\'établissement est obligatoire pour soumettre.',
                'etudiants.min' => 'Vous devez renseigner au moins un étudiant pour soumettre ce dossier.',
                'etudiants.*.nom.required' => 'Le nom de l\'étudiant est obligatoire.',
                'etudiants.*.prenom.required' => 'Le prénom de l\'étudiant est obligatoire.',
                'etudiants.*.email.required' => 'L\'email de l\'étudiant est obligatoire.',
                'etudiants.*.niveau_etude.required' => 'Le niveau d\'étude est obligatoire pour chaque étudiant.',
                'datefin.after' => 'La date de fin doit être postérieure à la date de début.',
            ]);
        } else {
            // Enregistrement en brouillon partiel : validation permissive
            $request->validate([
                'annee_academique' => 'nullable|string|max:50',
                'id_filiere' => 'nullable|exists:filieres,id_filiere',
                'id_cycle' => 'nullable|exists:cycles,id_cycle',
                'datedebut' => 'nullable|date',
                'datefin' => 'nullable|date',
                'note_demande_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'etudiants' => 'nullable|array',
                'etudiants.*.nom' => 'nullable|string|max:255',
                'etudiants.*.prenom' => 'nullable|string|max:255',
                'etudiants.*.email' => 'nullable|email|max:255',
                'etudiants.*.niveau_etude' => 'nullable|string|max:100',
                'etudiants.*.date_naissance' => 'nullable|date',
                'etudiants.*.datedebut_stage' => 'nullable|date',
                'etudiants.*.datefin_stage' => 'nullable|date',
                'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);
        }

        $filiereObj = $request->id_filiere ? Filiere::find($request->id_filiere) : null;

        DB::beginTransaction();
        try {
            // Upload Note de Demande
            $noteName = null;
            if ($request->hasFile('note_demande_file')) {
                $noteName = 'demande_' . $idEcole . '_' . time() . '.' . $request->file('note_demande_file')->getClientOriginalExtension();
                $request->file('note_demande_file')->move(public_path('uploads/notes'), $noteName);
            }

            // Créer le dossier (en préservant les données partielles)
            $dossier = Dossier::create([
                'annee_academique' => $request->annee_academique ?? (date('Y') . '-' . (date('Y') + 1)),
                'filiere' => $filiereObj ? $filiereObj->nom_filiere : ($request->filiere ?? 'Non spécifiée'),
                'sigle' => $request->sigle ?? ($filiereObj ? $filiereObj->sigle : null),
                'id_filiere' => $request->id_filiere,
                'id_cycle' => $request->id_cycle,
                'type_stage' => $request->type_stage ?? 'Stage académique',
                'niveau_etude' => ($request->etudiants && isset($request->etudiants[0]['niveau_etude'])) ? $request->etudiants[0]['niveau_etude'] : 'Non spécifié',
                'note_demande' => $noteName,
                'datedebut' => $request->datedebut,
                'datefin' => $request->datefin,
                'id_ecole' => $idEcole,
                'statut' => $statutGeneral,
                'statut_brouillon' => $statutBrouillon,
            ]);

            // Ajouter les étudiants
            if ($request->etudiants && is_array($request->etudiants)) {
                foreach ($request->etudiants as $index => $etuData) {
                    // Ignorer les lignes totalement vides en brouillon
                    if (!$isSubmit && empty($etuData['nom']) && empty($etuData['prenom']) && empty($etuData['email'])) {
                        continue;
                    }

                    $cvName = null;
                    if ($request->hasFile("etudiants.{$index}.cv_file")) {
                        $cvFile = $request->file("etudiants.{$index}.cv_file");
                        $cvName = 'cv_' . time() . '_' . $index . '.' . $cvFile->getClientOriginalExtension();
                        $cvFile->move(public_path('uploads/cv'), $cvName);
                    }

                    $debutEtu = !empty($etuData['datedebut_stage']) ? $etuData['datedebut_stage'] : $request->datedebut;
                    $finEtu = !empty($etuData['datefin_stage']) ? $etuData['datefin_stage'] : $request->datefin;

                    Etudiant::create([
                        'nom_etudiant' => $etuData['nom'] ?? 'Étudiant',
                        'prenom_etudiant' => $etuData['prenom'] ?? '',
                        'email_etu' => $etuData['email'] ?? null,
                        'niveau_etude' => $etuData['niveau_etude'] ?? 'Non spécifié',
                        'date_naissance' => !empty($etuData['date_naissance']) ? $etuData['date_naissance'] : null,
                        'datedebut_stage' => $debutEtu,
                        'datefin_stage' => $finEtu,
                        'cv' => $cvName,
                        'id_dossier' => $dossier->id_dossier,
                    ]);
                }
            }

            // Si le dossier est soumis directement, notifier l'administrateur
            if ($isSubmit) {
                AppNotification::notifier(
                    'admin',
                    'Nouveau Dossier de Stage Soumis',
                    "L'établissement {$ecoleName} a soumis le dossier #{$dossier->id_dossier} ({$dossier->filiere}) comprenant " . count($request->etudiants ?? []) . " étudiant(s).",
                    route('admin.dossiers.show', $dossier->id_dossier),
                    'dossier_soumis'
                );
            }

            DB::commit();

            $msg = $isSubmit 
                ? "Votre dossier #{$dossier->id_dossier} a été transmis avec succès à TFG SARL pour validation !"
                : "Votre dossier #{$dossier->id_dossier} a été sauvegardé en BROUILLON. Toutes les données saisies ont été conservées.";

            return redirect()->route('ecole.dossiers.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage());
        }
    }

    /**
     * Formulaire de modification d'un brouillon
     */
    public function edit($id)
    {
        $idEcole = auth()->user()->id_ecole;

        $dossier = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants'])
            ->findOrFail($id);

        if ($dossier->statut_brouillon === 'soumis' && $dossier->statut === 'valide') {
            return redirect()->route('ecole.dossiers.show', $id)
                ->with('error', "Ce dossier a déjà été validé par TFG SARL et ne peut plus être modifié.");
        }

        $cycles = Cycle::all();
        $filieres = Filiere::where('actif', true)->get();

        return view('ecole.dossiers.edit', compact('dossier', 'cycles', 'filieres'));
    }

    /**
     * Mettre à jour un dossier brouillon
     */
    public function update(Request $request, $id)
    {
        $idEcole = auth()->user()->id_ecole;
        $ecoleName = auth()->user()->ecole->nom_ecole ?? 'École Partenaire';
        $dossier = Dossier::where('id_ecole', $idEcole)->findOrFail($id);

        $isSubmit = ($request->input('action') === 'soumettre');
        $statutBrouillon = $isSubmit ? 'soumis' : 'brouillon';
        $statutGeneral = $isSubmit ? 'en_attente' : 'brouillon';

        if ($isSubmit) {
            $hasExistingNote = !empty($dossier->note_demande);
            $request->validate([
                'annee_academique' => 'required|string|max:50',
                'id_filiere' => 'required|exists:filieres,id_filiere',
                'id_cycle' => 'required|exists:cycles,id_cycle',
                'type_stage' => 'required|string|max:100',
                'datedebut' => 'required|date',
                'datefin' => 'required|date|after:datedebut',
                'note_demande_file' => ($hasExistingNote ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
                
                'etudiants' => 'required|array|min:1',
                'etudiants.*.nom' => 'required|string|max:255',
                'etudiants.*.prenom' => 'required|string|max:255',
                'etudiants.*.email' => 'required|email|max:255',
                'etudiants.*.niveau_etude' => 'required|string|max:100',
                'etudiants.*.date_naissance' => 'nullable|date',
                'etudiants.*.datedebut_stage' => 'nullable|date',
                'etudiants.*.datefin_stage' => 'nullable|date',
                'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ], [
                'etudiants.min' => 'Vous devez renseigner au moins un étudiant.',
                'etudiants.*.niveau_etude.required' => 'Le niveau d\'étude est obligatoire pour chaque étudiant.',
                'datefin.after' => 'La date de fin doit être postérieure à la date de début.',
            ]);
        } else {
            // Validation permissive pour la mise à jour de brouillon
            $request->validate([
                'annee_academique' => 'nullable|string|max:50',
                'id_filiere' => 'nullable|exists:filieres,id_filiere',
                'id_cycle' => 'nullable|exists:cycles,id_cycle',
                'type_stage' => 'nullable|string|max:100',
                'datedebut' => 'nullable|date',
                'datefin' => 'nullable|date',
                'note_demande_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'etudiants' => 'nullable|array',
                'etudiants.*.nom' => 'nullable|string|max:255',
                'etudiants.*.prenom' => 'nullable|string|max:255',
                'etudiants.*.email' => 'nullable|email|max:255',
                'etudiants.*.niveau_etude' => 'nullable|string|max:100',
                'etudiants.*.date_naissance' => 'nullable|date',
                'etudiants.*.datedebut_stage' => 'nullable|date',
                'etudiants.*.datefin_stage' => 'nullable|date',
                'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);
        }

        $filiereObj = $request->id_filiere ? Filiere::find($request->id_filiere) : null;

        DB::beginTransaction();
        try {
            // Note de demande (conserver l'existante si aucun nouveau fichier n'est téléversé)
            $noteName = $dossier->note_demande;
            if ($request->hasFile('note_demande_file')) {
                $noteName = 'demande_' . $idEcole . '_' . time() . '.' . $request->file('note_demande_file')->getClientOriginalExtension();
                $request->file('note_demande_file')->move(public_path('uploads/notes'), $noteName);
            }

            $dossier->update([
                'annee_academique' => $request->annee_academique ?? $dossier->annee_academique,
                'filiere' => $filiereObj ? $filiereObj->nom_filiere : $dossier->filiere,
                'sigle' => $request->sigle ?? ($filiereObj ? $filiereObj->sigle : $dossier->sigle),
                'id_filiere' => $request->id_filiere ?? $dossier->id_filiere,
                'id_cycle' => $request->id_cycle ?? $dossier->id_cycle,
                'type_stage' => $request->type_stage ?? $dossier->type_stage,
                'niveau_etude' => ($request->etudiants && isset($request->etudiants[0]['niveau_etude'])) ? $request->etudiants[0]['niveau_etude'] : $dossier->niveau_etude,
                'note_demande' => $noteName,
                'datedebut' => $request->datedebut ?? $dossier->datedebut,
                'datefin' => $request->datefin ?? $dossier->datefin,
                'statut' => $statutGeneral,
                'statut_brouillon' => $statutBrouillon,
            ]);

            // Synchronisation des étudiants en conservant les fichiers CV déjà téléversés
            $oldStudents = $dossier->etudiants->keyBy('id_etudiant');
            $dossier->etudiants()->delete();

            if ($request->etudiants && is_array($request->etudiants)) {
                foreach ($request->etudiants as $index => $etuData) {
                    if (!$isSubmit && empty($etuData['nom']) && empty($etuData['prenom']) && empty($etuData['email'])) {
                        continue;
                    }

                    $cvName = $etuData['existing_cv'] ?? null;
                    if ($request->hasFile("etudiants.{$index}.cv_file")) {
                        $cvFile = $request->file("etudiants.{$index}.cv_file");
                        $cvName = 'cv_' . time() . '_' . $index . '.' . $cvFile->getClientOriginalExtension();
                        $cvFile->move(public_path('uploads/cv'), $cvName);
                    }

                    $debutEtu = !empty($etuData['datedebut_stage']) ? $etuData['datedebut_stage'] : $request->datedebut;
                    $finEtu = !empty($etuData['datefin_stage']) ? $etuData['datefin_stage'] : $request->datefin;

                    Etudiant::create([
                        'nom_etudiant' => $etuData['nom'] ?? 'Étudiant',
                        'prenom_etudiant' => $etuData['prenom'] ?? '',
                        'email_etu' => $etuData['email'] ?? null,
                        'niveau_etude' => $etuData['niveau_etude'] ?? 'Non spécifié',
                        'date_naissance' => !empty($etuData['date_naissance']) ? $etuData['date_naissance'] : null,
                        'datedebut_stage' => $debutEtu,
                        'datefin_stage' => $finEtu,
                        'cv' => $cvName,
                        'id_dossier' => $dossier->id_dossier,
                    ]);
                }
            }

            // Notification à l'admin si soumission
            if ($isSubmit) {
                AppNotification::notifier(
                    'admin',
                    'Dossier de Stage Soumis',
                    "L'établissement {$ecoleName} a finalisé et soumis le dossier #{$dossier->id_dossier} ({$dossier->filiere}) pour examen.",
                    route('admin.dossiers.show', $dossier->id_dossier),
                    'dossier_soumis'
                );
            }

            DB::commit();

            $msg = $isSubmit 
                ? "Le dossier #{$dossier->id_dossier} a été finalisé et SOUMIS à TFG SARL !"
                : "Les modifications du brouillon #{$dossier->id_dossier} ont été enregistrées avec succès.";

            return redirect()->route('ecole.dossiers.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'un dossier
     */
    public function show($id)
    {
        $idEcole = auth()->user()->id_ecole;

        $dossier = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants.documents'])
            ->findOrFail($id);

        return view('ecole.dossiers.show', compact('dossier'));
    }

    /**
     * Supprimer un dossier brouillon
     */
    public function destroy($id)
    {
        $idEcole = auth()->user()->id_ecole;
        $dossier = Dossier::where('id_ecole', $idEcole)->findOrFail($id);

        if ($dossier->statut_brouillon === 'soumis' && $dossier->statut === 'valide') {
            return back()->with('error', "Vous ne pouvez pas supprimer un dossier déjà validé par TFG SARL.");
        }

        $dossier->delete();
        return redirect()->route('ecole.dossiers.index')->with('success', "Le dossier a été supprimé.");
    }

    /**
     * Décliner une nouvelle date proposée par l'admin et rediriger vers la création d'un nouveau dossier
     */
    public function refuserNouvelleDate($id)
    {
        $idEcole = auth()->user()->id_ecole;
        $dossier = Dossier::where('id_ecole', $idEcole)->findOrFail($id);

        $dossier->statut = 'refuse';
        $dossier->motif_refus = "Période de stage réajustée par l'administration TFG SARL refusée par l'établissement. Demande à réintroduire.";
        $dossier->save();

        // Notifier les admins de la décision de l'école
        \App\Models\AppNotification::notifier(
            'admin',
            'Proposition de Date Refusée',
            "L'établissement " . ($dossier->ecole->nom_ecole ?? '') . " a décliné la période proposée pour le dossier {$dossier->code_dossier}.",
            route('admin.dossiers.show', $dossier->id_dossier),
            'dossier_refuse',
            null
        );

        return redirect()->route('ecole.dossiers.create')
            ->with('info', "La proposition de nouvelle date pour le dossier {$dossier->code_dossier} a été déclinée. Le dossier a été classé comme refusé. Vous pouvez dès à présent soumettre un nouveau dossier avec vos dates préférées.");
    }
}
