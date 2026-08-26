<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Ecole;
use Illuminate\Http\Request;

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
        $dossier = Dossier::findOrFail($id);
        $dossier->statut = 'valide';
        $dossier->motif_refus = null;
        $dossier->save();

        // Notification envoyée à l'école
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Validé',
            "Excellente nouvelle ! Votre dossier #{$dossier->id_dossier} ({$dossier->filiere}) a été validé par la direction de TFG SARL.",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_valide',
            $dossier->id_ecole
        );

        return redirect()->route('admin.dossiers.show', $id)
            ->with('success', "Le dossier #{$dossier->id_dossier} ({$dossier->filiere}) a été VALIDÉ avec succès !");
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

        $dossier = Dossier::findOrFail($id);
        $dossier->statut = 'refuse';
        $dossier->motif_refus = $request->motif_refus;
        $dossier->save();

        // Notification envoyée à l'école avec motif
        \App\Models\AppNotification::notifier(
            'ecole',
            'Dossier de Stage Non Retenu',
            "Votre dossier #{$dossier->id_dossier} ({$dossier->filiere}) n'a pas été retenu. Motif : {$request->motif_refus}",
            route('ecole.dossiers.show', $dossier->id_dossier),
            'dossier_refuse',
            $dossier->id_ecole
        );

        return redirect()->route('admin.dossiers.show', $id)
            ->with('error', "Le dossier #{$dossier->id_dossier} a été REFUSÉ. Motif enregistré.");
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
