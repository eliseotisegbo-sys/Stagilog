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

        $dossiers = Dossier::where('id_ecole', $idEcole)
            ->with(['cycle', 'filiereRelation', 'etudiants'])
            ->when($search, function($query, $search) {
                $query->where('filiere', 'like', "%{$search}%")
                      ->orWhere('annee_academique', 'like', "%{$search}%")
                      ->orWhere('type_stage', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('ecole.dossiers.index', compact('dossiers', 'search'));
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

        $maxBirthDate = now()->subYears(16)->format('Y-m-d');

        $request->validate([
            'annee_academique' => 'required|string|max:50',
            'id_filiere' => 'required|exists:filieres,id_filiere',
            'id_cycle' => 'required|exists:cycles,id_cycle',
            'type_stage' => 'required|string|max:100',
            'datedebut' => 'required|date',
            'datefin' => 'required|date|after:datedebut',
            'note_demande_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            // Validation des étudiants avec niveau individuel et âge >= 16 ans
            'etudiants' => 'required|array|min:1',
            'etudiants.*.nom' => 'required|string|max:255',
            'etudiants.*.prenom' => 'required|string|max:255',
            'etudiants.*.email' => 'required|email|max:255',
            'etudiants.*.niveau_etude' => 'required|string|max:100',
            'etudiants.*.date_naissance' => 'nullable|date|before_or_equal:' . $maxBirthDate,
            'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'etudiants.min' => 'Vous devez renseigner au moins un étudiant pour ce dossier.',
            'etudiants.*.nom.required' => 'Le nom de l\'étudiant est obligatoire.',
            'etudiants.*.prenom.required' => 'Le prénom de l\'étudiant est obligatoire.',
            'etudiants.*.email.required' => 'L\'email de l\'étudiant est obligatoire.',
            'etudiants.*.niveau_etude.required' => 'Le niveau d\'étude est obligatoire pour chaque étudiant.',
            'etudiants.*.date_naissance.before_or_equal' => 'Chaque étudiant doit être âgé d\'au moins 16 ans révolus.',
            'datefin.after' => 'La date de fin doit être postérieure à la date de début.',
        ]);

        $filiereObj = Filiere::find($request->id_filiere);
        $isSubmit = ($request->input('action') === 'soumettre');
        $statutBrouillon = $isSubmit ? 'soumis' : 'brouillon';
        $statutGeneral = $isSubmit ? 'en_attente' : 'brouillon';

        DB::beginTransaction();
        try {
            // Upload Note de Demande
            $noteName = null;
            if ($request->hasFile('note_demande_file')) {
                $noteName = 'demande_' . $idEcole . '_' . time() . '.' . $request->file('note_demande_file')->getClientOriginalExtension();
                $request->file('note_demande_file')->move(public_path('uploads/notes'), $noteName);
            }

            // Créer le dossier
            $dossier = Dossier::create([
                'annee_academique' => $request->annee_academique,
                'filiere' => $filiereObj ? $filiereObj->nom_filiere : 'Autre',
                'sigle' => $request->sigle ?? ($filiereObj ? $filiereObj->sigle : null),
                'id_filiere' => $request->id_filiere,
                'id_cycle' => $request->id_cycle,
                'type_stage' => $request->type_stage,
                'niveau_etude' => $request->etudiants[0]['niveau_etude'] ?? 'Non spécifié',
                'note_demande' => $noteName,
                'datedebut' => $request->datedebut,
                'datefin' => $request->datefin,
                'id_ecole' => $idEcole,
                'statut' => $statutGeneral,
                'statut_brouillon' => $statutBrouillon,
            ]);

            // Ajouter les étudiants
            foreach ($request->etudiants as $index => $etuData) {
                $cvName = null;
                if ($request->hasFile("etudiants.{$index}.cv_file")) {
                    $cvFile = $request->file("etudiants.{$index}.cv_file");
                    $cvName = 'cv_' . time() . '_' . $index . '.' . $cvFile->getClientOriginalExtension();
                    $cvFile->move(public_path('uploads/cv'), $cvName);
                }

                Etudiant::create([
                    'nom_etudiant' => $etuData['nom'],
                    'prenom_etudiant' => $etuData['prenom'],
                    'email_etu' => $etuData['email'],
                    'niveau_etude' => $etuData['niveau_etude'],
                    'date_naissance' => $etuData['date_naissance'] ?? null,
                    'cv' => $cvName,
                    'id_dossier' => $dossier->id_dossier,
                ]);
            }

            // Si le dossier est soumis directement, notifier l'administrateur
            if ($isSubmit) {
                AppNotification::notifier(
                    'admin',
                    'Nouveau Dossier de Stage Soumis',
                    "L'établissement {$ecoleName} a soumis le dossier #{$dossier->id_dossier} ({$dossier->filiere}) comprenant " . count($request->etudiants) . " étudiant(s).",
                    route('admin.dossiers.show', $dossier->id_dossier),
                    'dossier_soumis'
                );
            }

            DB::commit();

            $msg = $isSubmit 
                ? "Votre dossier #{$dossier->id_dossier} a été transmis avec succès à TFG SARL pour validation !"
                : "Votre dossier #{$dossier->id_dossier} a été sauvegardé en BROUILLON. Vous pourrez le modifier ultérieurement.";

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

        $maxBirthDate = now()->subYears(16)->format('Y-m-d');

        $request->validate([
            'annee_academique' => 'required|string|max:50',
            'id_filiere' => 'required|exists:filieres,id_filiere',
            'id_cycle' => 'required|exists:cycles,id_cycle',
            'type_stage' => 'required|string|max:100',
            'datedebut' => 'required|date',
            'datefin' => 'required|date|after:datedebut',
            'note_demande_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            
            'etudiants' => 'required|array|min:1',
            'etudiants.*.nom' => 'required|string|max:255',
            'etudiants.*.prenom' => 'required|string|max:255',
            'etudiants.*.email' => 'required|email|max:255',
            'etudiants.*.niveau_etude' => 'required|string|max:100',
            'etudiants.*.date_naissance' => 'nullable|date|before_or_equal:' . $maxBirthDate,
            'etudiants.*.cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'etudiants.min' => 'Vous devez renseigner au moins un étudiant.',
            'etudiants.*.niveau_etude.required' => 'Le niveau d\'étude est obligatoire pour chaque étudiant.',
            'etudiants.*.date_naissance.before_or_equal' => 'Chaque étudiant doit être âgé d\'au moins 16 ans révolus.',
        ]);

        $filiereObj = Filiere::find($request->id_filiere);
        $isSubmit = ($request->input('action') === 'soumettre');
        $statutBrouillon = $isSubmit ? 'soumis' : 'brouillon';
        $statutGeneral = $isSubmit ? 'en_attente' : 'brouillon';

        DB::beginTransaction();
        try {
            // Note de demande
            $noteName = $dossier->note_demande;
            if ($request->hasFile('note_demande_file')) {
                $noteName = 'demande_' . $idEcole . '_' . time() . '.' . $request->file('note_demande_file')->getClientOriginalExtension();
                $request->file('note_demande_file')->move(public_path('uploads/notes'), $noteName);
            }

            $dossier->update([
                'annee_academique' => $request->annee_academique,
                'filiere' => $filiereObj ? $filiereObj->nom_filiere : $dossier->filiere,
                'sigle' => $request->sigle ?? ($filiereObj ? $filiereObj->sigle : $dossier->sigle),
                'id_filiere' => $request->id_filiere,
                'id_cycle' => $request->id_cycle,
                'type_stage' => $request->type_stage,
                'niveau_etude' => $request->etudiants[0]['niveau_etude'] ?? $dossier->niveau_etude,
                'note_demande' => $noteName,
                'datedebut' => $request->datedebut,
                'datefin' => $request->datefin,
                'statut' => $statutGeneral,
                'statut_brouillon' => $statutBrouillon,
            ]);

            // Synchronisation des étudiants
            $dossier->etudiants()->delete();

            foreach ($request->etudiants as $index => $etuData) {
                $cvName = $etuData['existing_cv'] ?? null;
                if ($request->hasFile("etudiants.{$index}.cv_file")) {
                    $cvFile = $request->file("etudiants.{$index}.cv_file");
                    $cvName = 'cv_' . time() . '_' . $index . '.' . $cvFile->getClientOriginalExtension();
                    $cvFile->move(public_path('uploads/cv'), $cvName);
                }

                Etudiant::create([
                    'nom_etudiant' => $etuData['nom'],
                    'prenom_etudiant' => $etuData['prenom'],
                    'email_etu' => $etuData['email'],
                    'niveau_etude' => $etuData['niveau_etude'],
                    'date_naissance' => $etuData['date_naissance'] ?? null,
                    'cv' => $cvName,
                    'id_dossier' => $dossier->id_dossier,
                ]);
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
            ->with(['cycle', 'filiereRelation', 'etudiants'])
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
}
