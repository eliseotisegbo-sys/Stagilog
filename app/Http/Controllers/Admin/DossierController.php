<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Ecole;
use App\Mail\DossierValideMail;
use App\Mail\DossierRefuseMail;
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
            ->when($status && in_array($status, ['en_attente', 'valide', 'refuse']), function($q) use ($status) {
                $q->where('statut', $status);
            })
            ->when($ecoleId, function($q) use ($ecoleId) {
                $q->where('id_ecole', $ecoleId);
            })
            ->latest()
            ->paginate(10);

        $ecoles = Ecole::all();
        $countAttente = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'en_attente')->count();
        $countValide = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'valide')->count();
        $countRefuse = Dossier::where('statut_brouillon', 'soumis')->where('statut', 'refuse')->count();
        $countTotal = Dossier::where('statut_brouillon', 'soumis')->count();

        return view('admin.dossiers.index', compact(
            'dossiers', 
            'status', 
            'ecoleId', 
            'ecoles', 
            'countAttente', 
            'countValide', 
            'countRefuse', 
            'countTotal'
        ));
    }

    /**
     * Examiner un dossier
     */
    public function show($id)
    {
        $dossier = Dossier::with(['ecole', 'cycle', 'filiereRelation', 'etudiants'])->findOrFail($id);

        return view('admin.dossiers.show', compact('dossier'));
    }

    /**
     * Valider le dossier
     */
    public function valider($id)
    {
        $dossier = Dossier::with(['ecole', 'cycle', 'etudiants'])->findOrFail($id);
        $dossier->statut = 'valide';
        $dossier->motif_refus = null;
        $dossier->save();

        $codeDossier = $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '');

        // 1. Notification interne envoyée à l'école
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Validé',
            "Excellente nouvelle ! Le dossier {$codeDossier} ({$dossier->filiere}) a été validé par la direction de TFG SARL.",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_valide',
            $dossier->id_ecole
        );

        // 2. Envoi réel d'email de validation via stagilogtfg@gmail.com
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new DossierValideMail($dossier));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email validation dossier ({$codeDossier}) : " . $e->getMessage());
            }
        }

        return redirect()->route('admin.dossiers.show', $id)
            ->with('success', "Le dossier {$codeDossier} ({$dossier->filiere}) a été VALIDÉ avec succès ! Un email de confirmation a été transmis à l'école.");
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

        $dossier = Dossier::with(['ecole', 'cycle', 'etudiants'])->findOrFail($id);
        $dossier->statut = 'refuse';
        $dossier->motif_refus = $request->motif_refus;
        $dossier->save();

        $codeDossier = $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '');

        // 1. Notification interne envoyée à l'école avec motif
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Non Retenu',
            "Le dossier {$codeDossier} ({$dossier->filiere}) n'a pas été retenu. Motif : {$request->motif_refus}",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_refuse',
            $dossier->id_ecole
        );

        // 2. Envoi réel d'email de refus avec motif via stagilogtfg@gmail.com
        if ($dossier->ecole && ($dossier->ecole->email || $dossier->ecole->mail)) {
            $destEmail = $dossier->ecole->email ?? $dossier->ecole->mail;
            try {
                Mail::to($destEmail)->send(new DossierRefuseMail($dossier, $request->motif_refus));
            } catch (\Exception $e) {
                Log::warning("Erreur envoi email refus dossier ({$codeDossier}) : " . $e->getMessage());
            }
        }

        return redirect()->route('admin.dossiers.show', $id)
            ->with('error', "Le dossier {$codeDossier} a été REFUSÉ. L'école a été notifiée par email avec le motif enregistré.");
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
