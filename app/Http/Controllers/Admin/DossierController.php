<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Ecole;
use App\Models\Etudiant;
use App\Models\AppSetting;
use App\Mail\DossierValideMail;
use App\Mail\DossierRefuseMail;
use App\Mail\EtudiantStageValideMail;
use App\Mail\PeriodeStageModifieeMail;
use App\Mail\PeriodeEtudiantModifieeMail;
use App\Mail\PeriodeDepotDossiersMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DossierController extends Controller
{
    /**
     * Liste des dossiers soumis
     */
    public function index(Request $request)
    {
        $status = $request->query('statut');
        $ecoleId = $request->query('ecole_id');

        $dossiers = Dossier::with(['ecole', 'cycle', 'filiereRelation', 'etudiants'])
            ->where('statut_brouillon', 'soumis')
            ->when($status && in_array($status, ['en_attente', 'valide', 'refuse', 'sous_reserve']), function($q) use ($status) {
                $q->where('statut', $status);
            })
            ->when($ecoleId, function($q) use ($ecoleId) {
                $q->where('id_ecole', $ecoleId);
            })
            ->latest()
            ->paginate(15);

        $ecoles = Ecole::all();
        $countAttente = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'en_attente')->count();
        $countSousReserve = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'sous_reserve')->count();
        $countValide = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'valide')->count();
        $countRefuse = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'refuse')->count();
        $countTotal = Dossier::where('statut_brouillon', 'soumis')->count();

        // Paramètres de la campagne de dépôt
        $depotActif = AppSetting::get('depot_dossiers_actif', '1') === '1';
        $depotDebut = AppSetting::get('depot_date_debut');
        $depotFin = AppSetting::get('depot_date_fin');
        $depotInstructions = AppSetting::get('depot_instructions');

        return view('admin.dossiers.index', compact(
            'dossiers', 
            'status', 
            'ecoleId', 
            'ecoles', 
            'countAttente', 
            'countSousReserve',
            'countValide', 
            'countRefuse', 
            'countTotal',
            'depotActif',
            'depotDebut',
            'depotFin',
            'depotInstructions'
        ));
    }

    /**
     * Définir la période officielle de dépôt des dossiers et notifier les écoles par email
     */
    public function configurerDepots(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'instructions' => 'nullable|string|max:1000',
            'notifier_ecoles' => 'nullable',
        ]);

        AppSetting::set('depot_date_debut', $request->date_debut);
        AppSetting::set('depot_date_fin', $request->date_fin);
        AppSetting::set('depot_instructions', $request->instructions ?? '');
        AppSetting::set('depot_dossiers_actif', '1');

        $notifiedCount = 0;
        if ($request->has('notifier_ecoles')) {
            $ecoles = Ecole::all();
            foreach ($ecoles as $ecole) {
                $email = $ecole->email ?? $ecole->mail;
                if ($email) {
                    try {
                        Mail::to($email)->send(new PeriodeDepotDossiersMail($ecole, $request->date_debut, $request->date_fin, $request->instructions));
                        $notifiedCount++;
                    } catch (\Exception $e) {
                        Log::error("Erreur envoi notification période dépôt à l'école ID {$ecole->id_ecole}: " . $e->getMessage());
                    }
                }
            }
        }

        $msg = "La période officielle de dépôt des dossiers a été configurée avec succès (du " . \Carbon\Carbon::parse($request->date_debut)->format('d/m/Y') . " au " . \Carbon\Carbon::parse($request->date_fin)->format('d/m/Y') . ").";
        if ($notifiedCount > 0) {
            $msg .= " Un email officiel a été envoyé à {$notifiedCount} établissement(s) partenaire(s).";
        }

        return redirect()->route('admin.dossiers.index')->with('success', $msg);
    }

    /**
     * Activer ou désactiver rapidement les dépôts de dossiers
     */
    public function toggleDepots(Request $request)
    {
        $current = AppSetting::get('depot_dossiers_actif', '1');
        $newStatus = $current === '1' ? '0' : '1';
        AppSetting::set('depot_dossiers_actif', $newStatus);

        $statusText = $newStatus === '1' ? 'ouverts (activés)' : 'désactivés (fermés)';
        return redirect()->route('admin.dossiers.index')->with('success', "Les dépôts de dossiers sont désormais {$statusText}.");
    }

    /**
     * Examiner un dossier
     */
    public function show($id)
    {
        $dossier = Dossier::with(['ecole', 'cycle', 'filiereRelation', 'etudiants.documents'])->findOrFail($id);

        return view('admin.dossiers.show', compact('dossier'));
    }

    /**
     * Modifier la durée / dates de stage globales par l'administrateur
     */
    public function modifierPeriode(Request $request, $id)
    {
        $request->validate([
            'datedebut' => 'required|date',
            'datefin' => 'required|date|after:datedebut',
        ], [
            'datedebut.required' => 'La date de début est obligatoire.',
            'datefin.required' => 'La date de fin est obligatoire.',
            'datefin.after' => 'La date de fin doit être postérieure à la date de début.',
        ]);

        $adminUser = auth()->user();
        $adminName = session('user_session_name') ?? ($adminUser->name ?? 'Administrateur TFG SARL');

        $dossier = Dossier::with(['ecole', 'etudiants'])->findOrFail($id);
        $dossier->datedebut = $request->datedebut;
        $dossier->datefin = $request->datefin;
        
        // Si le dossier n'est pas encore validé, passer en statut "sous_reserve"
        if ($dossier->statut !== 'valide') {
            $dossier->statut = 'sous_reserve';
        }
        $dossier->save();

        $codeDossier = $dossier->code_dossier ?? 'STAGE-' . $dossier->id_dossier;

        // Notification envoyée à l'école
        \App\Models\AppNotification::notifier(
            'ecole',
            'Période de Stage Réajustée (Sous Réserve)',
            "La période du dossier {$codeDossier} a été réajustée sous réserve par l'administrateur {$adminName} (du " . \Carbon\Carbon::parse($request->datedebut)->format('d/m/Y') . " au " . \Carbon\Carbon::parse($request->datefin)->format('d/m/Y') . ").",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_modifie',
            $dossier->id_ecole
        );

        // Notification envoyée aux administrateurs
        \App\Models\AppNotification::notifier(
            'admin',
            'Période de Stage Modifiée par ' . $adminName,
            "L'administrateur {$adminName} a modifié la période du dossier {$codeDossier}.",
            route('admin.dossiers.show', $dossier->id_dossier),
            'dossier_modifie',
            null
        );

        // Envoi d'email à l'école
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new PeriodeStageModifieeMail($dossier, $adminName));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email modification période école ({$codeDossier}) : " . $e->getMessage());
            }
        }

        // Envoi d'email à chaque étudiant du dossier
        foreach ($dossier->etudiants as $etudiant) {
            if ($etudiant->email_etu && filter_var($etudiant->email_etu, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($etudiant->email_etu)->send(new PeriodeStageModifieeMail($dossier, $adminName));
                } catch (\Exception $e) {
                    Log::warning("Erreur envoi email modification période étudiant ({$etudiant->email_etu}) : " . $e->getMessage());
                }
            }
        }

        return back()->with('success', "La période globale de stage a été mise à jour par l'administrateur {$adminName}. Le dossier est classé sous réserve et un email de notification a été envoyé à l'école et aux candidats.");
    }

    /**
     * Modifier la période individuelle de stage d'un étudiant par l'administrateur
     */
    public function modifierPeriodeEtudiant(Request $request, $id, $etudiantId)
    {
        $request->validate([
            'datedebut_stage' => 'required|date',
            'datefin_stage' => 'required|date|after:datedebut_stage',
        ], [
            'datedebut_stage.required' => 'La date de début est obligatoire.',
            'datefin_stage.required' => 'La date de fin est obligatoire.',
            'datefin_stage.after' => 'La date de fin doit être postérieure à la date de début.',
        ]);

        $adminUser = auth()->user();
        $adminName = session('user_session_name') ?? ($adminUser->name ?? 'Administrateur TFG SARL');

        $dossier = Dossier::with(['ecole'])->findOrFail($id);
        $etudiant = Etudiant::where('id_dossier', $id)->findOrFail($etudiantId);

        $etudiant->datedebut_stage = $request->datedebut_stage;
        $etudiant->datefin_stage = $request->datefin_stage;
        $etudiant->save();

        if ($dossier->statut !== 'valide') {
            $dossier->statut = 'sous_reserve';
            $dossier->save();
        }

        $codeDossier = $dossier->code_dossier ?? 'STAGE-' . $dossier->id_dossier;
        $etuName = $etudiant->nom_etudiant . ' ' . $etudiant->prenom_etudiant;

        // Notification interne école
        \App\Models\AppNotification::notifier(
            'ecole',
            "Période réajustée pour {$etuName}",
            "La période de stage de {$etuName} (Dossier {$codeDossier}) a été modifiée par l'administrateur {$adminName} (du " . \Carbon\Carbon::parse($request->datedebut_stage)->format('d/m/Y') . " au " . \Carbon\Carbon::parse($request->datefin_stage)->format('d/m/Y') . ").",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_modifie',
            $dossier->id_ecole
        );

        // Notification interne admin
        \App\Models\AppNotification::notifier(
            'admin',
            "Période ajustée pour {$etuName}",
            "L'administrateur {$adminName} a ajusté la période de {$etuName} dans le dossier {$codeDossier}.",
            route('admin.dossiers.show', $dossier->id_dossier),
            'dossier_modifie',
            null
        );

        // Mail à l'étudiant
        if ($etudiant->email_etu && filter_var($etudiant->email_etu, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($etudiant->email_etu)->send(new PeriodeEtudiantModifieeMail($dossier, $etudiant, $adminName, 'etudiant'));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email modification période étudiant ({$etudiant->email_etu}) : " . $e->getMessage());
            }
        }

        // Mail à l'école
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new PeriodeEtudiantModifieeMail($dossier, $etudiant, $adminName, 'ecole'));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email modification période étudiant à l'école ({$codeDossier}) : " . $e->getMessage());
            }
        }

        return back()->with('success', "La période de stage pour {$etuName} a été enregistrée avec succès. Un email de notification a été adressé à l'étudiant et à l'école.");
    }

    /**
     * Refuser individuellement un étudiant dans un dossier avec motif
     */
    public function refuserEtudiant(Request $request, $id, $etudiantId)
    {
        $request->validate([
            'motif_refus' => 'required|string|min:3|max:1000',
        ], [
            'motif_refus.required' => 'Veuillez préciser le motif du refus pour cet étudiant.',
        ]);

        $etudiant = Etudiant::where('id_dossier', $id)->findOrFail($etudiantId);
        $etudiant->statut_etudiant = 'refuse';
        $etudiant->motif_refus = $request->motif_refus;
        $etudiant->save();

        $etuName = $etudiant->nom_etudiant . ' ' . $etudiant->prenom_etudiant;
        return back()->with('success', "L'étudiant {$etuName} a été marqué comme non retenu (refusé). Le motif a été enregistré.");
    }

    /**
     * Rétablir un étudiant préalablement refusé
     */
    public function retablirEtudiant($id, $etudiantId)
    {
        $etudiant = Etudiant::where('id_dossier', $id)->findOrFail($etudiantId);
        $etudiant->statut_etudiant = 'en_attente';
        $etudiant->motif_refus = null;
        $etudiant->save();

        $etuName = $etudiant->nom_etudiant . ' ' . $etudiant->prenom_etudiant;
        return back()->with('success', "L'étudiant {$etuName} a été rétabli dans la liste des candidats.");
    }

    /**
     * Valider le dossier (même s'il était précédemment refusé ou sous réserve)
     */
    public function valider($id)
    {
        $adminUser = auth()->user();
        $adminName = session('user_session_name') ?? ($adminUser->name ?? 'Administrateur TFG SARL');

        $dossier = Dossier::with(['ecole', 'cycle', 'etudiants'])->findOrFail($id);
        $dossier->statut = 'valide';
        $dossier->valide_par = $adminName;
        $dossier->valide_par_id = $adminUser ? $adminUser->id : null;
        $dossier->motif_refus = null;
        $dossier->save();

        $codeDossier = $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '');

        // 1. Notification interne envoyée à l'école
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Validé',
            "Excellente nouvelle ! Le dossier {$codeDossier} ({$dossier->filiere}) a été validé par la direction TFG SARL.",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_valide',
            $dossier->id_ecole
        );

        // 2. Notification aux administrateurs
        \App\Models\AppNotification::notifier(
            'admin',
            'Dossier Validé par ' . $adminName,
            "Le dossier {$codeDossier} ({$dossier->filiere}) a été validé par l'administrateur {$adminName}.",
            route('admin.dossiers.show', $dossier->id_dossier),
            'dossier_valide',
            null
        );

        // 3. Envoi réel d'email récapitulatif à l'école
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new DossierValideMail($dossier));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email validation dossier école ({$codeDossier}) : " . $e->getMessage());
            }
        }

        // 4. Envoi réel d'email automatique à CHAQUE étudiant du dossier selon son statut
        foreach ($dossier->etudiants as $etudiant) {
            if ($etudiant->email_etu && filter_var($etudiant->email_etu, FILTER_VALIDATE_EMAIL)) {
                try {
                    if ($etudiant->statut_etudiant === 'refuse') {
                        Mail::to($etudiant->email_etu)->send(new \App\Mail\EtudiantStageRefuseMail($dossier, $etudiant, $etudiant->motif_refus));
                    } else {
                        $etudiant->statut_etudiant = 'valide';
                        $etudiant->save();
                        Mail::to($etudiant->email_etu)->send(new EtudiantStageValideMail($dossier, $etudiant));
                    }
                } catch (\Exception $e) {
                    Log::warning("Erreur envoi email étudiant ({$etudiant->email_etu}) : " . $e->getMessage());
                }
            } else {
                if ($etudiant->statut_etudiant !== 'refuse') {
                    $etudiant->statut_etudiant = 'valide';
                    $etudiant->save();
                }
            }
        }

        return redirect()->route('admin.dossiers.show', $id)
            ->with('success', "Le dossier {$codeDossier} ({$dossier->filiere}) a été VALIDÉ avec succès ! Les emails de confirmation et d'information ont été transmis à l'école et à l'ensemble des candidats.");
    }

    /**
     * Refuser le dossier avec motif
     */
    public function refuser(Request $request, $id)
    {
        $request->validate([
            'motif_refus' => 'required|string|min:5|max:1000',
        ], [
            'motif_refus.required' => 'Veuillez préciser le motif du refus pour notifier l\'école.',
        ]);

        $adminUser = auth()->user();
        $adminName = session('user_session_name') ?? ($adminUser->name ?? 'Administrateur TFG SARL');

        $dossier = Dossier::with(['ecole', 'cycle', 'etudiants'])->findOrFail($id);
        $dossier->statut = 'refuse';
        $dossier->refuse_par = $adminName;
        $dossier->motif_refus = $request->motif_refus;
        $dossier->save();

        $codeDossier = $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '');

        // 1. Notification interne envoyée à l'école avec motif
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Non Retenu',
            "Le dossier {$codeDossier} ({$dossier->filiere}) n'a pas été retenu par la direction. Motif : {$request->motif_refus}",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_refuse',
            $dossier->id_ecole
        );

        // 2. Notification aux administrateurs avec le nom de l'admin
        \App\Models\AppNotification::notifier(
            'admin',
            'Dossier Refusé par ' . $adminName,
            "Le dossier {$codeDossier} ({$dossier->filiere}) a été refusé par l'administrateur {$adminName}. Motif : {$request->motif_refus}",
            route('admin.dossiers.show', $dossier->id_dossier),
            'dossier_refuse',
            null
        );

        // 3. Envoi réel d'email de refus avec motif à l'école
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new DossierRefuseMail($dossier, $request->motif_refus));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email refus dossier ({$codeDossier}) : " . $e->getMessage());
            }
        }

        // 4. Envoi réel d'email de refus avec motif à CHAQUE étudiant du dossier
        foreach ($dossier->etudiants as $etudiant) {
            if ($etudiant->email_etu && filter_var($etudiant->email_etu, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($etudiant->email_etu)->send(new DossierRefuseMail($dossier, $request->motif_refus));
                } catch (\Exception $e) {
                    Log::warning("Erreur envoi email refus étudiant ({$etudiant->email_etu}) : " . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.dossiers.show', $id)
            ->with('error', "Le dossier {$codeDossier} a été REFUSÉ. L'école et tous les étudiants ont été notifiés par email avec le motif enregistré.");
    }

    /**
     * Supprimer un dossier
     */
    public function destroy($id)
    {
        $dossier = Dossier::findOrFail($id);
        $dossier->delete();

        return redirect()->route('admin.dossiers.index')->with('success', "Dossier supprimé avec succès.");
    }
}
